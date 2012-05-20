<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

class CalcObject {
	//Object Calculations
	public static function calcNewObjectRes($objectEnv, $timeDelta, $mod = null) {
		if($mod == null) $mod = DataMod::calculateObjectModifiers($objectEnv);
		
		$endRes = $objectEnv->envItems;
		
		$buildProduction = array();
		
		//Add total production as if everything is fine
		foreach ($objectEnv->envBuildings->getBuildingArray() as $building => $data) {
			$buildProduction[$building] = self::getBuildingProduction($objectEnv, $building, $data[0], $mod, $data[1])->multiply($timeDelta / (3600)); 
			$endRes->sum($buildProduction[$building]);
		}
		
		//Then deduct resources
		foreach ($objectEnv->envBuildings->getBuildingArray() as $building => $data) {
			$resCost = self::getBuildingConsumption($objectEnv, $building, $data[0], $mod, $data[1])->multiply($timeDelta / (3600));
			
			//Find limiting factor
			$factor = 1;
			
			//Find missing resources
			foreach ($resCost->getItemArray() as $resource => $amount) {
				$factor = max(0, min($factor, $endRes->getItem($resource) / $amount));
			}
						
			$endRes->sub($resCost->multiply($factor));
			
			if($factor < 1) {
				$endRes->sub($buildProduction[$building]->multiply(1 - $factor));
			}
		}
		
		//Special: max energy
		$maxEnergy = self::getMaxEnergyStorage($objectEnv, $mod);
		if(($endRes->getItem("energy")) >= $maxEnergy) {
			$endRes->setItem("energy", $maxEnergy);
		}
		
		return $endRes;
	}
	
	public static function getMaxEnergyStorage($objectEnv, $mod = null) {
		if($mod == null) $mod = DataMod::calculateObjectModifiers($objectEnv);
		return (int)max(0, (10 + $mod->getMod("modEnergyStorageCapacityIncrease")) * (1 + $mod->getMod("modEnergyStorageCapacityMultiplier")/100));
	}
	
	public static function getObjectStorage($objectEnv, $mod = null) {
		if($mod == null) $mod = DataMod::calculateObjectModifiers($objectEnv);
		return (int)max(1, (1000 * $objectEnv->envObjectData["planetSize"] + $mod->getMod("modStorageCapacityIncrease")) * (1 + $mod->getMod("modStorageCapacityMultiplier")/100));
	}
	
	public static function getObjectWeightPenalty($objectEnv, $mod) {
		$modAmount = (int)min(0, -($objectEnv->envItems->getTotalWeight() - self::getObjectStorage($objectEnv, $mod)) / (self::getObjectStorage($objectEnv, $mod) / 1000));
		return array(
			"modResourceProductionMultiplier" => $modAmount,
			"modResourceConsumptionMultiplier" => $modAmount
		);
	}
	
	//Get list of research points
	public static function getObjectResearchPoints($objectEnv, $mod = null) {
		if($mod == null) $mod = DataMod::calculateObjectModifiers($objectEnv);
		
		$points = array(
			"Weapons" 	=> 0,
			"Defense" 	=> 0,
			"Diplomatic"=> 0,
			"Economic" 	=> 0,
			"Fleet" 	=> 0
		);
		
		//Add up active building modifiers
		foreach ($objectEnv->envBuildings->getBuildingArray() as $buildingID => $data) {
			foreach(CalcObject::getBuildingResearch($objectEnv, $buildingID, $data[0], $mod, $data[1]) as $type => $value) {
				$points[$type] += $value;
			}
		}
		
		return $points;
	}
	
	public static function getObjectResearch($objectEnv, $researchID, $mod = null) {
		if(!isset(GameCache::get("RESEARCH")[$researchID]["type"])) return 0;
		if($mod == null) $mod = DataMod::calculateObjectModifiers($objectEnv);
		
		$type = GameCache::get("RESEARCH")[$researchID]["type"];
		$points = self::getObjectResearchPoints($objectEnv, $mod);
		return $points["base"] + $points[$type];
	}
	
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
	public static function getBuildingType($buildingID) {
		return GameCache::get("BUILDINGS")[$buildingID]["buildType"];
	}
	
	public static function getBuildingMaxLevel($objectEnv, $buildingID) {
		if(isset(GameCache::get("BUILDINGS")[$buildingID]["buildMax"]))
			return GameCache::get("BUILDINGS")[$buildingID]["buildMax"];
		else 
			return -1;
	}
	
	public static function getBuildingModifiers($objectEnv, $buildingID, $level, $activity = 100) {
		if(!isset(GameCache::get("BUILDINGS")[$buildingID]["modifiers"])) return null;
		$numEntries = sizeof(GameCache::get("BUILDINGS")[$buildingID]["modifiers"]);
		
		if(!$numEntries) {
			return null;
		} else {
			$mods = GameCache::get("BUILDINGS")[$buildingID]["modifiers"][min($level -  1, $numEntries - 1)];
			
			foreach($mods as $mod => $value) {
				$mods[$mod] = $value * ($activity / 100);
			}
			
			return $mods;
		}
	}
	
	public static function getBuildingUpgradeCost($objectEnv, $buildingID, $level, $mod = null) {
		$maxBalLevel = sizeof(GameCache::get("BUILDINGS")[$buildingID]["resReq"]);
		
		if($mod == null) $mod = DataMod::calculateObjectModifiers($objectEnv);
		
		$retObject = null;
		if($level > $maxBalLevel) {
			$retObject = DataItem::fromItemArray(GameCache::get("BUILDINGS")[$buildingID]["resReq"][$maxBalLevel - 1])->multiply(pow(2, $level - $maxBalLevel));
		} else {
			$retObject = DataItem::fromItemArray(GameCache::get("BUILDINGS")[$buildingID]["resReq"][$level - 1]);
		}
		
		$retObject->multiply(1 + $mod->getMod("modBuildCostMultiplier")/100);
		
		return $retObject;
	}
	
	public static function getBuildTime($objectEnv, $buildingID, $level, $mod = null) {
		$maxBalLevel = sizeof(GameCache::get("BUILDINGS")[$buildingID]["buildDifficulty"]);
		
		if($maxBalLevel <= 0 || $level <= 0) {
			throw new Exception("Invalid Parameters!");
		}
		
		if($mod == null) $mod = DataMod::calculateObjectModifiers($objectEnv);
		
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
	
	public static function getBuildingProduction($objectEnv, $buildingID, $level, $mod = null, $activity = 100) {
		if(!isset(GameCache::get("BUILDINGS")[$buildingID]["resProduction"])) 
			return new DataItem();
		$maxBalLevel = sizeof(GameCache::get("BUILDINGS")[$buildingID]["resProduction"]);

		if($maxBalLevel <= 0 || $level <= 0) {
			return new DataItem();
		}
		
		if($mod == null) 
			$mod = DataMod::calculateObjectModifiers($objectEnv);
		
		$retObject = null;
		if($level > $maxBalLevel) {
			$retObject = DataItem::fromItemArray(GameCache::get("BUILDINGS")[$buildingID]["resProduction"][$maxBalLevel - 1])->multiply(pow(2, $level - $maxBalLevel));
		} else {
			$retObject = DataItem::fromItemArray(GameCache::get("BUILDINGS")[$buildingID]["resProduction"][$level - 1]);
		}
		
		foreach ($retObject->getItemArray() as $res => $amount) {
			$retObject->setItem($res, $amount * (max(0, 1 + $mod->getMod("mod". GameCache::get("ITEMS")[$res]["itemType"] ."ProductionMultiplier")/100)));
		}
		
		return $retObject->multiply($activity / 100);
	}
	
	public static function getBuildingConsumption($objectEnv, $buildingID, $level, $mod = null, $activity = 100) {
		if(!isset(GameCache::get("BUILDINGS")[$buildingID]["resConsumption"]))
			return new DataItem();
		$maxBalLevel = sizeof(GameCache::get("BUILDINGS")[$buildingID]["resConsumption"]);
		
		if($maxBalLevel <= 0 || $level <= 0) {
			return new DataItem();
		}
		
		if($mod == null) 
			$mod = DataMod::calculateObjectModifiers($objectEnv);
		
		$retObject = null;
		if($level > $maxBalLevel) {
			$retObject = DataItem::fromItemArray(GameCache::get("BUILDINGS")[$buildingID]["resConsumption"][$maxBalLevel - 1])->multiply(pow(2, $level - $maxBalLevel));
		} else {
			$retObject = DataItem::fromItemArray(GameCache::get("BUILDINGS")[$buildingID]["resConsumption"][$level - 1]);
		}
		
		foreach ($retObject->getItemArray() as $res => $amount) {
			$retObject->setItem($res, $amount * (max(0, 1 + $mod->getMod("mod". GameCache::get("ITEMS")[$res]["itemType"] ."ConsumptionMultiplier")/100)));
		}
		
		return $retObject->multiply($activity / 100);
	}
	
	public static function getBuildingResearch($objectEnv, $buildingID, $level, $mod = null, $activity = 100) {
		if(!isset(GameCache::get("BUILDINGS")[$buildingID]["researchPoints"])) 
			return array();
		$numEntries = sizeof(GameCache::get("BUILDINGS")[$buildingID]["researchPoints"]);
		
		if(!$numEntries) {
			return array();
		} else {
			$points = GameCache::get("BUILDINGS")[$buildingID]["researchPoints"][min($level -  1, $numEntries - 1)];
			foreach($points as $type => $value) {
				$points[$type] = $value * ($activity / 100);
			}
			return $points;
		}
	}
}

?>
