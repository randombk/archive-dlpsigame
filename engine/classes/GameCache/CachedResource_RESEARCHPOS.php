<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */
require_once(ROOT_PATH . 'engine/classes/GameCache/CachedResource_RESEARCH.php');

//Cache class for Star Data
class CachedResource_RESEARCHPOS {
	public static function loadGameResource() {
		return CachedResource_RESEARCH::loadGameResource(true);
	}
}

?>
