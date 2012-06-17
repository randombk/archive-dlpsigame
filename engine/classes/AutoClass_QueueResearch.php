<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class QueueResearch
 */
class QueueResearch {
	/**
	 * @param PlayerEnvironment $playerEnv
	 * @param ObjectEnvironment $objectEnv
	 * @param string $techID
	 * @param int $time
	 * @return bool
	 */
	public static function hasPreReq($playerEnv, $objectEnv, $techID, $time = TIMESTAMP) {
		if(!CalcResearch::canResearch($playerEnv, $techID)) {
			Message::sendNotification(
				$playerEnv->playerID,
				"Research Failed on " . $objectEnv->objectName . " (Missing Pre-Requisite)",
				"Unable to research ". GameCache::get("RESEARCH")[$techID]["techName"] ."! The technology has not been unlocked!",
				"ERROR",
				"researchError.jpg",
				"game.php?page=research&objectID=" . $objectEnv->objectID,
				$time
			);
			return false;
		}

		foreach (GameCache::get("RESEARCH")[$techID]["researchNoteBuildingReq"] as $buildID => $level) {
			if($objectEnv->envBuildings->getBuildingLevel($buildID) < $level) {
				Message::sendNotification(
					$playerEnv->playerID,
					"Research Failed on " . $objectEnv->objectName . " (Missing Pre-Requisite)",
					"Unable to research ". GameCache::get("RESEARCH")[$techID]["techName"] ."! Required buildings missing!",
					"ERROR",
					"researchError.jpg",
					"game.php?page=research&objectID=" . $objectEnv->objectID,
					$time
				);
				return false;
			}
		}

		return true;
	}

	/**
	 * @param PlayerEnvironment $playerEnv
	 * @param ObjectEnvironment $objectEnv
	 * @param string $techID
	 * @param int $numQueued
	 * @param int $time
	 * @return bool|DataItem
	 */
	public static function canResearch($playerEnv, $objectEnv, $techID, $numQueued, $time = TIMESTAMP) {
		if(!self::hasPreReq($playerEnv, $objectEnv, $techID, $time)) {
			return false;
		}

		$requiredItems = CalcResearch::getResearchNoteCost($playerEnv, $objectEnv, $techID)->multiply($numQueued);
		if(!$objectEnv->envItems->contains($requiredItems)) {
			Message::sendNotification(
				$playerEnv->playerID,
				"Research Failed on " . $objectEnv->objectName . " (Missing Resources)",
				"Not enough resources to research " . GameCache::get("RESEARCH")[$techID]["techName"],
				"ERROR",
				"researchError.jpg",
				"game.php?page=research&objectID=" . $objectEnv->objectID,
				$time
			);
			return false;
		}
		return $requiredItems;
	}

	/**
	 * @param ObjectEnvironment $objectEnv
	 * @return null | string
	 */
	public static function getCurrentResearch($objectEnv) {
		return isset($objectEnv->researchQueue["techID"]) ? $objectEnv->researchQueue["techID"] : null;
	}

	/**
	 * @param PlayerEnvironment $playerEnv
	 * @param ObjectEnvironment $objectEnv
	 * @param int $time
	 * @return bool
	 */
	public static function processResearchQueue($playerEnv, $objectEnv, $time) {
		if(!empty($objectEnv->researchQueue)) {
			//Actions are validated upon insertion into the queue
			$timeDelta = $time - $objectEnv->researchQueue["startTime"];
			$numNotesCreated = min(floor($timeDelta / $objectEnv->researchQueue["researchTime"]), $objectEnv->researchQueue["numQueued"]);
			$objectEnv->researchQueue["startTime"] += $numNotesCreated * $objectEnv->researchQueue["researchTime"];
			$objectEnv->researchQueue["numQueued"] -= $numNotesCreated;
			$objectEnv->envItems->addItem("research-notes_".$objectEnv->researchQueue["techID"], $numNotesCreated);

			if($objectEnv->researchQueue["numQueued"] < 1) {
				self::abortResearchQueue($objectEnv);
			}
		}
		return true;
	}

	/**
	 * @param PlayerEnvironment $playerEnv
	 * @param ObjectEnvironment $objectEnv
	 * @param string $techID
	 * @param int $numQueued
	 * @return bool|string
	 */
	public static function setResearchQueue($playerEnv, $objectEnv, $techID, $numQueued) {
		if(!empty($objectEnv->researchQueue)) {
			return "A research is already active";
		} else {
			$reqItems = self::canResearch($playerEnv, $objectEnv, $techID, $numQueued);
			if($reqItems === false){
				return "Missing resources";
			} else {
				$researchTime = CalcResearch::getResearchTime($playerEnv, $objectEnv, $techID, $objectEnv->envMods);
				$objectEnv->researchQueue = array(
					"techID" => $techID,
					"numQueued" => $numQueued,
					"researchTime" => $researchTime,
					"startTime" => TIMESTAMP,
					"endTime" => TIMESTAMP + $researchTime * $numQueued,
					"id" => uniqid()
				);
				$objectEnv->envItems->sub($reqItems);
				return false;
			}
		}
	}

	/**
	 * @param PlayerEnvironment $playerEnv
	 * @param ObjectEnvironment $objectEnv
	 * @param string $queueItemID
	 * @return bool
	 */
	public static function cancelResearchQueue($playerEnv, $objectEnv, $queueItemID) {
		if(isset($objectEnv->researchQueue["id"]) && $objectEnv->researchQueue["id"] == $queueItemID) {
			$objectEnv->envItems->sum(CalcResearch::getResearchNoteCost($playerEnv, $objectEnv, $objectEnv->researchQueue["techID"])->multiply($objectEnv->researchQueue["numQueued"] * 0.5));
			$objectEnv->researchQueue = array();
			return false;
		}
		return "Item not in queue";
	}

	/**
	 * @param ObjectEnvironment $objectEnv
	 * @return bool
	 */
	public static function abortResearchQueue($objectEnv) {
		$objectEnv->researchQueue = array();
		return true;
	}
}
