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
				$this->sendJSON(UtilItem::buildItemDataArray($objectEnv->envItems));
			}
		} else {
			AjaxError::sendError("Invalid Parameters");
		}
	}
	
	function useItem() {
		$objectID = HTTP::REQ("objectID", 0);
		if ($objectID > 0) {
			//Check player permissions
			if(!isset($_SESSION['OBJECTS'][$objectID])) {
				AjaxError::sendError("Access Denied");
			} else {
				$objectEnv = UniUpdater::updatePlayer($_SESSION["playerID"])->envObjects[$objectID];
				$this->sendJSON(UtilItem::buildItemDataArray($objectEnv->envItems));
			}
		} else {
			AjaxError::sendError("Invalid Parameters");
		}
	}
	
	function disgardItem() {
		$objectID = HTTP::REQ("objectID", 0);
		$itemArray = HTTP::REQ("itemArray", "json");
		if ($objectID > 0) {
			//Check player permissions
			if(!isset($_SESSION['OBJECTS'][$objectID])) {
				AjaxError::sendError("Access Denied");
			} else {
				$objectEnv = UniUpdater::updatePlayer($_SESSION["playerID"])->envObjects[$objectID];
				try {
					foreach($itemArray as $itemID => $number) {
						$baseData = UtilItem::getItemBaseData($itemID);	
						if($baseData) {
							if(!isset($baseData["itemFlags"]["NoDestroy"])) {
								$haveQuantity = $objectEnv->envItems->getItem($itemID);
								if($haveQuantity < floor($number)) {
									AjaxError::sendError("Invalid Parameters: You don't have enough of that item");
								} else if(floor($haveQuantity) == floor($number)) {
									$objectEnv->envItems->setItem($itemID, 0);
								} else {
									$objectEnv->envItems->setItem($itemID, $haveQuantity - floor($number));
								}
							} else {
								AjaxError::sendError("Invalid Parameters: Item may not ne discarded");
							}
						} else {
							AjaxError::sendError("Invalid Parameters");
						}
					}
					$objectEnv->apply();
					$this->sendCode(0);	
				} catch (Exception $e) {
					AjaxError::sendError("Invalid Parameters");
				}
				
				$this->sendJSON(UtilItem::buildItemDataArray($objectEnv->envItems));
			}
		} else {
			AjaxError::sendError("Invalid Parameters");
		}
	}
}
