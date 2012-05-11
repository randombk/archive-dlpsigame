<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

//Cache class for Resource Data
class CachedResource_RESOURCES {
	public static function loadGameResource() {
		$string = file_get_contents(ROOT_PATH . 'engine/data/resources.json');
		$RESOURCES = json_decode($string, TRUE);
		
		apc_store('CachedResource/RESOURCES', $RESOURCES);
		return $RESOURCES;
	}
}

?>
