<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

class AjaxRequest_Researchhandler extends AjaxRequest {
	function __construct() {
		parent::__construct();
	}

	function getResearch() {
		/*
		$objectEnv = UniUpdater::updateObject($objectID);
		$data = array(
			"buildings" => self::getBuildingList($objectEnv),
			"canBuild" => self::getUpgradeList($objectEnv),
			"resources" => $objectEnv->envResources->getResourceArray(),
			"buildQueue" => $objectEnv->buildingQueue
		);
		$this->sendJSON($data);
		*/
	}
	
	function buildBuilding() {
		$objectID = HTTP::REQ("objectID", 0);
		$buildingID = HTTP::REQ("buildingID", "none");
		$buildingLevel = HTTP::REQ("buildingLevel", 0);
		if ($objectID > 0 && isset(GameCache::get("BUILDINGS")[$buildingID])) {
			//Check player permissions
			if(!isset($_SESSION['OBJECTS'][$objectID])) {
				AjaxError::sendError("Access Denied");
			} else {
				$objectEnv = UniUpdater::updateObject($objectID);
				$result = QueueBuilding::appendToBuildingQueue($objectEnv, "Build", $buildingID, $buildingLevel);
				if(!$result) {
					$objectEnv->apply();
					$this->sendCode(0);
				} else {
					AjaxError::sendError($result);
				}
			}
		} else {
			AjaxError::sendError("Invalid Parameters");
		}
	}
	
	function setBuildingActivity() {
		$objectID = HTTP::REQ("objectID", 0);
		$buildingID = HTTP::REQ("buildingID", "none");
		$activity = HTTP::REQ("buildingActivity", 100);
		if ($objectID > 0 && isset(GameCache::get("BUILDINGS")[$buildingID])) {
			//Check player permissions
			if(!isset($_SESSION['OBJECTS'][$objectID])) {
				AjaxError::sendError("Access Denied");
			} else {
				$objectEnv = UniUpdater::updateObject($objectID);
				if($objectEnv->envBuildings->getBuildingActivity($buildingID) !== (int)$activity) {
					$objectEnv->envBuildings->setBuildingActivity($buildingID, max(0, min(100, (int)$activity)));
					$objectEnv->apply();
				}
				$this->sendCode(0);
			}
		} else {
			AjaxError::sendError("Invalid Parameters");
		}
	}
	
	function setAllBuildingActivity() {
		$objectID = HTTP::REQ("objectID", 0);
		$activityData = HTTP::REQ("activityData", "json");
		if ($objectID > 0) {
			//Check player permissions
			if(!isset($_SESSION['OBJECTS'][$objectID])) {
				AjaxError::sendError("Access Denied");
			} else {
				if(isset($activityData)) {
					$objectEnv = UniUpdater::updateObject($objectID);
					try {
						foreach($activityData as $buildingID => $activity) {
							if(!isset(GameCache::get("BUILDINGS")[$buildingID])) {
								AjaxError::sendError("Invalid Parameters");
							}
							if($objectEnv->envBuildings->getBuildingActivity($buildingID) !== (int)$activity) {
								$objectEnv->envBuildings->setBuildingActivity($buildingID, max(0, min(100, (int)$activity)));
							}
						}
						$objectEnv->apply();
						$this->sendCode(0);	
					} catch (Exception $e) {
						AjaxError::sendError("Invalid Parameters");
					}
				} else {
					AjaxError::sendError("Invalid Parameters");
				}
			}
		} else {
			AjaxError::sendError("Invalid Parameters");
		}
	}
	
	function destroyBuilding() {
		$objectID = HTTP::REQ("objectID", 0);
		$buildingID = HTTP::REQ("buildingID", "none");
		$buildingLevel = HTTP::REQ("buildingLevel", 0);
		if ($objectID > 0 && isset(GameCache::get("BUILDINGS")[$buildingID])) {
			//Check player permissions
			if(!isset($_SESSION['OBJECTS'][$objectID])) {
				AjaxError::sendError("Access Denied");
			} else {
				$objectEnv = UniUpdater::updateObject($objectID);
				$result = QueueBuilding::appendToBuildingQueue($objectEnv, "Destroy", $buildingID, $buildingLevel);
				if(!$result) {
					$objectEnv->apply();
					$this->sendCode(0);
				} else {
					AjaxError::sendError($result);
				}
			}
		} else {
			AjaxError::sendError("Invalid Parameters");
		}
	}
	
	function recycleBuilding() {
		$objectID = HTTP::REQ("objectID", 0);
		$buildingID = HTTP::REQ("buildingID", "none");
		$buildingLevel = HTTP::REQ("buildingLevel", 0);
		if ($objectID > 0 && isset(GameCache::get("BUILDINGS")[$buildingID])) {
			//Check player permissions
			if(!isset($_SESSION['OBJECTS'][$objectID])) {
				AjaxError::sendError("Access Denied");
			} else {
				$objectEnv = UniUpdater::updateObject($objectID);
				$result = QueueBuilding::appendToBuildingQueue($objectEnv, "Recycle", $buildingID, $buildingLevel);
				if(!$result) {
					$objectEnv->apply();
					$this->sendCode(0);
				} else {
					AjaxError::sendError($result);
				}
			}
		} else {
			AjaxError::sendError("Invalid Parameters");
		}
	}
	
	function cancelBuildingQueueItem() {
		$objectID = HTTP::REQ("objectID", 0);
		$queueItemID = HTTP::REQ("queueItemID", "");
		if ($objectID > 0 && $queueItemID != "") {
			//Check player permissions
			if(!isset($_SESSION['OBJECTS'][$objectID])) {
				AjaxError::sendError("Access Denied");
			} else {
				$objectEnv = UniUpdater::updateObject($objectID);
				$result = QueueBuilding::removeFromBuildingQueue($objectEnv, $queueItemID);
				if(!$result) {
					$objectEnv->apply();
					$this->sendCode(0);
				} else {
					AjaxError::sendError($result);
				}
			}
		} else {
			AjaxError::sendError("Invalid Parameters");
		}
	}
	
	static function getBuildingList($objectEnv, $includeProduction = false) {
		$buildings = array();
		
		if($includeProduction) {
			$mod = DataMod::calculateObjectModifiers($objectEnv);
		}
		
		foreach ($objectEnv->envBuildings->getBuildingArray() as $id => $data) {
			$buildings[$id]["level"] = $data[0];
			$buildings[$id]["activity"] = $data[1];
			
			$buildings[$id]["curModifiers"] = ObjectCalc::getBuildingModifiers($objectEnv, $id, $data[0], $data[1]);
			if($includeProduction) {
				$buildings[$id]["curResConsumption"] = ObjectCalc::getBuildingConsumption($objectEnv, $id, $data[0], $mod, $data[1])->getResourceArray();
				$buildings[$id]["curResProduction"] = ObjectCalc::getBuildingProduction($objectEnv, $id, $data[0], $mod, $data[1])->getResourceArray();
				$buildings[$id]["curResearch"] = ObjectCalc::getBuildingResearch($objectEnv, $id, $data[0], $mod, $data[1]);
			}
		}
		return $buildings;
	}
	
	//Returns an array containing upgradable/buildable buildings
	static function getUpgradeList($objectEnv) {
		$canBuild = array();
		
		$mod = DataMod::calculateObjectModifiers($objectEnv);
		foreach (GameCache::get("BUILDINGS") as $id => $data) {
			$nextLevel = $objectEnv->envBuildings->getBuildingLevel($id) + 1;	
			foreach ($objectEnv->buildingQueue as $item) {
				if($item["buildingID"] == $id) {
					if($item["operation"] == "Build")
						$nextLevel = $item["buildingLevel"] + 1;
					else 
						$nextLevel = $item["buildingLevel"];
				}
			}
			
			if(!QueueBuilding::hasPreReq($objectEnv, $id, $nextLevel, TIMESTAMP, true)) continue;
							
			$canBuild[$id]["nextLevel"] = $nextLevel;
			$canBuild[$id]["upgradeTime"] = ObjectCalc::getBuildTime($objectEnv, $id, $nextLevel, $mod);
			$canBuild[$id]["nextResReq"] = ObjectCalc::getBuildingUpgradeCost($objectEnv, $id, $nextLevel, $mod)->getResourceArray();
			
			$canBuild[$id]["nextModifiers"] = ObjectCalc::getBuildingModifiers($objectEnv, $id, $nextLevel);
		}
		return $canBuild;
	} 
}
