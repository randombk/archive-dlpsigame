<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

//Cache class for Item Data
class CachedResource_ITEMS {
	public static function loadGameResource() {
		$string = file_get_contents(ROOT_PATH . 'engine/data/items.json');
		$ITEMS = json_decode($string, TRUE);
		
		apc_store('CachedResource/ITEMS', $ITEMS);
		return $ITEMS;
	}
}

?>