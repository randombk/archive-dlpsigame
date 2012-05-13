<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */
class AjaxRequest_ObjectInventory extends AjaxRequest {
	function __construct() {
		parent::__construct();
	}

	function getInventory() {
		$objectID = HTTP::REQ("objectID", 0);
		if ($objectID > 0) {
			//Check player permissions
			if(!isset($_SESSION['OBJECTS'][$objectID])) {
				AjaxError::sendError("Access Denied");
			} else {
				$objectEnv = UniUpdater::updatePlayer($_SESSION["playerID"])->envObjects[$objectID];
				$this->sendJSON($objectEnv->envResources->getResourceArray());
			}
		} else {
			AjaxError::sendError("Invalid Parameters");
		}
	}
}
