<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

class ObjectEnvironment {
	public $objectID = -1;
	public $objectType = 0;
	public $objectName = "";
	public $objectIndex = 0;
	public $objectImageID = 0;
	public $ownerID = 0;
	public $starID = 0;
	public $last_update = PHP_INT_MAX;
	public $objectDesc = "";
	public $buildingQueue = array();
	
	public $envBuildings = null; //DataBuilding
	public $envResources = null; //DataResource
	public $envMods = null; //DataMod
	public $envObjectCoord = null; //UniCoord 
	public $envObjectData = null;
	
	public static function fromObjectID($objectID) {
		$stmt = $GLOBALS['RDBMS']->prepare("
			SELECT 
				objectID, 
				objectType,
				objectName,
				objectIndex,
				objectImageID,
				ownerID,
				starID,
				last_update,
				objectDesc,
				buildingQueue
			FROM " . tblUNI_OBJECTS . "
			WHERE objectID = :objectID
			FOR UPDATE;
		");
		
		$stmt->bindValue(':objectID', $objectID, PDO::PARAM_INT);
		if($stmt->execute()) {
			$obj = $stmt->fetchObject('ObjectEnvironment');
			$obj->buildingQueue = json_decode($obj->buildingQueue, true);
			if(!isset($obj->buildingQueue)) {
				$obj->buildingQueue = array();
			}
			return $obj;
		} else {
			throw new Exception("Invalid objectID");
		}
	}
	
	public function __construct() {
		if($this->buildingQueue < 0) {
			throw new Exception("Invalid objectID - Object should be initiated using static constructor");
		}
		
		$this->getMongoData();
		
		$star = GameCache::get('STARS')[$this->starID];
		$this->envObjectCoord = UniCoord::fromCoord($star['galaxyID'], $star['sectorID'], $star['starIndex'], $this->objectIndex, $this->objectType, $this->objectName, $this->objectImageID);
	}
	
	private function getMongoData() {
		$this->envBuildings = ObjectUtils::getObjectBuildingDataUsingID($this->objectID);
		$this->envResources = ObjectUtils::getObjectResDataUsingID($this->objectID);
		$this->envObjectData = ObjectUtils::getObjectDataUsingID($this->objectID);
	}
	
	public function apply() {
		$result = $GLOBALS['RDBMS']->update(
			tblUNI_OBJECTS, 
			array(
				"objectID" => $this->objectID,
				"objectType" => $this->objectType,
				"objectName" => $this->objectName,
				"ownerID" => $this->ownerID,
				"last_update" => $this->last_update,
				"objectDesc" => $this->objectDesc,
				"buildingQueue" => json_encode($this->buildingQueue)
			),
			"objectID = :objectID",
			array(":objectID" => $this->objectID)
		);
		
		if($result !== false) {
			ObjectUtils::setObjectResDataUsingID($this->objectID, $this->envResources->getResourceArray());
			ObjectUtils::setObjectBuildingDataUsingID($this->objectID, $this->envBuildings->getBuildingArray());
			ObjectUtils::setObjectDataUsingID($this->objectID, $this->envObjectData);
			return true;
		} else {
			throw new Exception("Unknown PDO Error ");
		}
	}
}
?>
