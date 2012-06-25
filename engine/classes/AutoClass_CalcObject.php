<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class CalcObject
 */
class CalcObject {
	//Object Calculations

	/**
	 * @param PlayerEnvironment $playerEnv,
	 * @param ObjectEnvironment $objectEnv
	 * @param DataMod|null $mod
	 * @return int
	 */
	public static function getMaxEnergyStorage($playerEnv, $objectEnv, $mod = null) {
		if($mod == null) $mod = DataMod::calculateObjectModifiers($playerEnv, $objectEnv);
		return (int)max(0, (10 + $mod->getMod("modEnergyStorageCapacityIncrease")) * (1 + $mod->getMod("modEnergyStorageCapacityMultiplier")/100));
	}

	/**
	 * @param PlayerEnvironment $playerEnv,
	 * @param ObjectEnvironment $objectEnv
	 * @param DataMod|null $mod
	 * @return int
	 */
	public static function getObjectStorage($playerEnv, $objectEnv, $mod = null) {
		if($mod == null) $mod = DataMod::calculateObjectModifiers($playerEnv, $objectEnv);
		return (int)max(1, (1000 * $objectEnv->envObjectData["planetSize"] + $mod->getMod("modStorageCapacityIncrease")) * (1 + $mod->getMod("modStorageCapacityMultiplier")/100));
	}

	/**
	 * @param PlayerEnvironment $playerEnv
	 * @param ObjectEnvironment $objectEnv
	 * @param DataMod $mod
	 * @return array Modifier Array
	 */
	public static function getObjectWeightPenalty($playerEnv, $objectEnv, $mod) {
		$modAmount = (int)min(0, -($objectEnv->envItems->getTotalWeight() - self::getObjectStorage($playerEnv, $objectEnv, $mod)) / (self::getObjectStorage($playerEnv, $objectEnv, $mod) / 1000));
		return array(
			"modResourceProductionMultiplier" => $modAmount,
			"modResourceConsumptionMultiplier" => $modAmount
		);
	}

	/**
	 * @param ObjectEnvironment $objectEnv
	 * @return array Modifier Array
	 */
	public static function getObjectModifiers($objectEnv) {
		$mods = array();

		if($objectEnv->objectType == 1) {
			switch ($objectEnv->envObjectData["planetType"]) {
				case "Temperate": {
					$mods["modConstructionSpeedMultiplier"] = 	(int)((min($objectEnv->envObjectData["planetSize"] - 200, 350 - $objectEnv->envObjectData["planetSize"]) / 5) + 20 - abs(20 - $objectEnv->envObjectData["planetTemp"]));
					break;
				}
				case "Volcanic": {
					$mods["modResourceProductionMultiplier"] = 	(int)($objectEnv->envObjectData["planetSize"] - 380 + $objectEnv->envObjectData["planetTemp"] - 80);
					$mods["modEnergyProductionMultiplier"] = 	(int)($objectEnv->envObjectData["planetTemp"]);
					$mods["modStorageCapacityMultiplier"] = 	(int)(max(-20, $objectEnv->envObjectData["planetSize"] - 380));
					$mods["modBuildSpeedMultiplier"] = 			(int)(- $objectEnv->envObjectData["planetTemp"]);
					break;
				}
				case "Dwarf": {
					$mods["modBuildSpeedMultiplier"] = 			(int)(150 - $objectEnv->envObjectData["planetSize"]);
					$mods["modResourceProductionMultiplier"] = -(int)(150 - $objectEnv->envObjectData["planetSize"]);
					$mods["modStorageCapacityMultiplier"] =    -(int)((150 - $objectEnv->envObjectData["planetSize"]) / 5);
					break;
				}
				case "Icy": {
					$mods["modResourceProductionMultiplier"] = -(int)($objectEnv->envObjectData["randSeed"] / 100);
					$mods["modEnergyConsumptionMultiplier"] = 	(int)($objectEnv->envObjectData["planetTemp"] / 2);
					break;
				}
				case "Desert": {
					$mods["modEnergyProductionMultiplier"] = 	(int)($objectEnv->envObjectData["planetTemp"] / 3);
					$mods["modBuildSpeedMultiplier"] = 			(int)(-20 + $objectEnv->envObjectData["planetHumidity"]);
					break;
				}
				case "Ocean": {
					$mods["modResourceProductionMultiplier"] = 	(int)($objectEnv->envObjectData["planetHumidity"] / 3 + $objectEnv->envObjectData["planetSize"] / 50);
					$mods["modBuildCostMultiplier"] = 			(int)($objectEnv->envObjectData["planetHumidity"] - 50);
					$mods["modResourceConsumptionMultiplier"] = (int)($objectEnv->envObjectData["randSeed"] / 100);
					break;
				}
				case "Oasis": {
					$mods["modConstructionSpeedMultiplier"] = 	(int)((min($objectEnv->envObjectData["planetSize"] - 200, 350 - $objectEnv->envObjectData["planetSize"]) /2) + 20 - abs(20 - $objectEnv->envObjectData["planetTemp"]));
					$mods["modResourceProductionMultiplier"] = 	(int)($objectEnv->envObjectData["randSeed"] / 20);
					$mods["modStorageCapacityMultiplier"] = 	(int)(min($objectEnv->envObjectData["planetSize"] - 200, 350 - $objectEnv->envObjectData["planetSize"]));
					$mods["modEnergyProductionMultiplier"] = 	(int)(2*abs(20 - $objectEnv->envObjectData["planetTemp"]));
					$mods["modBuildingCapacityMultiplier"] = 	(int)(min($objectEnv->envObjectData["planetSize"] - 200, 350 - $objectEnv->envObjectData["planetSize"]) + ($objectEnv->envObjectData["randSeed"]-500) / 100);
					break;
				}
			}
		}
		return $mods;
	}

	//Building Calculations
	/**
	 * @param string $buildingID
	 * @return array Associative array of building definitions
	 */
	public static function getBuildingType($buildingID) {
		return GameCache::get("BUILDINGS")[$buildingID]["buildType"];
	}

	/**
	 * @param ObjectEnvironment $objectEnv
	 * @param string $buildingID
	 * @return int
	 */
	public static function getBuildingMaxLevel($objectEnv, $buildingID) {
		if(isset(GameCache::get("BUILDINGS")[$buildingID]["buildMax"]))
			return GameCache::get("BUILDINGS")[$buildingID]["buildMax"];
		else
			return -1;
	}

	/**
	 * @param ObjectEnvironment $objectEnv
	 * @param string $buildingID
	 * @param int $level
	 * @param int $activity
	 * @return array Modifier Array
	 */
	public static function getBuildingModifiers($objectEnv, $buildingID, $level, $activity = 100) {
		if(!isset(GameCache::get("BUILDINGS")[$buildingID]["modifiers"])) return null;

		$targetLevel = min(sizeof(GameCache::get("BUILDINGS")[$buildingID]["modifiers"]), $level);

		if(!$targetLevel) {
			return null;
		} else {
			$mods = GameCache::get("BUILDINGS")[$buildingID]["modifiers"][$targetLevel - 1];

			foreach($mods as $mod => $value) {
				$mods[$mod] = $value * ($activity / 100);
			}

			return $mods;
		}
	}

	/**
	 * @param PlayerEnvironment $playerEnv
	 * @param ObjectEnvironment $objectEnv
	 * @param string $buildingID
	 * @param int $level
	 * @param DataMod $mod
	 * @return $this|DataItem
	 */
	public static function getBuildingUpgradeCost($playerEnv, $objectEnv, $buildingID, $level, $mod = null) {
		$maxBalLevel = sizeof(GameCache::get("BUILDINGS")[$buildingID]["resReq"]);

		if($mod == null) $mod = DataMod::calculateObjectModifiers($playerEnv, $objectEnv);

		$retObject = null;
		if($level > $maxBalLevel) {
			$retObject = DataItem::fromItemArray(GameCache::get("BUILDINGS")[$buildingID]["resReq"][$maxBalLevel - 1])->multiply(pow(2, $level - $maxBalLevel));
		} else {
			$retObject = DataItem::fromItemArray(GameCache::get("BUILDINGS")[$buildingID]["resReq"][$level - 1]);
		}

		$retObject->multiply(1 + $mod->getMod("modBuildCostMultiplier")/100);

		return $retObject;
	}

	/**
	 * @param PlayerEnvironment $playerEnv
	 * @param ObjectEnvironment $objectEnv
	 * @param string $buildingID
	 * @param int $level
	 * @param DataMod $mod
	 * @return int
	 * @throws Exception
	 */
	public static function getBuildTime($playerEnv, $objectEnv, $buildingID, $level, $mod = null) {
		$maxBalLevel = sizeof(GameCache::get("BUILDINGS")[$buildingID]["buildDifficulty"]);

		if($maxBalLevel <= 0 || $level <= 0) {
			throw new Exception("Invalid Parameters!");
		}

		if($mod == null) $mod = DataMod::calculateObjectModifiers($playerEnv, $objectEnv);

		$retObject = null;
		if($level > $maxBalLevel) {
			$retObject = GameCache::get("BUILDINGS")[$buildingID]["buildDifficulty"][$maxBalLevel - 1] * pow(2, $level - $maxBalLevel);
		} else {
			$retObject = GameCache::get("BUILDINGS")[$buildingID]["buildDifficulty"][$level - 1];
		}

		$speed = 1 + $mod->getMod("modConstructionSpeedMultiplier") / 100 + $mod->getMod("modBuildSpeedMultiplier") / 100;
		$time = ($retObject / max(0.00001, $speed)) - $mod->getMod("modConstructionTimeDecrease") - $mod->getMod("modBuildTimeDecrease");
		return max(1, $time);
	}

	/**
	 * @param PlayerEnvironment $playerEnv
	 * @param ObjectEnvironment $objectEnv
	 * @param string $buildingID
	 * @param int $level
	 * @param DataMod $mod
	 * @param int $activity
	 * @return $this|DataItem
	 */
	public static function getBuildingProduction($playerEnv, $objectEnv, $buildingID, $level, $mod = null, $activity = 100) {
		if(!isset(GameCache::get("BUILDINGS")[$buildingID]["resProduction"]))
			return new DataItem();
		$maxBalLevel = sizeof(GameCache::get("BUILDINGS")[$buildingID]["resProduction"]);

		if($maxBalLevel <= 0 || $level <= 0) {
			return new DataItem();
		}

		if($mod == null)
			$mod = DataMod::calculateObjectModifiers($playerEnv, $objectEnv);

		$retObject = null;
		if($level > $maxBalLevel) {
			$retObject = DataItem::fromItemArray(GameCache::get("BUILDINGS")[$buildingID]["resProduction"][$maxBalLevel - 1])->multiply(pow(2, $level - $maxBalLevel));
		} else {
			$retObject = DataItem::fromItemArray(GameCache::get("BUILDINGS")[$buildingID]["resProduction"][$level - 1]);
		}

		foreach ($retObject->getDataArray() as $res => $amount) {
			$retObject->setItem($res, $amount * (max(0, 1 + $mod->getMod("mod". GameCache::get("ITEMS")[$res]["itemType"] ."ProductionMultiplier")/100)));
		}

		return $retObject->multiply($activity / 100);
	}

	/**
	 * @param PlayerEnvironment $playerEnv
	 * @param ObjectEnvironment $objectEnv
	 * @param string $buildingID
	 * @param int $level
	 * @param DataMod $mod
	 * @param int $activity
	 * @return $this|DataItem
	 */
	public static function getBuildingConsumption($playerEnv, $objectEnv, $buildingID, $level, $mod = null, $activity = 100) {
		if(!isset(GameCache::get("BUILDINGS")[$buildingID]["resConsumption"]))
			return new DataItem();
		$maxBalLevel = sizeof(GameCache::get("BUILDINGS")[$buildingID]["resConsumption"]);

		if($maxBalLevel <= 0 || $level <= 0) {
			return new DataItem();
		}

		if($mod == null)
			$mod = DataMod::calculateObjectModifiers($playerEnv, $objectEnv);

		$retObject = null;
		if($level > $maxBalLevel) {
			$retObject = DataItem::fromItemArray(GameCache::get("BUILDINGS")[$buildingID]["resConsumption"][$maxBalLevel - 1])->multiply(pow(2, $level - $maxBalLevel));
		} else {
			$retObject = DataItem::fromItemArray(GameCache::get("BUILDINGS")[$buildingID]["resConsumption"][$level - 1]);
		}

		foreach ($retObject->getDataArray() as $res => $amount) {
			$retObject->setItem($res, $amount * (max(0, 1 + $mod->getMod("mod". GameCache::get("ITEMS")[$res]["itemType"] ."ConsumptionMultiplier")/100)));
		}

		return $retObject->multiply($activity / 100);
	}
}
