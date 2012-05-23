<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

//Cache class for Star Data
/**
 * Class CachedResource_STARS
 */
class CachedResource_STARS {
	/**
	 * @param bool $returnPos
	 * @return array
	 */
	public static function loadGameResource($returnPos = false) {
		$tableData = DBMySQL::select(tblUNI_STARS);
		
		$STARS = array();
		$STARPOS = array();
		
		foreach ($tableData as $row) {
			$STARS[$row['starID']] = $row;
			$STARPOS[''.$row['galaxyID'].'.'.$row['sectorID'].'.'.$row['starIndex']] = $row;
		}
		
		apc_store('CachedResource/STARS', $STARS);
		apc_store('CachedResource/STARPOS', $STARPOS);
		if(!$returnPos) {
			return $STARS;
		} else {
			return $STARPOS;
		}
	}
}

?>
