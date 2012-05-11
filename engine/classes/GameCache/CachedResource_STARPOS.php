<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */
require_once(ROOT_PATH . 'engine/classes/GameCache/CachedResource_STARS.php');

//Cache class for Star Data
class CachedResource_STARPOS {
	public static function loadGameResource() {
		return CachedResource_STARS::loadGameResource(true);
	}
}

?>
