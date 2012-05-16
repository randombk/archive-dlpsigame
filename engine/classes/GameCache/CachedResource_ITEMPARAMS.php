<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

//Cache class for Item Parameter Data
class CachedResource_ITEMPARAMS {
	public static function loadGameResource() {
		$ITEMPARAMS = $GLOBALS["MONGO"]->getCachableItemParams();
		
		apc_store('CachedResource/ITEMPARAMS', $ITEMPARAMS);
		return $ITEMPARAMS;
	}
}

?>
