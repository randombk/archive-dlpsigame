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
			if($playerEnv->envPlayerData->getValue("flagNationalArchivePlanet") == $objectID) {
				if(isset(GameCache::get("RESEARCH")[$techID])) {
					if(CalcResearch::canResearch($techID, $playerEnv)) {
						$oldResearchPoints = $playerEnv->envResearch->getResearchPoints($techID);
						$newResearchPoints = $oldResearchPoints + $numUsed;
						
						$playerEnv->envResearch->setResearchPoints($techID, $newResearchPoints);
						$playerEnv->envObjects[$objectID]->envItems->addItem($itemID, - $numUsed);
						
						$playerEnv->envObjects[$objectID]->applyObjectMongo();
						$playerEnv->applyPlayerMongo();
						
						return true;
					} else {
						return "You cannot research that technology!";
					}
				} else {
					return "This research note is for an non-existant technology! This could be a result of a removed/changed research. Please contact an OP for help.";
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
