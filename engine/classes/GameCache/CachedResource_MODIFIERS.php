<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

//Cache class for Modifier Data
/**
 * Class CachedResource_MODIFIERS
 */
class CachedResource_MODIFIERS {
	/**
	 * @return mixed
	 */
	public static function loadGameResource() {
		$string = file_get_contents(ROOT_PATH . 'engine/data/modifiers.json');
		$MODIFIERS = json_decode($string, TRUE);
		
		apc_store('CachedResource/MODIFIERS', $MODIFIERS);
		return $MODIFIERS;
	}
}

?>
