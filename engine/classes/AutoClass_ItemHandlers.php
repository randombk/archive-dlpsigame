<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

class ItemHandlers {
	static function itemhandlerUseResearchNotes($itemID, $numUsed, $objectID, $playerID) {
		$baseID = UtilItem::getItemBaseID($itemID);
		$specialID = UtilItem::getItemSpecialID($itemID);
		
		if($baseID == "research-notes") {
			
		} else {
			throw new Exception("Invalid Parameter - Item may not be used with this handler");
		}
	}
}
?>
