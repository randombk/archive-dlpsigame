<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

class QueueBuilding {
	public static function hasPreReq($objectEnv, $buildingID, $buildingLevel, $time = TIMESTAMP, $forQueue = false) {
		if($buildingID == "buildResearchLab" && $objectEnv->envPlayer->envPlayerData->getValue("flagResearchCenterPlanet")) {
			if(!$forQueue) 
				Message::sendNotification(
					$objectEnv->ownerID, 
					"Construction Failed on " . $objectEnv->objectName . " (Missing Pre-Requisite)", 
					"A research center is already built. Only one research center may be built at a time",
					"ERROR", 
					"constructError.jpg", 
					"game.php?page=buildings&objectID=" . $objectEnv->objectID,
					$time
				);
			return false;
		}
		
		if(!$forQueue) {
			if($objectEnv->envBuildings->getBuildingLevel($buildingID) != $buildingLevel - 1) {
				Message::sendNotification(
					$objectEnv->ownerID, 
					"Construction Failed on " . $objectEnv->objectName . " (Missing Pre-Requisite)", 
					"Missing pre-requisite for Level $buildingLevel " . GameCache::get("BUILDINGS")[$buildingID]["buildName"],
					"ERROR", 
					"constructError.jpg", 
					"game.php?page=buildings&objectID=" . $objectEnv->objectID,
					$time
				);
				return false;
			}
		}
		
		$maxBuildLevel = ObjectCalc::getBuildingMaxLevel($objectEnv, $buildingID);
		if($maxBuildLevel > 0 && $buildingLevel > $maxBuildLevel) {
			if(!$forQueue) 
				Message::sendNotification(
					$objectEnv->ownerID, 
					"Construction Failed on " . $objectEnv->objectName . " (Max Level Reached)", 
					"Max level reached for building " . GameCache::get("BUILDINGS")[$buildingID]["buildName"],
					"ERROR", 
					"constructError.jpg", 
					"game.php?page=buildings&objectID=" . $objectEnv->objectID,
					$time
				);
			return false;
		}
		
		foreach (GameCache::get("BUILDINGS")[$buildingID]["buildingReq"] as $id => $level) {
			if($objectEnv->envBuildings->getBuildingLevel($id) < $level) {
				if(!$forQueue) 
					Message::sendNotification(
						$objectEnv->ownerID, 
						"Construction Failed on " . $objectEnv->objectName . " (Missing Pre-Requisite)", 
						"Missing pre-requisite for Level $buildingLevel " . GameCache::get("BUILDINGS")[$buildingID]["buildName"],
						"ERROR", 
						"constructError.jpg", 
						"game.php?page=buildings&objectID=" . $objectEnv->objectID,
						$time
					);
				return false;
			}
		}
		
		return true;
	}
	
	public static function canBuild($objectEnv, $buildingID, $buildingLevel, $time = TIMESTAMP) {
		if(!self::hasPreReq($objectEnv, $buildingID, $buildingLevel, $time)) {
			return false;
		}
		
		if(!$objectEnv->envItems->contains(ObjectCalc::getBuildingUpgradeCost($objectEnv, $buildingID, $buildingLevel))) {
			Message::sendNotification(
				$objectEnv->ownerID, 
				"Construction Failed on " . $objectEnv->objectName . " (Missing Resources)", 
				"Not enough resources to build Level $buildingLevel " . GameCache::get("BUILDINGS")[$buildingID]["buildName"],
				"ERROR", 
				"constructError.jpg", 
				"game.php?page=buildings&objectID=" . $objectEnv->objectID,
				$time
			);
			return false;
		}
		return true;
	} 
	
	public static function processBuildingQueue($objectEnv, $time) {
		if(isset($objectEnv->buildingQueue[0])) {
			$action = array_shift($objectEnv->buildingQueue);
			if($action["operation"] == "Build") {
				//Actions are validated upon insertion into the queue
				$objectEnv->envBuildings->setBuildingLevel($action["buildingID"], $action["buildingLevel"]);
				if($action["buildingID"] == "buildResearchLab") {
					$objectEnv->envPlayer->envPlayerData->setValue("flagResearchCenterPlanet", $objectEnv->objectID);
				}
			} else if($action["operation"] == "Recycle") {
				$objectEnv->envBuildings->setBuildingLevel($action["buildingID"], $action["buildingLevel"] - 1);
				$objectEnv->envItems->sum(ObjectCalc::getBuildingUpgradeCost($objectEnv, $action["buildingID"], $action["buildingLevel"])->multiply(0.5));
				if($action["buildingID"] == "buildResearchLab") {
					$objectEnv->envPlayer->envPlayerData->setValue("flagResearchCenterPlanet", false);
					$objectEnv->envPlayer->envResearch->resetUnsavedPoints();
				}
			} else if($action["operation"] == "Destroy") {
				$objectEnv->envBuildings->setBuildingLevel($action["buildingID"], $action["buildingLevel"] - 1);
				if($action["buildingID"] == "buildResearchLab") {
					$objectEnv->envPlayer->envPlayerData->setValue("flagResearchCenterPlanet", false);
					$objectEnv->envPlayer->envResearch->resetUnsavedPoints();
				}
			} 
		}
		
		return self::moveAllUp($objectEnv, $time);
	}

	private static function moveAllUp($objectEnv, $time) {
		while(sizeof($objectEnv->buildingQueue)) {
			if(isset($objectEnv->buildingQueue[0]["endTime"])) {
				//Current action not done
				return true;
			}
			$action = $objectEnv->buildingQueue[0];
			if($action["operation"] == "Build") {
				if(self::canBuild($objectEnv, $action["buildingID"], $action["buildingLevel"], $time)) {
					$objectEnv->buildingQueue[0]["startTime"] = $time;
					$objectEnv->buildingQueue[0]["endTime"] = $time + ObjectCalc::getBuildTime($objectEnv, $action["buildingID"], $action["buildingLevel"]);
					$objectEnv->envItems->sub(ObjectCalc::getBuildingUpgradeCost($objectEnv, $action["buildingID"], $action["buildingLevel"]));
					if($action["buildingID"] == "buildResearchLab") {
						$objectEnv->envPlayer->envPlayerData->setValue("flagResearchCenterPlanet", "queue");
					}
					return true;
				} else {
					array_shift($objectEnv->buildingQueue);
				}
			} else if($action["operation"] == "Destroy") {
				if($action["buildingLevel"] > 0 && $objectEnv->envBuildings->getBuildingLevel($action["buildingID"]) == $action["buildingLevel"]) {
					$objectEnv->buildingQueue[0]["startTime"] = $time;
					$objectEnv->buildingQueue[0]["endTime"] = $time + ObjectCalc::getBuildTime($objectEnv, $action["buildingID"], $action["buildingLevel"]) / 10;
					return true;
				} else {
					array_shift($objectEnv->buildingQueue);
					Message::sendNotification(
						$objectEnv->ownerID, 
						"Construction Failed on " . $objectEnv->objectName, 
						"Failed to process building queue item: Destroy Level " . $action["buildingLevel"] . " " . GameCache::get("BUILDINGS")[$action["buildingID"]]["buildName"],
						"ERROR", 
						"constructError.jpg", 
						"game.php?page=buildings&objectID=" . $objectEnv->objectID,
						$time
					);
				}
			} else if($action["operation"] == "Recycle") {
				if($action["buildingLevel"] > 0 && $objectEnv->envBuildings->getBuildingLevel($action["buildingID"]) == $action["buildingLevel"]) {
					$objectEnv->buildingQueue[0]["startTime"] = $time;
					$objectEnv->buildingQueue[0]["endTime"] = $time + ObjectCalc::getBuildTime($objectEnv, $action["buildingID"], $action["buildingLevel"]) / 4;
					return true;
				} else {
					array_shift($objectEnv->buildingQueue);
					Message::sendNotification(
						$objectEnv->ownerID, 
						"Construction Failed on " . $objectEnv->objectName, 
						"Failed to process building queue item: Recycle Level " . $action["buildingLevel"] . " " . GameCache::get("BUILDINGS")[$action["buildingID"]]["buildName"],
						"ERROR", 
						"constructError.jpg", 
						"game.php?page=buildings&objectID=" . $objectEnv->objectID,
						$time
					);
				}
			} else {
				return false;
			}
		}
		return false;
	}
	
	public static function appendToBuildingQueue($objectEnv, $command, $buildingID, $buildingLevel) {
		array_push($objectEnv->buildingQueue, array(
			"operation" => $command,
			"buildingID" => $buildingID,
			"buildingLevel" => $buildingLevel,
			"id" => uniqid()
		));
		return self::moveAllUp($objectEnv, TIMESTAMP) ? false : "An error has occurred. Check your notifications for details";
	}
	
	public static function removeFromBuildingQueue($objectEnv, $queueItemID) {
		for ($i=0; $i < sizeof($objectEnv->buildingQueue); $i++) { 
			if($objectEnv->buildingQueue[$i]["id"] == $queueItemID) {
				if($i == 0) {
					if($objectEnv->buildingQueue[$i]["operation"] == "Build") {
						$objectEnv->envItems->sum(ObjectCalc::getBuildingUpgradeCost($objectEnv, $objectEnv->buildingQueue[0]["buildingID"], $objectEnv->buildingQueue[0]["buildingLevel"])->multiply(0.5));
						if($objectEnv->buildingQueue[$i]["buildingID"] == "buildResearchLab") {
							$objectEnv->envPlayer->envPlayerData->setValue("flagResearchCenterPlanet", false);
						}
					}
				}
				
				unset($objectEnv->buildingQueue[$i]);
				$objectEnv->buildingQueue = array_values($objectEnv->buildingQueue);
				self::moveAllUp($objectEnv, TIMESTAMP);
				return false;
			}
		}
	}
}

?>
