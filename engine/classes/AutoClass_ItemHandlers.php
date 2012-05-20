<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

class ItemHandlers {
	static function itemhandlerUseResearchNotes($itemID, $numUsed, $objectID, $playerEnv) {
		$baseID = UtilItem::getItemBaseID($itemID);
		$techID = UtilItem::getItemSpecialID($itemID);
		
		if($baseID == "research-notes") {
			if($playerEnv->envPlayerData->getValue("flagResearchCenterPlanet") == $objectID) {
				if(CalcResearch::canResearch($techID, $playerEnv)) {
					$oldResearchPoints = $playerEnv->envResearch->getResearchPoints($techID);
					$newResearchPoints = $oldResearchPoints + $numUsed;
					
					$playerEnv->envResearch->setResearchPoints($techID, $newResearchPoints);
					$playerEnv->applyPlayerMongo();
					return true;
				} else {
					return "You cannot research that technology!";
				}
			} else {
				return "Research Notes can only be used on planets with a Research Center!";
			}
		} else {
			return "Invalid Parameter - Item may not be used with this handler!";
		}
	}
}
?>
