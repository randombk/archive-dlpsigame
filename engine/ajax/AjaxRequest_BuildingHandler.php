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
	 * @param null $message
	 */
	private function sendBuildingInfo($objectEnv, $code = 0, $message = null) {
		$data = array(
			//"buildings" => self::getBuildingList($objectEnv),
			//"items" => UtilItem::buildItemDataArray($objectEnv->envItems),
			"canBuild" => UtilObject::getUpgradeList($objectEnv),
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
}
