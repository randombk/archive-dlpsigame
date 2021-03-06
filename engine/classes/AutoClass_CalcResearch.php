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
	 * @param PlayerEnvironment $playerEnv
	 * @param string $techID
	 * @return bool
	 */
	public static function canResearch($playerEnv, $techID) {
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
	 * @param PlayerEnvironment $playerEnv
	 * @param string $techID
	 * @param int $level
	 * @return array Modifier Array
	 */
	public static function getResearchModifiers($playerEnv, $techID, $level) {
		if($level < 1 || !isset(GameCache::get("RESEARCH")[$techID]["techMods"])) return null;

		$mods = array();

		foreach (GameCache::get("RESEARCH")[$techID]["techMods"] as $modID => $modData) {
			if($level >= $modData[0]) {
				$mods[$modID] = $modData[1]*pow($level-$modData[0], $modData[2]) + $modData[3];
			}
		}
		return $mods;
	}

	/**
	 * @param PlayerEnvironment $playerEnv
	 * @param ObjectEnvironment $objectEnv
	 * @param string $techID
	 * @param DataMod $mod
	 * @return $this|DataItem
	 */
	public static function getResearchNoteConsumption($playerEnv, $objectEnv, $techID, $mod = null) {
		if($mod == null) $mod = DataMod::calculateObjectModifiers($playerEnv, $objectEnv);

		$retObject = DataItem::fromItemArray(GameCache::get("RESEARCH")[$techID]["researchNoteConsumption"]);
		//$retObject->multiply(1 + $mod->getMod("modResearchNoteCostMultiplier")/100);

		return $retObject;
	}

	/**
	 * @param PlayerEnvironment $playerEnv
	 * @param ObjectEnvironment $objectEnv
	 * @param string $techID
	 * @return $this|array
	 */
	public static function getResearchNotePassive($playerEnv, $objectEnv, $techID) {
		$retObject = GameCache::get("RESEARCH")[$techID]["researchNotePassive"];
		//$retObject->multiply(1 + $mod->getMod("modResearchNoteCostMultiplier")/100);

		return $retObject;
	}

	/**
	 * @param PlayerEnvironment $playerEnv
	 * @param ObjectEnvironment $objectEnv
	 * @param string $techID
	 * @param DataMod $mod
	 * @return $this|DataItem
	 */
	public static function getResearchNoteCost($playerEnv, $objectEnv, $techID, $mod = null) {
		if($mod == null) $mod = DataMod::calculateObjectModifiers($playerEnv, $objectEnv);

		$retObject = DataItem::fromItemArray(GameCache::get("RESEARCH")[$techID]["researchNoteCost"]);
		//$retObject->multiply(1 + $mod->getMod("modResearchNoteCostMultiplier")/100);

		return $retObject;
	}

	/**
	 * @param PlayerEnvironment $playerEnv
	 * @param string $techID
	 * @param int $level
	 * @return int|null
	 */
	public static function getReqResearchPoints($playerEnv, $techID, $level) {
		$costData = GameCache::get("RESEARCH")[$techID]["researchCost"];
		$baseCost = $costData[0]*pow($level, $costData[1]) + $costData[2];

		//Get discount factor
		$sides = self::getNeighborIDs($techID);
		$numSides = sizeof($sides);
		$totalLevel = 0;
		foreach($sides as $sideID) {
			$totalLevel += $playerEnv->envResearch->getResearchLevel($sideID);
		}
		$discountFactor = (1/(($totalLevel / $numSides) / $level));
		$discountFactor -= ($discountFactor-1)/1.5;

		return max(ceil($baseCost*$discountFactor), 1);
	}

	/**
	 * Research time of Research Note
	 * Calculated as a factor of the distance from the center of the research map
	 *
	 * @param PlayerEnvironment $playerEnv
	 * @param ObjectEnvironment $objectEnv
	 * @param string $techID
	 * @param DataMod $mod
	 * @return int time
	 * @throws Exception
	 */
	public static function getResearchTime($playerEnv, $objectEnv, $techID, $mod = null) {
		$distance = GameCache::get("RESEARCH")[$techID]["distance"];
		return pow($distance, 1.3) + 60;
	}

	/**
	 * @param PlayerEnvironment $playerEnv
	 * @param string $techID
	 */
	public static function propagateResearchUpdate($playerEnv, $techID) {
		$curLevel = $playerEnv->envResearch->getResearchLevel($techID);
		$curNotes = $playerEnv->envResearch->getResearchPoints($techID);
		$numRequired = CalcResearch::getReqResearchPoints($playerEnv, $techID, $curLevel + 1);
		$needsPropagation = false;
		while($numRequired <= $curNotes) {
			$needsPropagation = true;
			$curNotes -= $numRequired;
			$curLevel += 1;
			$numRequired = CalcResearch::getReqResearchPoints($playerEnv, $techID, $curLevel + 1);
		}

		if($needsPropagation) {
			$playerEnv->envResearch->setResearchLevel($techID, $curLevel);
			$playerEnv->envResearch->setResearchPoints($techID, $curNotes);
			$sides = self::getNeighborIDs($techID);
			foreach($sides as $sideID) {
				self::propagateResearchUpdate($playerEnv, $sideID);
			}
		}
	}
}
