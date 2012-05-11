<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

//Cache class for Building Data
class CachedResource_BUILDINGS {
	public static function loadGameResource() {
		$string = file_get_contents(ROOT_PATH . 'engine/data/buildings.json');
		$BUILDINGS = json_decode($string, TRUE);
		
		apc_store('CachedResource/BUILDINGS', $BUILDINGS);
		return $BUILDINGS;
	}
}

?>
