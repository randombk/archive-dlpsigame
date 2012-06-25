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
				self::updateObject($playerEnv, $objectEnv, $playerEnv->last_update, $time);
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

	/**
	 * @param PlayerEnvironment $playerEnv
	 * @param ObjectEnvironment $objectEnv
	 * @param int $curUpdatePoint
	 * @param int $updateTo
	 * @internal param int $timeDelta
	 * @return DataItem
	 */
	public static function updateObject($playerEnv, $objectEnv, $curUpdatePoint, $updateTo) {
		$nextUpdate = $updateTo;
		$buildingFilter = array();

		while ($curUpdatePoint < $updateTo) {
			$mod = DataMod::calculateObjectModifiers($playerEnv, $objectEnv, $buildingFilter);
			$timeDelta = $nextUpdate - $curUpdatePoint;

			$hourlyDelta = new DataItem();

			//Get total production and consumption
			/* @var $buildProduction DataItem[] */
			$buildProduction = array();

			/* @var $buildConsumption DataItem[] */
			$buildConsumption = array();

			foreach ($objectEnv->envBuildings->getDataArray() as $building => $data) {
				//Ignore filtered buildings
				if(isset($buildingFilter[$building])) {
					continue;
				}

				$buildProduction[$building] = CalcObject::getBuildingProduction($playerEnv, $objectEnv, $building, $data[0], $mod, $data[1]);
				$hourlyDelta->sum($buildProduction[$building]);

				$buildConsumption[$building] = CalcObject::getBuildingConsumption($playerEnv, $objectEnv, $building, $data[0], $mod, $data[1]);
				$hourlyDelta->sub($buildConsumption[$building]);
			}

			//Get research consumption
			$currentResearch = QueueResearch::getCurrentResearch($objectEnv);
			$researchConsumption = array();
			if($currentResearch) {
				$researchConsumption = CalcResearch::getResearchNoteConsumption($playerEnv, $objectEnv, $currentResearch, $mod);
				$hourlyDelta->sub($researchConsumption);
			}

			if(!$hourlyDelta->isPositive()) {
				//Check research requirements
				$keyPoint = $updateTo;
				$keyActor = "none";
				if($currentResearch) {
					foreach($researchConsumption->getDataArray() as $item => $amount) {
						$netLoss = -$hourlyDelta->getItem($item);
						if($netLoss > 0) {
							$amountNeeded = $netLoss * $timeDelta / 3600;
							if($objectEnv->envItems->getItem($item) < $amountNeeded) {
								//Ran out of resources. Find out when resources ran out
								$keyFrame = floor(($objectEnv->envItems->getItem($item) / $netLoss) * 3600);
								$potentialFrame = $curUpdatePoint + $keyFrame;
								if($potentialFrame < $keyPoint) {
									$keyPoint = $potentialFrame;
									$keyActor = "research";
								}
							}
						}
					}
				}

				//Find limiting resource
				foreach($hourlyDelta->getDataArray() as $resID => $amount) {
					if($amount < 0) {
						$amountNeeded = -$amount * $timeDelta / 3600;
						if($objectEnv->envItems->getItem($resID) < $amountNeeded) {
							//Ran out of resources. Find out when resources ran out
							$keyFrame = floor(($objectEnv->envItems->getItem($resID) / $amount) * 3600);
							$potentialFrame = $curUpdatePoint + $keyFrame;
							if($potentialFrame < $keyPoint) {
								$keyPoint = $potentialFrame;
								$keyActor = $resID;
							}
						}
					}
				}

				//Apply key-point
				if($keyPoint < $updateTo) {
					if($keyActor == "research") {
						//Abort research
						QueueResearch::processResearchQueue($playerEnv, $objectEnv, $keyPoint);
						QueueResearch::abortResearchQueue($objectEnv);
						Message::sendNotification(
							$playerEnv->playerID,
							"Research Aborted on " . $objectEnv->objectName . " (Missing Resources)",
							"Insufficient resources to continue research. Initial round of research funding lost.",
							"ERROR",
							"researchError.jpg",
							"game.php?page=research&objectID=" . $objectEnv->objectID,
							$keyPoint
						);
					} else {
						//find buildings using the limited resource and exclude them from next calculation
						foreach($buildConsumption as $buildID => $resArray) {
							foreach($resArray->getDataArray() as $resID => $resAmount) {
								if($resID == $keyActor) {
									$buildingFilter[$buildID] = true;
									break;
								}
							}
						}
					}

					$nextUpdate = $keyPoint;
					$timeDelta = $nextUpdate - $curUpdatePoint;
				}
			}

			$objectEnv->envItems->sum($hourlyDelta->multiply($timeDelta / 3600));

			//Credit completed resources
			QueueResearch::processResearchQueue($playerEnv, $objectEnv, $nextUpdate);

			//Special: max energy
			$maxEnergy = CalcObject::getMaxEnergyStorage($playerEnv, $objectEnv, $mod);
			if(($objectEnv->envItems->getItem("energy")) >= $maxEnergy) {
				$objectEnv->envItems->setItem("energy", $maxEnergy);
			}

			$curUpdatePoint = $nextUpdate;
			$nextUpdate = $updateTo;
		}

		return true;
	}

}
