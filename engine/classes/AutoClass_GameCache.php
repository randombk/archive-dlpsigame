<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class GameCache
 */
class GameCache {
	/**
	 * @param $varName
	 * @return mixed
	 */
	public static function get($varName){
		if(isset($GLOBALS["GameCache"][$varName])) {
			return $GLOBALS["GameCache"][$varName];
		} else {
			if(apc_exists('CachedResource/' . $varName)) {
				$GLOBALS["GameCache"][$varName] = apc_fetch('CachedResource/' . $varName);
				return $GLOBALS["GameCache"][$varName];
			} else {
				return self::load($varName);
			}	
		}
	}

	/**
	 * @param $varName
	 * @return mixed
	 * @throws Exception
	 */
	private static function load($varName) {
		try {
			$varClass = 'CachedResource_' . ucwords($varName);
			$classSrc = ROOT_PATH . 'engine/classes/GameCache/'.$varClass.'.php';
			require_once($classSrc);
			
			$GLOBALS["GameCache"][$varName] = $varClass::loadGameResource();
			apc_store('CachedResource', TIMESTAMP);
			return $GLOBALS["GameCache"][$varName];
		} catch (Exception $e) {
			throw new Exception("Invalid Resource Name: " . $varName);
		}
	}

	/**
	 * @param $varName
	 * @return mixed
	 */
	public static function reload($varName) {
		return self::load($varName);
	}

	/**
	 * @return mixed
	 */
	public static function getCacheTime() {
		return apc_fetch('CachedResource');
	}
	
	//If null, delete all
	/**
	 * @param null $toDelete
	 * @return bool|string[]
	 */
	public static function flush($toDelete = null){
		if(is_null($toDelete)) {
			$toDelete = new APCIterator('user', '/^CachedResource/', APC_ITER_VALUE);
		}
		return apc_delete($toDelete); 
	}
}

?>
