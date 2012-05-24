<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

//Cache class for Research Data
/**
 * Class CachedResource_RESEARCH
 */
class CachedResource_RESEARCH {
	/**
	 * @param bool $returnPos
	 * @return array
	 */
	public static function loadGameResource($returnPos = false) {
		$string = file_get_contents(ROOT_PATH . 'engine/data/research.json');
		$RESEARCH = json_decode($string, TRUE);
		$RESEARCHPOS = array();
		
		foreach($RESEARCH as $data) {
			$RESEARCHPOS[$data["q"] . ":" . $data["r"]] = $data;
			DBMongo::setItemParams("research-notes_" . $data["techID"],
				array (
				    'cached' => true,
				    'formatNameParams' => 
					    array (
					      0 => $data["techName"],
					    ),
				    'formatDescParams' => 
					    array (
					      0 => $data["techName"],
					    ),
  				)
			);
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