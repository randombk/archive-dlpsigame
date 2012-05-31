<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

//Cache class for Item Parameter Data
/**
 * Class CachedResource_ITEMPARAMSPUBLIC
 */
class CachedResource_ITEMPARAMSPUBLIC {
	/**
	 * @return array
	 */
	public static function loadGameResource() {
		$ITEMPARAMSPUBLIC = DBMongo::getPublicCachableItemParams();

		apc_store('CachedResource/ITEMPARAMSPUBLIC', $ITEMPARAMSPUBLIC);
		return $ITEMPARAMSPUBLIC;
	}
}
