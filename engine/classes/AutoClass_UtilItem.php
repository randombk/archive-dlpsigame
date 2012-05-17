<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

class UtilItem {
	static function splitItemID($itemID) {
		return explode("_", $itemID, 2);
	}
	
	static function getItemBaseID($itemID) {
		$idArray = self::splitItemID($itemID);
		return isset($idArray[0]) ? $idArray[0] : "";
	}
	
	static function getItemSpecialID($itemID) {
		$idArray = self::splitItemID($itemID);
		return isset($idArray[1]) ? $idArray[1] : "";
	}
	
	static function getItemBaseData($itemID) {
		$baseID = self::getItemBaseID($itemID);
		if(isset(GameCache::get("ITEMS")[$baseID])) {
			return GameCache::get("ITEMS")[$baseID];
		} else {
			return null;
		}
	}
	
	static function getItemParamData($itemID) {
		if(isset(GameCache::get("ITEMPARAMS")[$itemID])) {
			return GameCache::get("ITEMPARAMS")[$itemID];
		} else {
			$data = $GLOBALS["MONGO"]->getItemParams($itemID);
			return is_null($data) ? array() : $data;
		}
	}
	
	static function buildItemDataArray($dataItem) {
		$dataArray = array();
		$inv = $dataItem->getItemArray();
		foreach ($inv as $itemID => $quantity) {
			$dataArray[$itemID] = self::getItemParamData($itemID);
			$dataArray[$itemID]["quantity"] = $quantity;
		}
		return $dataArray;
	}
}
?>
