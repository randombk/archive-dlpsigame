<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

//Cache class for Item Parameter Data
/**
 * Class CachedResource_ITEMPARAMS
 */
class CachedResource_ITEMPARAMS {
	public static function loadGameResource() {
		$ITEMPARAMS = DBMongo::getCachableItemParams();
		
		apc_store('CachedResource/ITEMPARAMS', $ITEMPARAMS);
		return $ITEMPARAMS;
	}
}

?>
