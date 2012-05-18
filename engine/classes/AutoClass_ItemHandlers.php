<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

class ItemHandlers {
	static function itemhandlerUseResearchNotes($itemID, $numUsed, $objectID, $playerEnv) {
		$baseID = UtilItem::getItemBaseID($itemID);
		$specialID = UtilItem::getItemSpecialID($itemID);
		
		if($baseID == "research-notes") {
			Message::sendNotification($playerEnv->playerID, "Item Used", $numUsed . " on " . $objectID, "OK", "", "game.php", TIMESTAMP);
		} else {
			throw new Exception("Invalid Parameter - Item may not be used with this handler");
		}
	}
}
?>
