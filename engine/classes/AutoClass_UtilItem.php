<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class UtilItem
 */
class UtilItem {
	/**
	 * @param $itemID
	 * @return array
	 */
	static function splitItemID($itemID) {
		return explode("_", $itemID, 2);
	}

	/**
	 * @param $itemID
	 * @return string
	 */
	static function getItemBaseID($itemID) {
		$idArray = self::splitItemID($itemID);
		return isset($idArray[0]) ? $idArray[0] : "";
	}

	/**
	 * @param $itemID
	 * @return string
	 */
	static function getItemSpecialID($itemID) {
		$idArray = self::splitItemID($itemID);
		return isset($idArray[1]) ? $idArray[1] : "";
	}

	/**
	 * @param $itemID
	 * @return null
	 */
	static function getItemBaseData($itemID) {
		$baseID = self::getItemBaseID($itemID);
		if(isset(GameCache::get("ITEMS")[$baseID])) {
			return GameCache::get("ITEMS")[$baseID];
		} else {
			return null;
		}
	}

	/**
	 * @param $itemID
	 * @return array
	 */
	static function getItemParamData($itemID) {
		if(isset(GameCache::get("ITEMPARAMS")[$itemID])) {
			return GameCache::get("ITEMPARAMS")[$itemID];
		} else {
			$data = DBMongo::getItemParams($itemID);
			return is_null($data) ? array() : $data;
		}
	}

	/**
	 * @param $dataItem DataItem
	 * @return array
	 */
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