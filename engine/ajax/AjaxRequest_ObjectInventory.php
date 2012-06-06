<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */
/**
 * Class AjaxRequest_ObjectInventory
 */
class AjaxRequest_ObjectInventory extends AjaxRequest {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();
	}

	private function sendObjectInventoryInfo($data, $objectEnv, $objectMods = null, $code = 0, $message = null) {
		if(is_null($objectMods)) {
			$objectMods = DataMod::calculateObjectModifiers($objectEnv);
		}
		$data["objectModifiers"] = $objectMods->objMods;
		$data["objectWeightPenalty"] = $objectMods->weightPenalty;
		$data["usedStorage"] = $objectEnv->envItems->getTotalWeight();
		$data["objStorage"] = CalcObject::getObjectStorage($objectEnv, $objectMods);
		$data["objUsedEnergyStorage"] = $objectEnv->envItems->getItem("energy");
		$data["objEnergyStorage"] = CalcObject::getMaxEnergyStorage($objectEnv, $objectMods);
		$data["numBuildings"] = $objectEnv->envBuildings->getNumBuildings();
		$data["objectData"] = $objectEnv->envObjectData;
		$this->sendJSONWithObjectData($data, $objectEnv, $code);
	}

	function getObjectInventoryInfo() {
		$objectID = HTTP::REQ("objectID", 0);
		if ($objectID > 0) {
			//Check player permissions
			if(!isset($_SESSION['OBJECTS'][$objectID])) {
				AjaxError::sendError("Access Denied");
			} else {
				$playerEnv = UniUpdater::updatePlayer($_SESSION["playerID"]);
				$objectEnv = $playerEnv->envObjects[$objectID];
				$this->sendObjectInventoryInfo(array(), $objectEnv);
			}
		} else {
			AjaxError::sendError("Invalid Parameters");
		}
	}

	function discardItem() {
		$objectID = HTTP::REQ("objectID", 0);
		$itemArray = HTTP::REQ("itemArray", "json");
		if ($objectID > 0) {
			//Check player permissions
			if(!isset($_SESSION['OBJECTS'][$objectID])) {
				AjaxError::sendError("Access Denied");
			} else {
				try {
					$objectEnv = UniUpdater::updatePlayer($_SESSION["playerID"])->envObjects[$objectID];
					foreach($itemArray as $itemID => $number) {
						$baseData = UtilItem::getItemBaseData($itemID);
						if($baseData) {
							if(!isset($baseData["itemFlags"]["NoDestroy"])) {
								$haveQuantity = $objectEnv->envItems->getItem($itemID);
								if($haveQuantity < floor($number)) {
									$this->sendObjectInventoryInfo(array(), $objectEnv, null, -1, "You don't have the item(s) you were trying to discard");
								} else if(floor($haveQuantity) == floor($number)) {
									$objectEnv->envItems->setItem($itemID, 0);
								} else {
									$objectEnv->envItems->setItem($itemID, $haveQuantity - floor($number));
								}
							} else {
								$this->sendObjectInventoryInfo(array(), $objectEnv, null, -1, "One of the selected items may not be discarded");
							}
						} else {
							AjaxError::sendError("Invalid Parameters");
						}
					}
					$objectEnv->apply();
					$this->sendObjectInventoryInfo(array(), $objectEnv);
				} catch (Exception $e) {
					AjaxError::sendError("Invalid Parameters");
				}
			}
		} else {
			AjaxError::sendError("Invalid Parameters");
		}
	}

	function useItem() {
		$objectID = HTTP::REQ("objectID", 0);
		$itemID = HTTP::REQ("itemID", "");
		$itemAmount = HTTP::REQ("itemAmount", 0);
		if ($objectID > 0 && $itemAmount > 0) {
			//Check player permissions
			if(!isset($_SESSION['OBJECTS'][$objectID])) {
				AjaxError::sendError("Access Denied");
			} else {
				$baseData = UtilItem::getItemBaseData($itemID);
				if($baseData) {
					if(isset($baseData["itemFlags"]["Usable"])) {
						$playerEnv = UniUpdater::updatePlayer($_SESSION["playerID"]);
						$objectEnv = $playerEnv->envObjects[$objectID];

						$haveQuantity = $objectEnv->envItems->getItem($itemID);
						if($haveQuantity < floor($itemAmount)) {
							$this->sendObjectInventoryInfo(array(), $objectEnv, null, -1, "You don't have the item you were trying to use");
						} else {
							try {
								$handlerHame = $baseData["itemFlags"]["Usable"]["itemhandlerUse"];

								$result = ItemHandlers::$handlerHame($itemID, $itemAmount, $objectID, $playerEnv);
								if($result === true) {
									$this->sendObjectInventoryInfo(array(), $objectEnv);
								} else {
									AjaxError::sendError($result);
								}
							} catch (Exception $e) {
								AjaxError::sendError("Unknown Error");
							}
						}
					} else {
						AjaxError::sendError("This item is not usable!");
					}
				} else {
					AjaxError::sendError("Invalid Parameters");
				}
			}
		} else {
			AjaxError::sendError("Invalid Parameters");
		}
	}
}
