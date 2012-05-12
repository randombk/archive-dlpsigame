<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

//Stolen from http://stackoverflow.com/questions/1724587/how-to-store-php-sessions-in-apc-cache
class GameSession {
	protected $_prefix;
	protected $_ttl;
	protected $_lockTimeout = 60; // if empty, no session locking, otherwise seconds to lock timeout
	protected $_playerID = -1;
	
	public static $instance = null;
	
	public function __construct($params = array()) {
		ini_set('session.use_cookies', '1');
		$def = session_get_cookie_params();
		$this->_ttl = $def['lifetime'];
		if (isset($params['ttl'])) {
			$this->_ttl = $params['ttl'];
		}
		
		if (isset($params['lock_timeout'])) {
			$this->_lockTimeout = $params['lock_timeout'];
		}

		session_set_save_handler(
			array($this, 'open'), 
			array($this, 'close'), 
			array($this, 'read'), 
			array($this, 'write'), 
			array($this, 'destroy'), 
			array($this, 'gc')
		);
	}

	public function open($savePath, $sessionName) {
		$this->_prefix = $sessionName;
		if (!apc_exists($this->_prefix)) {
			// creating non-empty array @see http://us.php.net/manual/en/function.apc-store.php#107359
			apc_store($this->_prefix . '/TS', array(''));
			apc_store($this->_prefix . '/LOCK', array(''));
		}
		return true;
	}

	public function close() {
		return true;
	}

	public function read($id) {
		$key = $this->_prefix . '/' . $id;
		if (!apc_exists($key)) {
			return ''; // no session
		}

		// redundant check for ttl before read
		if ($this->_ttl) {
			$ts = apc_fetch($this->_prefix . '/TS');
			if (empty($ts[$id])) {
				return ''; // no session
			} elseif (!empty($ts[$id]) && $ts[$id] + $this->_ttl < time()) {
				unset($ts[$id]);
				apc_delete($key);
				apc_store($this->_prefix . '/TS', $ts);
				return ''; // session expired
			}
		}
		
		if(!$this->_lockTimeout) {
			$locks = apc_fetch($this->_prefix . '/LOCK');
			if (!empty($locks[$id])) {
				while (!empty($locks[$id]) && $locks[$id] + $this->_lockTimeout >= time()) {
					usleep(10000); // sleep 10ms
					$locks = apc_fetch($this->_prefix . '/LOCK');
				}
			}
			/*
			  // by default will overwrite session after lock expired to allow smooth site function
			  // alternative handling is to abort current process
			  if (!empty($locks[$id])) {
			  return false; // abort read of waiting for lock timed out
			  }
			 */
			$locks[$id] = time(); // set session lock
			apc_store($this->_prefix . '/LOCK', $locks);
		}

		$ip = apc_fetch($this->_prefix . '/IP/' . $id);
		if(!$this->CompareIPs($ip)) {
			return ''; // stolen session
		}
				
		if($this->_playerID > 0) {
			if($activeID != $id) {
				return ""; //User logged in from another location
			}
		}
		return apc_fetch($key);
	}

	public function write($id, $data) {
		$ts = apc_fetch($this->_prefix . '/TS');
		$ts[$id] = time();
		apc_store($this->_prefix . '/TS', $ts);

		$locks = apc_fetch($this->_prefix . '/LOCK');
		unset($locks[$id]);
		apc_store($this->_prefix . '/LOCK', $locks);
		
		apc_store($this->_prefix . '/IP/' . $id, $_SERVER['REMOTE_ADDR']);
		
		if(isset($_SESSION['playerID'])) {
			$oldID = apc_fetch($this->_prefix . '/USERS/' . $_SESSION['playerID']);
			apc_delete($this->_prefix . '/' . $oldID);
			apc_store($this->_prefix . '/USERS/' . $_SESSION['playerID'], $id);
		}
		
		return apc_store($this->_prefix . '/' . $id, $data, $this->_ttl);
	}

	public function destroy($id) {
		$ts = apc_fetch($this->_prefix . '/TS');
		unset($ts[$id]);
		apc_store($this->_prefix . '/TS', $ts);

		$locks = apc_fetch($this->_prefix . '/LOCK');
		unset($locks[$id]);
		apc_store($this->_prefix . '/LOCK', $locks);

		return apc_delete($this->_prefix . '/' . $id);
	}

	public function gc($lifetime) {
		if ($this->_ttl) {
			$lifetime = min($lifetime, $this->_ttl);
		}
		$ts = apc_fetch($this->_prefix . '/TS');
		foreach ($ts as $id => $time) {
			if ($time + $lifetime < time()) {
				apc_delete($this->_prefix . '/' . $id);
				unset($ts[$id]);
			}
		}
		return apc_store($this->_prefix . '/TS', $ts);
	}

	private static function create() {
		session_name("gameSession");
		self::$instance = new self;
		session_start();
		return self::$instance;
	}
	
	public static function loginPlayer($playerID, $playerName) {
		self::create();
		$_SESSION['playerID'] = $playerID;
		$_SESSION['playerName'] = $playerName;
	}
	
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

?>
