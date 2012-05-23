<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */
require_once(ROOT_PATH . 'engine/classes/GameCache/CachedResource_STARS.php');

//Cache class for Star Data
/**
 * Class CachedResource_STARPOS
 */
class CachedResource_STARPOS {
	/**
	 * @return array
	 */
	public static function loadGameResource() {
		return CachedResource_STARS::loadGameResource(true);
	}
}