<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

class PlayerEnvironment {
	public $playerID = -1;
	public $playerName = "";
	public $joinDate = "";
	public $numWins = 0;
	public $numLoss = 0;
	public $numDraw = 0;
	public $researchQueue = "";
	public $last_update = PHP_INT_MAX;
	
	public $objects = array();
	public $envStars = array();
	public $envObjects = array();
	public $envResearch = null;
	
	public $researchProduction = null;
	
	public static function fromPlayerID($playerID) {
		$stmt = $GLOBALS['RDBMS']->prepare("
			SELECT 
				playerID, 
				playerName,
				joinDate,
				numWins,
				numLoss,
				numDraw,
				researchQueue,
				last_update
			FROM " . tblPLAYERS . "
			WHERE playerID = :playerID
			FOR UPDATE;
		");
		
		$stmt->bindValue(':playerID', $playerID, PDO::PARAM_INT);
		if($stmt->execute()) {
			$obj = $stmt->fetchObject('PlayerEnvironment');
			$obj->researchQueue = json_decode($obj->researchQueue, true);
			if(!isset($obj->researchQueue)) {
				$obj->researchQueue = array();
			}
			return $obj;
		} else {
			throw new Exception("Invalid playerID");
		}
	}
	
	public function __construct() {
		if($this->playerID < 0) {
			throw new Exception("Invalid playerID - Object should be initiated using static constructor");
		}
		
		$this->getResearchData();
		$this->getObjects();
	}
	
	private function getResearchData() {
		$this->envResearch = PlayerUtils::getPlayerResearchData($this->playerID);
	}
	
	private function getObjects() {
		$this->objects = PlayerUtils::getPlayerObjects($this->playerID);
		foreach ($this->objects as $object) {
			$starID = $object->getStarID();
			$objectID = $object->getObjectID();
			$this->envObjects[$objectID] = ObjectEnvironment::fromObjectID($objectID);
			if(!isset($this->envStars[$starID])) {
				$this->envStars[$starID] = GameCache::get('STARS')[$starID];
				$this->envStars[$starID]["objects"] = array();
			}
			array_push($this->envStars[$starID]["objects"], $objectID);
		}
	}
	
	public function apply() {
		$result = $GLOBALS['RDBMS']->update(
			tblPLAYERS, 
			array(
				"playerID" => $this->playerID,
				"playerName" => $this->playerName,
				"joinDate" => $this->joinDate,
				"numWins" => $this->numWins,
				"numLoss" => $this->numLoss,
				"numDraw" => $this->numDraw,
				"researchQueue" => json_encode($this->researchQueue),
				"last_update" => $this->last_update
			),
			"playerID = :playerID",
			array(":playerID" => $this->playerID)
		);
		
		if($result !== false) {
			foreach($this->envObjects as $envObject) {
				if(!$envObject->apply()) {
					throw new Exception("Unknown PDO Error");
				} 
			}
			
			PlayerUtils::setPlayerResearchData($this->envResearch);
			return true;
		} else {
			throw new Exception("Unknown PDO Error ");
		}
	}
}
?>
