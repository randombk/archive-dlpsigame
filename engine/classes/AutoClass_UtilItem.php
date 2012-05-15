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
	
	static function getItemSubID($itemID) {
		$idArray = self::splitItemID($itemID);
		return isset($idArray[1]) ? $idArray[1] : "";
	}
}
?>
