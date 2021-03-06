<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class AjaxRequest_ObjectHandler
 */
class AjaxRequest_ObjectHandler extends AjaxRequest {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();
	}

	function getObjectInfo() {
		$objectID = HTTP::REQ("objectID", 0);
		if ($objectID > 0) {
			//Check player permissions
			if(!isset($_SESSION['OBJECTS'][$objectID])) {
				AjaxError::sendError("Access Denied");
			} else {
				$playerEnv = UniUpdater::updatePlayer($_SESSION["playerID"]);
				$objectEnv = $playerEnv->envObjects[$objectID];
				$objectMods = DataMod::calculateObjectModifiers($playerEnv, $objectEnv);
				$data = array(
					"buildingData" => UtilObject::getBuildingList($playerEnv, $objectEnv, true),
					"objectModifiers" => $objectMods->objMods,
					"researchData" => $playerEnv->envResearch->getDataArray(),
					"researchQueue" => $objectEnv->researchQueue,
					"objectWeightPenalty" => $objectMods->weightPenalty,
					"usedStorage" => $objectEnv->envItems->getTotalWeight(),
					"objStorage" => CalcObject::getObjectStorage($playerEnv, $objectEnv, $objectMods),
					"objUsedEnergyStorage" => $objectEnv->envItems->getItem("energy"),
					"objEnergyStorage" => CalcObject::getMaxEnergyStorage($playerEnv, $objectEnv, $objectMods),
					"numBuildings" => $objectEnv->envBuildings->getNumBuildings(),
					"buildQueue" => $objectEnv->buildingQueue,
					"objectData" => $objectEnv->envObjectData
				);
				$this->sendJSONWithObjectData($data, $objectEnv);
			}
		} else {
			AjaxError::sendError("Invalid Parameters");
		}
	}

	function getObjectList() {
		$data = array();
		$playerEnv = UniUpdater::updatePlayer($_SESSION["playerID"]);
		foreach($playerEnv->envObjects as $objectID => $objectEnv) {
			$objectMods = DataMod::calculateObjectModifiers($playerEnv, $objectEnv);
			$data[$objectID] = array(
				"usedStorage" => $objectEnv->envItems->getTotalWeight(),
				"objStorage" => CalcObject::getObjectStorage($playerEnv, $objectEnv, $objectMods),
				"objectName" => $objectEnv->objectName,
				"objectCoords" => $objectEnv->envObjectCoord->getCoordString()
			);
		}
		$this->sendJSON(array(
			"objectList" => $data
		));
	}
}
