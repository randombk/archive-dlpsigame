<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

//Cache class for Research Data
class CachedResource_RESEARCH {
	public static function loadGameResource($returnPos = false) {
		$string = file_get_contents(ROOT_PATH . 'engine/data/research.json');
		$RESEARCH = json_decode($string, TRUE);
		$RESEARCHPOS = array();
		
		foreach($RESEARCH as $researchID => $data) {
			$RESEARCHPOS[$data["q"] . ":" . $data["r"]] = $data;
		}
		
		apc_store('CachedResource/RESEARCH', $RESEARCH);
		apc_store('CachedResource/RESEARCHPOS', $RESEARCHPOS);
		
		if(!$returnPos) {
			return $RESEARCH;
		} else {
			return $RESEARCHPOS;
		}
	}
}

?>
