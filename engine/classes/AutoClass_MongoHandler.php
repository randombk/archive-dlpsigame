<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

class MongoHandler {
	protected $connect;
	protected $database;
	protected $exception;
	
	public function __construct () {
		try {
			$this->connect 	= new MongoClient("mongodb://localhost:27017", array("connect" => TRUE));
			$this->database = $this->connect->{$GLOBALS['_MONGO']['databasename']};
		} catch (Exception $e) {
			throw new Exception("Connection to Mongo failed");
		}
	}
	
	protected function get($collection, $uniqueID) {
		try {
			$data = $collection->findOne(array('_id' => $uniqueID));
			if(isset($data["__EMPTY__"])) {
				return array();
			} else {
				unset($data["_id"]);
				return $data;
			}
		} catch (Exception $e) {
			throw new Exception("Unknown error while querying Mongo");
		}
	}
	
	protected function update($collection, $uniqueID, $content) {
		try {
			$content["_id"] = $uniqueID;
			if(!sizeof($content)) {
				$content["__EMPTY__"] = true;
			}
			return $collection->update(array('_id' => $uniqueID), $content, array('upsert' => true));
		} catch (Exception $e) {
			throw new Exception("Unknown error while updating Mongo");
		}
	}
	
	/*
	 * Items
	 * 
	 * */
	public function collItems() {
		return $this->database->itemData;
	}
	
	public function getItem($uniqueID) {
		try {
			return $this->get($this->collItems(), $uniqueID);
		} catch (Exception $e) {
			throw new Exception("Unknown error");
		}
	}
	
	public function setItem($uniqueID, $itemData) {
		try {
			return $this->update($this->collItems(), $uniqueID, $itemData);
		} catch (Exception $e) {
			throw new Exception("Unknown error");
		}
	}
		
	/*
	 * Buildings
	 * 
	 * */
	public function collBuildings() {
		return $this->database->buildingData;
	}
	
	public function getBuildings($uniqueID) {
		return $this->get($this->collBuildings(), $uniqueID);
	}
	
	public function setBuildings($uniqueID, $buildingData) {
		return $this->update($this->collBuildings(), $uniqueID, $buildingData);
	}
	
	/*
	 * Object Data
	 * 
	 * */
	public function collObject() {
		return $this->database->objectData;
	}
	
	public function getObject($uniqueID) {
		return $this->get($this->collObject(), $uniqueID);
	}
	
	public function setObject($uniqueID, $objectData) {
		return $this->update($this->collObject(), $uniqueID, $objectData);
	}
	
	
	/*
	 * Research Data
	 * 
	 * */
	public function collResearch() {
		return $this->database->researchData;
	}
	
	public function getResearch($uniqueID) {
		return $this->get($this->collResearch(), $uniqueID);
	}
	
	public function setResearch($uniqueID, $researchData) {
		return $this->update($this->collResearch(), $uniqueID, $researchData);
	}
}
