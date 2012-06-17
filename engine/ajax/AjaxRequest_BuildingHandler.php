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
	 * @param PlayerEnvironment $playerEnv
	 * @param ObjectEnvironment $objectEnv
	 * @param int $code
	 * @param null $message
	 */
	private function sendBuildingInfo($playerEnv, $objectEnv, $code = 0, $message = null) {
		$data = array(
			"canBuild" => UtilObject::getUpgradeList($playerEnv, $objectEnv),
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
				$playerEnv = UniUpdater::updatePlayer($_SESSION["playerID"]);
				$objectEnv = $playerEnv->envObjects[$objectID];
				$this->sendBuildingInfo($playerEnv, $objectEnv);
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
				$playerEnv = UniUpdater::updatePlayer($_SESSION["playerID"]);
				$objectEnv = $playerEnv->envObjects[$objectID];
				$result = QueueBuilding::appendToBuildingQueue($playerEnv, $objectEnv, "Build", $buildingID, $buildingLevel);
				if(!$result) {
					if($buildingID == "buildNationalArchives") {
						$objectEnv->envPlayer->applyPlayerMongo();
					}
					$objectEnv->apply();
					$this->sendBuildingInfo($playerEnv, $objectEnv);
				} else {
					$this->sendBuildingInfo($playerEnv, $objectEnv, -1, $result);
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
				$playerEnv = UniUpdater::updatePlayer($_SESSION["playerID"]);
				$objectEnv = $playerEnv->envObjects[$objectID];
				if($objectEnv->envBuildings->getBuildingActivity($buildingID) !== (int)$activity) {
					$objectEnv->envBuildings->setBuildingActivity($buildingID, max(0, min(100, (int)$activity)));
					$objectEnv->apply();
				}
				$this->sendBuildingInfo($playerEnv, $objectEnv);
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
					$playerEnv = UniUpdater::updatePlayer($_SESSION["playerID"]);
					$objectEnv = $playerEnv->envObjects[$objectID];
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
						$this->sendBuildingInfo($playerEnv, $objectEnv);
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
				$playerEnv = UniUpdater::updatePlayer($_SESSION["playerID"]);
				$objectEnv = $playerEnv->envObjects[$objectID];
				$result = QueueBuilding::appendToBuildingQueue($playerEnv, $objectEnv, "Destroy", $buildingID, $buildingLevel);
				if(!$result) {
					$objectEnv->apply();
					$this->sendBuildingInfo($playerEnv, $objectEnv);
				} else {
					$this->sendBuildingInfo($playerEnv, $objectEnv, -1, $result);
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
				$playerEnv = UniUpdater::updatePlayer($_SESSION["playerID"]);
				$objectEnv = $playerEnv->envObjects[$objectID];
				$result = QueueBuilding::appendToBuildingQueue($playerEnv, $objectEnv, "Recycle", $buildingID, $buildingLevel);
				if(!$result) {
					$objectEnv->apply();
					$this->sendBuildingInfo($playerEnv, $objectEnv);
				} else {
					$this->sendBuildingInfo($playerEnv, $objectEnv, -1, $result);
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
				$playerEnv = UniUpdater::updatePlayer($_SESSION["playerID"]);
				$objectEnv = $playerEnv->envObjects[$objectID];
				$result = QueueBuilding::removeFromBuildingQueue($playerEnv, $objectEnv, $queueItemID);
				if(!$result) {
					$objectEnv->apply();
					$this->sendBuildingInfo($playerEnv, $objectEnv);
				} else {
					$this->sendBuildingInfo($playerEnv, $objectEnv, -1, $result);
				}
			}
		} else {
			AjaxError::sendError("Invalid Parameters");
		}
	}
}
