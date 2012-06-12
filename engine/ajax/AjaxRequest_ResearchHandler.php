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

	private function sendObjectResearchData($data, $playerEnv, $objectID, $code = 0, $message = null) {
		$data = array(
			"objectID" => $objectID,
			"researchData" => $playerEnv->envResearch->getDataArray(),
			"researchQueue" => $playerEnv->envObjects[$objectID]->researchQueue,
		);
		if($message) {
			$data["message"] = $message;
		}
		$this->sendJSONWithObjectData($data, $playerEnv->envObjects[$objectID], $code);
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
				$this->sendObjectResearchData(array(), $playerEnv, $objectID);
			}
		} else {
			AjaxError::sendError("Invalid Parameters");
		}
	}

	function startResearch() {
		$objectID = HTTP::REQ("objectID", 0);
		$techID = HTTP::REQ("techID", "none");
		$numberNotes = HTTP::REQ("numberNotes", 0);
		if ($objectID > 0 && isset(GameCache::get("RESEARCH")[$techID]) && $numberNotes > 0) {
			//Check player permissions
			if(!isset($_SESSION['OBJECTS'][$objectID])) {
				AjaxError::sendError("Access Denied");
			} else {
				$playerEnv = UniUpdater::updatePlayer($_SESSION["playerID"]);
				$objectEnv = $playerEnv->envObjects[$objectID];
				$result = QueueResearch::setResearchQueue($playerEnv, $objectEnv, $techID, $numberNotes);
				if(!$result) {
					$objectEnv->apply();
					$this->sendObjectResearchData(array(), $playerEnv, $objectID);
				} else {
					$this->sendObjectResearchData(array(), $playerEnv, $objectID, -1, $result);
				}
			}
		} else {
			AjaxError::sendError("Invalid Parameters");
		}
	}

	function cancelResearchQueueItem() {
		$objectID = HTTP::REQ("objectID", 0);
		$queueItemID = HTTP::REQ("queueItemID", "");
		if ($objectID > 0 && $queueItemID != "") {
			//Check player permissions
			if(!isset($_SESSION['OBJECTS'][$objectID])) {
				AjaxError::sendError("Access Denied");
			} else {
				$playerEnv = UniUpdater::updatePlayer($_SESSION["playerID"]);
				$objectEnv = $playerEnv->envObjects[$objectID];
				$result = QueueResearch::cancelResearchQueue($playerEnv, $objectEnv, $queueItemID);
				if(!$result) {
					$objectEnv->apply();
					$this->sendObjectResearchData(array(), $playerEnv, $objectID);
				} else {
					$this->sendObjectResearchData(array(), $playerEnv, $objectID, -1, $result);
				}
			}
		} else {
			AjaxError::sendError("Invalid Parameters");
		}
	}
}
