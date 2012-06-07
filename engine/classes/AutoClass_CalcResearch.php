<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class CalcResearch
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

	/**
	 * @param int $q
	 * @param int $r
	 * @return string
	 */
	public static function getResearchPosString($q, $r) {
		return $q . ":" . $r;
	}

	/**
	 * @param int $q
	 * @param int $r
	 * @return null|array
	 */
	public static function getResearchAtPosition($q, $r) {
		$positionID = self::getResearchPosString($q, $r);
		if(isset(GameCache::get("RESEARCHPOS")[$positionID])) {
			return GameCache::get("RESEARCHPOS")[$positionID]["techID"];
		} else {
			return null;
		}
	}

	/**
	 * @param string $techID
	 * @param array $offset
	 * @return null|array
	 */
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

	/**
	 * @param string $techID
	 * @return string[]
	 */
	public static function getNeighborIDs($techID) {
		$ids = array();
		foreach(self::$directions as $direction) {
			$id = self::getOffsetID($techID, $direction);
			if($id) {
				array_push($ids, $id);
			}
		}
		return $ids;
	}

	/**
	 * @param string $techID
	 * @param PlayerEnvironment $playerEnv
	 * @return bool
	 */
	public static function canResearch($techID, $playerEnv) {
		if($playerEnv->envResearch->getResearchLevel($techID) || $playerEnv->envResearch->getResearchPoints($techID)) {
			return true;
		}

		$neighborIDs = self::getNeighborIDs($techID);
		foreach($neighborIDs as $id) {
			if($playerEnv->envResearch->getResearchLevel($id)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * IN DEVELOPMENT
	 *
	 * @param string $techID
	 * @param PlayerEnvironment $playerEnv
	 * @return null
	 */
	public static function getReqResearchPoints($techID, $playerEnv) {
		if(self::canResearch($techID, $playerEnv)) {
			//$baseCost = GameCache::get("RESEARCH")[$techID][""]
			return 200;

		} else {
			return null;
		}
	}

	/**
	 * @param PlayerEnvironment $playerEnv
	 * @param ObjectEnvironment $objectEnv
	 * @param string $techID
	 * @param int $level
	 * @param DataMod $mod
	 * @return int
	 * @throws Exception
	 */
	public static function getResearchTime($playerEnv, $objectEnv, $techID, $level, $mod = null) {
		return max(1, 200);
	}
}
