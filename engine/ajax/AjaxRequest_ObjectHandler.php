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
		require_once(ROOT_PATH . 'engine/ajax/AjaxRequest_BuildingHandler.php');
		$objectID = HTTP::REQ("objectID", 0);
		if ($objectID > 0) {
			//Check player permissions
			if(!isset($_SESSION['OBJECTS'][$objectID])) {
				AjaxError::sendError("Access Denied");
			} else {
				$playerEnv = UniUpdater::updatePlayer($_SESSION["playerID"]);
				$objectEnv = $playerEnv->envObjects[$objectID];
				$objectMods = DataMod::calculateObjectModifiers($objectEnv);
				$data = array(
					"buildings" => AjaxRequest_BuildingHandler::getBuildingList($objectEnv, true),
					"objectModifiers" => $objectMods->objMods,
					"objectWeightPenalty" => $objectMods->weightPenalty,
					"usedStorage" => $objectEnv->envItems->getTotalWeight(),
					"objStorage" => CalcObject::getObjectStorage($objectEnv, $objectMods),
					"objEnergyStorage" => CalcObject::getMaxEnergyStorage($objectEnv, $objectMods),
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
			$objectMods = DataMod::calculateObjectModifiers($objectEnv);
			$data[$objectID] = array(
				"usedStorage" => $objectEnv->envItems->getTotalWeight(),
				"objStorage" => CalcObject::getObjectStorage($objectEnv, $objectMods),
				"objectName" => $objectEnv->objectName,
				"objectCoords" => $objectEnv->envObjectCoord->getCoordString()
			);
		}
		$this->sendJSON($data);
	}
}
