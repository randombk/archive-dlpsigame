<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

class UniUpdater {
	public static function updatePlayer($playerID, $updateTo = TIMESTAMP){
		$playerEnv = PlayerEnvironment::fromPlayerID($playerID);
		$GLOBALS['RDBMS']->exec("BEGIN;");		
		try {
			while(true) {
				//Give a 1-second margin of error
				if($playerEnv->last_update < $updateTo - 1) {
					$nextUpdate = self::getNextUpdatePoint($playerEnv);
					$updateTime = min($nextUpdate[0], $updateTo);
					
					self::updatePlayerResources($playerEnv, $updateTime);
					if($nextUpdate[1] == "building" && $updateTime == $nextUpdate[0]) {
						QueueBuilding::processBuildingQueue($playerEnv->envObjects[$nextUpdate[2]], $updateTime);
					}
				} else {
					$playerEnv->apply();
					$GLOBALS['RDBMS']->exec("COMMIT;");
					return $playerEnv;
				}
			}
		} catch (Exception $e) {
			$GLOBALS['RDBMS']->exec("ROLLBACK;");
			throw $e;
		}
	}
	
	public static function updatePlayerResources($playerEnv, $time) {
		$updatedTo = $playerEnv->last_update;
		
		// 3600 * 24 * 7
		if($time - $updatedTo > 604800) {
			Message::sendNotification(
				$playerEnv->playerID, 
				"Resource production halted on " . $playerEnv->playerName . "", 
				"Nothing has happened on your account for more than 7 days. Resource production was halted to save system resources.",
				"WARNING", 
				"time.jpg", 
				"game.php",
				$time
			);
			$updatedTo = $time - 604800;
		}
		
		while($updatedTo < $time) {
			$updateInterval = min(3600, $time - $updatedTo);
			foreach($playerEnv->envObjects as $objectEnv) {
				$objectEnv->envResources = ObjectCalc::calcNewObjectRes($objectEnv, $updateInterval);
			}
			$updatedTo += $updateInterval;
		}
		$playerEnv->last_update = $updatedTo;
	}
	
	public static function getNextUpdatePoint($env){
		$nextUpdateTime = PHP_INT_MAX;
		$updateType = "normal";
		$updateObject = -1;
		
		//Check research queue
		if(isset($env->researchQueue[0], $env->researchProduction[$env->researchQueue[0]["type"]])) {
			$requiredPoints = $env->researchQueue[0]["points"] - $env->envResearch->getResearchPoints($env->researchQueue[0]["research"]);
			$nextResearchUpdateTime = min($env->researchQueue[0]["last_update"] + ceil($requiredPoints / $env->researchProduction[$env->researchQueue[0]["type"]]));
			if($nextUpdateTime > $nextResearchUpdateTime) {
				$nextUpdateTime = $nextResearchUpdateTime;
				$updateType = "research";
			}
		}
		
		//Check building queue
		foreach($env->envObjects as $envObject) {
			if(isset($envObject->buildingQueue[0])) {
				if($nextUpdateTime > $envObject->buildingQueue[0]["endTime"]) {
					$nextUpdateTime = $envObject->buildingQueue[0]["endTime"];
					$updateType = "building";
					$updateObject = $envObject->objectID;
				}
			}
		}

		//Check fleets
		/*
		$stmtFleetupdate = $GLOBALS['RDBMS']->selectCell(
			tblFLEET_MISSIONS,
			"missionTargetPlayerID = :playerID",
			array(":objectID" => $env->objectID),
			"missionEndTime, missionTargetObjectID",
			"ORDER BY missionEndTime LIMIT 1;"
		);
		
		if($stmtFleetupdate) {
			$fleetupdate = $stmtFleetupdate;
		} else {
			$fleetupdate = PHP_INT_MAX;
		}
		
		if($nextUpdateTime > $fleetupdate) {
			$updateType = "fleet";
			$nextUpdateTime = $fleetupdate;
		}
		*/
		return array($nextUpdateTime, $updateType, $updateObject);
	}
}

?>
