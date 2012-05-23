<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class PlayerEnvironment
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

	/**
	 * @var UniCoord[]
	 */
	public $objects = array();

	public $envStars = array();

	/**
	 * @var ObjectEnvironment[]
	 */
	public $envObjects = array();

	/**
	 * @var DataResearch
	 */
	public $envResearch = null;

	/**
	 * @var DataPlayer
	 */
	public $envPlayerData = null;
	
	public $researchProduction = null;

	/**
	 * @param $playerID
	 * @return PlayerEnvironment
	 * @throws Exception
	 */
	public static function fromPlayerID($playerID) {
		$stmt = DBMySQL::prepare("
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

	/**
	 *
	 */
	public function __construct() {
		if($this->playerID < 0) {
			throw new Exception("Invalid playerID - Object should be initiated using static constructor");
		}
		
		$this->getResearchData();
		$this->getPlayerData();
		$this->getObjects();
	}
	
	private function getResearchData() {
		$this->envResearch = UtilPlayer::getPlayerResearchData($this->playerID);
	}
	
	private function getPlayerData() {
		$this->envPlayerData = UtilPlayer::getPlayerData($this->playerID);
	}
	
	private function getObjects() {
		$this->objects = UtilPlayer::getPlayerObjects($this->playerID);
		foreach ($this->objects as $object) {
			$starID = $object->getStarID();
			$objectID = $object->getObjectID();
			$this->envObjects[$objectID] = ObjectEnvironment::fromObjectID($objectID, $this);
			if(!isset($this->envStars[$starID])) {
				$this->envStars[$starID] = GameCache::get('STARS')[$starID];
				$this->envStars[$starID]["objects"] = array();
			}
			array_push($this->envStars[$starID]["objects"], $objectID);
		}
	}

	/**
	 * @return bool
	 * @throws Exception
	 */
	public function apply() {
		$result = DBMySQL::update(
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
			
			$this->applyPlayerMongo();
			return true;
		} else {
			throw new Exception("Unknown PDO Error ");
		}
	}
	
	public function applyPlayerMongo() {
		UtilPlayer::setPlayerResearchData($this->envResearch->getResearchArray(), $this->playerID);
		UtilPlayer::setPlayerData($this->envPlayerData->getDataArray(), $this->playerID);
	}
}