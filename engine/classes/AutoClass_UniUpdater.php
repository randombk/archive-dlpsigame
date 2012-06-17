<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class UniUpdater
 */
class UniUpdater
{
	/**
	 * @param int $playerID
	 * @param int $updateTo
	 * @return PlayerEnvironment
	 * @throws Exception
	 */
	public static function updatePlayer($playerID, $updateTo = TIMESTAMP)
	{
		$playerEnv = PlayerEnvironment::fromPlayerID($playerID);
		DBMySQL::exec("BEGIN;");
		try {
			while (true) {
				//Give a 1-second margin of error
				if ($playerEnv->last_update < $updateTo - 1) {
					$nextUpdate = self::getNextUpdatePoint($playerEnv);
					$updateTime = min($nextUpdate[0], $updateTo);

					self::updatePlayerItems($playerEnv, $updateTime);
					if ($nextUpdate[1] == "building" && $updateTime == $nextUpdate[0]) {
						QueueBuilding::processBuildingQueue($playerEnv, $playerEnv->envObjects[$nextUpdate[2]], $updateTime);
					}

					$playerEnv->last_update = $updateTime;
				} else {
					$playerEnv->apply();
					DBMySQL::exec("COMMIT;");
					return $playerEnv;
				}
			}
		} catch (Exception $e) {
			DBMySQL::exec("ROLLBACK;");
			throw $e;
		}
		return null;
	}

	/**
	 * @param PlayerEnvironment $playerEnv
	 * @param int $time
	 */
	public static function updatePlayerItems($playerEnv, $time)
	{
		//$updatedTo = $playerEnv->last_update;

		// 3600 * 24 * 7
		/*if ($time - $updatedTo > 604800) {
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
		*/
		//while ($updatedTo < $time) {
		//	$updateInterval = min(3600, $time - $updatedTo);
			foreach ($playerEnv->envObjects as $objectEnv) {
				CalcObject::updateObject($playerEnv, $objectEnv, $playerEnv->last_update, $time);
			}
		//	$updatedTo += $updateInterval;
		//}
	}

	/**
	 * @param PlayerEnvironment $env
	 * @return array
	 */
	public static function getNextUpdatePoint($env)
	{
		$nextUpdateTime = PHP_INT_MAX;
		$updateType = "normal";
		$updateObject = -1;

		//Check research queue
		foreach ($env->envObjects as $envObject) {
			if (!empty($envObject->researchQueue)) {
				if ($nextUpdateTime > $envObject->researchQueue["endTime"]) {
					$nextUpdateTime = $envObject->researchQueue["endTime"];
					$updateType = "research";
					$updateObject = $envObject->objectID;
				}
			}
		}

		//Check building queue
		foreach ($env->envObjects as $envObject) {
			if (isset($envObject->buildingQueue[0])) {
				if ($nextUpdateTime > $envObject->buildingQueue[0]["endTime"]) {
					$nextUpdateTime = $envObject->buildingQueue[0]["endTime"];
					$updateType = "building";
					$updateObject = $envObject->objectID;
				}
			}
		}

		return array($nextUpdateTime, $updateType, $updateObject);
	}
}
