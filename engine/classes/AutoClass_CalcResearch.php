<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

class CalcResearch {
	public static $directions = array(
		array(1, 0),
		array(0, 1),
		array(-1, 1),
		array(-1, 0),
		array(0, -1),
		array(1, -1)
	);
	
	public static function getResearchPosString($q, $r) {
		return $q . ":" . $r;
	}
	
	public static function getResearchAtPosition($q, $r) {
		$positionID = self::getResearchPosString($q, $r);
		if(isset(GameCache::get("RESEARCHPOS")[$positionID])) {
			return GameCache::get("RESEARCHPOS")[$positionID]["techID"];
		} else {
			return null;
		}
	}
	
	public static function getOffsetID($techID, $offset) {
		if(isset(GameCache::get("RESEARCH")[$techID]) && isset($offset[0]) && isset($offset[1])) {
			return self::getResearchAtPosition(
				GameCache::get("RESEARCH")[$techID]["q"] + $offset[0],
				GameCache::get("RESEARCH")[$techID]["r"] + $offset[1]
			);
		} else {
			return null;
		}
	}
	
	public static function getNeighborIDs($techID) {
		$ids = array();
		foreach(self::$directions as $index => $direction) {
			$id = self::getOffsetID($direction);
			if($id) {
				array_push($ids, $id);
			}
		}
		return $ids;
	}
	
	public static function canResearch($techID, $playerEnv) {
		if($playerEnv->envResearch->getResearchLevel($techID) || $playerEnv->envResearch->getResearchPoints($techID)) {
			return true;	
		}
		
		$neighborIDs = self::getNeighborIDs($techID);
		foreach($neighborIDs as $index => $id) {
			if($playerEnv->envResearch->getResearchLevel($id)) {
				return true;
			}
		}
		
		return false;	
	}
}

?>
