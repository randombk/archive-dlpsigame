<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class ItemHandlers
 */
class ItemHandlers {
	/**
	 * @param string $itemID
	 * @param int $numUsed
	 * @param int $objectID
	 * @param PlayerEnvironment $playerEnv
	 * @return bool|string
	 */
	static function itemhandlerUseResearchNotes($itemID, $numUsed, $objectID, $playerEnv) {
		$baseID = UtilItem::getItemBaseID($itemID);
		$techID = UtilItem::getItemSpecialID($itemID);

		if($baseID == "research-notes") {
			if($playerEnv->envPlayerData->getValue("flagNationalArchivePlanet") == $objectID) {
				if(isset(GameCache::get("RESEARCH")[$techID])) {
					if(CalcResearch::canResearch($playerEnv, $techID)) {
						$oldResearchPoints = $playerEnv->envResearch->getResearchPoints($techID);
						$playerEnv->envResearch->setResearchPoints($techID, $oldResearchPoints + $numUsed);

						CalcResearch::propagateResearchUpdate($playerEnv, $techID);

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
				return "Research Notes can only be used on planets with a National Archives!";
			}
		} else {
			return "Invalid Parameter - Item may not be used with this handler!";
		}
	}
}
