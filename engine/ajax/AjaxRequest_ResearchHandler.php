<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class AjaxRequest_ResearchHandler
 */
class AjaxRequest_ResearchHandler extends AjaxRequest {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();
	}

	function getResearch() {
		$playerEnv = UniUpdater::updatePlayer($_SESSION["playerID"]);
		$data = array(
			"researchData" => $playerEnv->envResearch->getDataArray()
		);
		$this->sendJSON($data);
	}

	function getObjectResearch() {
		$objectID = HTTP::REQ("objectID", 0);
		if ($objectID > 0) {
			//Check player permissions
			if(!isset($_SESSION['OBJECTS'][$objectID])) {
				AjaxError::sendError("Access Denied");
			} else {
				$playerEnv = UniUpdater::updatePlayer($_SESSION["playerID"]);
				$data = array(
					"objectID" => $objectID,
					"researchData" => $playerEnv->envResearch->getDataArray()//,
					//"researchQueue" => $playerEnv->researchQueue,
					//"researchProduction" => $playerEnv->researchProduction
				);
				$this->sendJSONWithObjectData($data, $playerEnv->envObjects[$objectID]);
			}
		} else {
			AjaxError::sendError("Invalid Parameters");
		}
	}
}
