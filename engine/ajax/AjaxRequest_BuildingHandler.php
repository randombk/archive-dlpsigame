<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */
/**
 * Class AjaxRequest_BuildingHandler
 */
class AjaxRequest_BuildingHandler extends AjaxRequest {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();
	}

	/**
	 * @param ObjectEnvironment $objectEnv
	 * @param int $code
	 */
	private function sendBuildingInfo($objectEnv, $code = 0, $message = null) {
		$data = array(
			"buildings" => self::getBuildingList($objectEnv),
			"canBuild" => self::getUpgradeList($objectEnv),
			//"items" => UtilItem::buildItemDataArray($objectEnv->envItems),
			"buildQueue" => $objectEnv->buildingQueue
		);
		if($message) {
			$data["message"] = $message;
		}
		$this->sendJSONWithObjectData($data, $objectEnv, $code);
	}

	function getBuildings() {
		$objectID = HTTP::REQ("objectID", 0);
		if ($objectID > 0) {
			//Check player permissions
			if(!isset($_SESSION['OBJECTS'][$objectID])) {
				AjaxError::sendError("Access Denied");
			} else {
				$objectEnv = UniUpdater::updatePlayer($_SESSION["playerID"])->envObjects[$objectID];
				$this->sendBuildingInfo($objectEnv);
			}
		} else {
			AjaxError::sendError("Invalid Parameters");
		}
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
				$objectEnv = UniUpdater::updatePlayer($_SESSION["playerID"])->envObjects[$objectID];
				$result = QueueBuilding::appendToBuildingQueue($objectEnv, "Build", $buildingID, $buildingLevel);
				if(!$result) {
					if($buildingID == "buildNationalArchives") {
						$objectEnv->envPlayer->applyPlayerMongo();
					}
					$objectEnv->apply();
					$this->sendBuildingInfo($objectEnv);
				} else {
					$this->sendBuildingInfo($objectEnv, -1, $result);
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
				$objectEnv = UniUpdater::updatePlayer($_SESSION["playerID"])->envObjects[$objectID];
				if($objectEnv->envBuildings->getBuildingActivity($buildingID) !== (int)$activity) {
					$objectEnv->envBuildings->setBuildingActivity($buildingID, max(0, min(100, (int)$activity)));
					$objectEnv->apply();
				}
				$this->sendBuildingInfo($objectEnv);
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
					$objectEnv = UniUpdater::updatePlayer($_SESSION["playerID"])->envObjects[$objectID];
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
						$this->sendBuildingInfo($objectEnv);
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
				$objectEnv = UniUpdater::updatePlayer($_SESSION["playerID"])->envObjects[$objectID];
				$result = QueueBuilding::appendToBuildingQueue($objectEnv, "Destroy", $buildingID, $buildingLevel);
				if(!$result) {
					$objectEnv->apply();
					$this->sendBuildingInfo($objectEnv);
				} else {
					$this->sendBuildingInfo($objectEnv, -1, $result);
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
				$objectEnv = UniUpdater::updatePlayer($_SESSION["playerID"])->envObjects[$objectID];
				$result = QueueBuilding::appendToBuildingQueue($objectEnv, "Recycle", $buildingID, $buildingLevel);
				if(!$result) {
					$objectEnv->apply();
					$this->sendBuildingInfo($objectEnv);
				} else {
					$this->sendBuildingInfo($objectEnv, -1, $result);
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
				$objectEnv = UniUpdater::updatePlayer($_SESSION["playerID"])->envObjects[$objectID];
				$result = QueueBuilding::removeFromBuildingQueue($objectEnv, $queueItemID);
				if(!$result) {
					$objectEnv->apply();
					$this->sendBuildingInfo($objectEnv);
				} else {
					$this->sendBuildingInfo($objectEnv, -1, $result);
				}
			}
		} else {
			AjaxError::sendError("Invalid Parameters");
		}
	}

	/**
	 * @param ObjectEnvironment $objectEnv
	 * @param bool $getActual
	 * @return array
	 */
	static function getBuildingList($objectEnv, $getActual = false) {
		$buildings = array();

		if($getActual) {
			$mod = DataMod::calculateObjectModifiers($objectEnv);
		} else {
			$mod = new DataMod();
		}

		foreach ($objectEnv->envBuildings->getDataArray() as $id => $data) {
			$buildings[$id]["level"] = $data[0];
			$buildings[$id]["activity"] = $getActual ? $data[1] : 100;

			$buildings[$id]["curModifiers"] = CalcObject::getBuildingModifiers($objectEnv, $id, $data[0], $getActual ? $data[1]: 100);
			$buildings[$id]["curResConsumption"] = UtilItem::buildItemDataArray(CalcObject::getBuildingConsumption($objectEnv, $id, $data[0], $mod, $getActual ? $data[1] : 100));
			$buildings[$id]["curResProduction"] = UtilItem::buildItemDataArray(CalcObject::getBuildingProduction($objectEnv, $id, $data[0], $mod, $getActual ? $data[1] : 100));
		}
		return $buildings;
	}

	//Returns an array containing upgradable/buildable buildings
	/**
	 * @param ObjectEnvironment $objectEnv
	 * @return array
	 */
	static function getUpgradeList($objectEnv) {
		$canBuild = array();

		$mod = DataMod::calculateObjectModifiers($objectEnv);
		$resMod = new DataMod();

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
			$canBuild[$id]["upgradeTime"] = CalcObject::getBuildTime($objectEnv, $id, $nextLevel, $mod);
			$canBuild[$id]["nextResReq"] = UtilItem::buildItemDataArray(CalcObject::getBuildingUpgradeCost($objectEnv, $id, $nextLevel, $mod));
			$canBuild[$id]["nextResConsumption"] = UtilItem::buildItemDataArray(CalcObject::getBuildingConsumption($objectEnv, $id, $nextLevel, $resMod));
			$canBuild[$id]["nextResProduction"] = UtilItem::buildItemDataArray(CalcObject::getBuildingProduction($objectEnv, $id, $nextLevel, $resMod));
			$canBuild[$id]["nextModifiers"] = CalcObject::getBuildingModifiers($objectEnv, $id, $nextLevel);
		}
		return $canBuild;
	}
}
