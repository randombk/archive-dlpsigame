<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

class GameCache {
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
	
	public static function load($varName) {
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
	
	public static function getCacheTime() {
		return apc_fetch('CachedResource');
	}
	
	//If null, delete all
	public static function flush($toDelete = null){
		if(is_null($toDelete)) {
			$toDelete = new APCIterator('user', '/^CachedResource/', APC_ITER_VALUE);
		}
		return apc_delete($toDelete); 
	}
}

?>
