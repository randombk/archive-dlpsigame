<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

class UniUpdater {
	public static function updateObject($objectID){
		while(true) {
			$GLOBALS['RDBMS']->exec("BEGIN;");		
			try {
				$objectEnv = ObjectEnvironment::fromObjectID($objectID);
			
				//Give a 1-second margin of error
				if($objectEnv->last_update < TIMESTAMP - 1) {
					$nextUpdate = self::getNextUpdatePoint($objectEnv);
					
					$updateTime = min($nextUpdate[0], TIMESTAMP);
					self::updateResources($objectEnv, $updateTime);
					
					if($nextUpdate[1] == "building" && $updateTime == $nextUpdate[0]) {
						QueueBuilding::processBuildingQueue($objectEnv, $updateTime);
					}
					$objectEnv->apply();
				} else {
					$GLOBALS['RDBMS']->exec("COMMIT;");
					return $objectEnv;
				}
			} catch (Exception $e) {
				$GLOBALS['RDBMS']->exec("ROLLBACK;");
				throw $e;
			}
			$GLOBALS['RDBMS']->exec("COMMIT;");
		}
	}
	
	public static function updateResources($objectEnv, $time) {
		$updatedTo = $objectEnv->last_update;
		
		// 3600 * 24 * 7
		if($time - $updatedTo > 604800) {
			//Send message
			Message::sendNotification(
				$objectEnv->ownerID, 
				"Resource production halted on " . $objectEnv->objectName . "", 
				"Nothing has happened on your " . $objectEnv->envObjectCoord->getTypeName() . " " 
					. $objectEnv->objectName . " for more than 7 days. Resource production was halted to save system resources.",
				"WARNING", 
				"time.jpg", 
				"game.php?page=objectoverview&objectID=" . $objectEnv->objectID,
				$time
			);
			$updatedTo = $time - 604800;
		}
		
		while($updatedTo < $time) {
			$updateInterval = min(3600, $time - $updatedTo);
			$objectEnv->envResources = ObjectCalc::calcNewObjectRes($objectEnv, $updateInterval);
			$updatedTo += $updateInterval;
		}
		$objectEnv->last_update = $updatedTo;
	}
	
	public static function getNextUpdatePoint($objectEnv){
		$nextUpdateTime = PHP_INT_MAX;
		$updateType = "object";
		//Check research queue
		//TODO research
		
		//Check building queue
		if(isset($objectEnv->buildingQueue[0])) {
			if($nextUpdateTime > $objectEnv->buildingQueue[0]["endTime"]) {
				$updateType = "building";
				$nextUpdateTime = $objectEnv->buildingQueue[0]["endTime"];
			}
		}
		
		//Check fleets
		$stmtFleetupdate = $GLOBALS['RDBMS']->selectCell(
			tblFLEET_MISSIONS,
			"missionTargetObjectID = :objectID",
			array(":objectID" => $objectEnv->objectID),
			"missionEndTime",
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
		
		return array($nextUpdateTime, $updateType);
	}
}

?>
