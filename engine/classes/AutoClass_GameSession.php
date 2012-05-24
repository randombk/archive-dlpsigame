<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

//Written based on the example from http://stackoverflow.com/questions/1724587/how-to-store-php-sessions-in-apc-cache
/**
 * Class GameSession
 */
class GameSession {
	protected $_sessionName;
	protected $_ttl;
	protected $_lockTimeout; // if empty, no session locking, otherwise seconds to lock timeout
	
	public static $instance = null;

	/**
	 * @param array $params
	 */
	public function __construct($params = array()) {
		ini_set('session.use_cookies', '1');
		$this->_ttl = 60*60*12;
		$this->_lockTimeout = 10;
		
		session_set_save_handler(
			array($this, 'open'), 
			array($this, 'close'), 
			array($this, 'read'), 
			array($this, 'write'), 
			array($this, 'destroy'), 
			array($this, 'gc')
		);
	}

	/**
	 * @param $savePath
	 * @param $sessionName
	 * @return bool
	 */
	public function open($savePath, $sessionName) {
		$this->_sessionName = $sessionName;
		return true;
	}

	/**
	 * @return bool
	 */
	public function close() {
		return true;
	}

	/**
	 * @param $id
	 * @return mixed|string
	 */
	public function read($id) {
		$baseKey = $this->_sessionName . '/' . $id;
		$metaKey = $baseKey . '/META';
		if (!(apc_exists($baseKey) && apc_exists($metaKey))) {
			return ''; // no session
		}
		
		$meta = apc_fetch($metaKey);
		
		while ($meta["lock"] > 0 && $meta["lock"] + $this->_lockTimeout >= time()) {
			usleep(10000); // sleep 10ms
			$meta = apc_fetch($metaKey);
		}
				
		if ($meta["lastAccess"] + $this->_ttl < time()) {
			$this->destroy($id);
			return ''; // session expired
		}
		
		if(!$this->CompareIPs($meta["ip"])) {
			return ''; // stolen session
		}
		
		$meta["lock"] = time();
		$meta["lastAccess"] = time();
		apc_store($metaKey, $meta);
		
		return apc_fetch($baseKey);
	}

	/**
	 * @param $id
	 * @param $data
	 * @return bool
	 */
	public function write($id, $data) {
		$baseKey = $this->_sessionName . '/' . $id;
		$meta = array(
			"lastAccess"=> time(),
			"lock"		=> -1,
			"ip"		=> $_SERVER['REMOTE_ADDR']
		);
		
		$success = apc_store($baseKey, $data, $this->_ttl) && apc_store($baseKey.'/META', $meta, $this->_ttl);
		
		if(isset($_SESSION['playerID'])) {
			$oldID = apc_fetch($this->_sessionName . '/USERS/' . $_SESSION['playerID']);
			if($oldID != $id) {
				$this->destroy($oldID);
				apc_store($this->_sessionName . '/USERS/' . $_SESSION['playerID'], $id);
			}
		}
		return $success;
	}

	/**
	 * @param $id
	 * @return bool
	 */
	public function destroy($id) {
		apc_delete($this->_sessionName . '/' . $id);
		apc_delete($this->_sessionName . '/' . $id . '/META');
			
		return true;
	}

	/**
	 * @param $lifetime
	 * @return bool
	 */
	public function gc($lifetime) {
		//Trigger APC's internal time-to-live cleanup
		apc_cache_info();
		return true;
	}

	/**
	 * @return GameSession|null
	 */
	private static function create() {
		if(is_null(self::$instance)) {
			session_name("gameSession");
			self::$instance = new self;
			session_start();
		}
		return self::$instance;
	}

	/**
	 * @param int $playerID
	 * @param string $playerName
	 */
	public static function loginPlayer($playerID, $playerName) {
		self::create();
		$_SESSION['playerID'] = $playerID;
		$_SESSION['playerName'] = $playerName;
	}

	/**
	 * @return bool
	 */
	public static function isLoggedIn() {
		self::create();
		if(!is_null(self::$instance) && isset($_SESSION['playerID'])) {
			return true;
		} else {
			return false;
		}
	}
	
	public static function DestroySession() {
		@session_destroy();
		self::$instance = null;
	}

	/**
	 * @param $IP
	 * @return bool
	 */
	public function CompareIPs($IP) {
		if (strpos($_SERVER['REMOTE_ADDR'], ':') !== false && strpos($IP, ':') !== false) {
			$s_ip = $this->short_ipv6($IP, COMPARE_IP_BLOCKS);
			$u_ip = $this->short_ipv6($_SERVER['REMOTE_ADDR'], COMPARE_IP_BLOCKS);
		} else {
			$s_ip = implode('.', array_slice(explode('.', $IP), 0, COMPARE_IP_BLOCKS));
			$u_ip = implode('.', array_slice(explode('.', $_SERVER['REMOTE_ADDR']), 0, COMPARE_IP_BLOCKS));
		}

		return ($s_ip == $u_ip);
	}

	//From http://ftp.phpbb-fr.com/public/cdd/phpbb3/3.0.9/nav.html?_functions/index.html
	/**
	 * @param $ip
	 * @param $length
	 * @return mixed|string
	 */
	public function short_ipv6($ip, $length) {
		if ($length < 1) {
			return '';
		}
		$blocks = substr_count($ip, ':') + 1;
		if ($blocks < 9) {
			$ip = str_replace('::', ':' . str_repeat('0000:', 9 - $blocks), $ip);
		}
		if ($ip[0] == ':') {
			$ip = '0000' . $ip;
		}
		if ($length < 4) {
			$ip = implode(':', array_slice(explode(':', $ip), 0, 1 + $length));
		}
		return $ip;
	}
}