<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */
class DataBuilding {
	private $buildingArray = array();
	
	public function getValue($key) {
		if(isset($this->buildingArray[$key])){
			return $this->buildingArray[$key];
		} else {
			return 0;
		}
	}
	
	public function getBuildingLevel($buildingName) {
		if(isset($this->buildingArray[$buildingName])){
			return $this->buildingArray[$buildingName][0];
		} else {
			return 0;
		}
	}
	
	public function getBuildingActivity($buildingName) {
		if(isset($this->buildingArray[$buildingName])){
			return $this->buildingArray[$buildingName][1];
		} else {
			return 0;
		}
	}
	
	public function setBuildingLevel($buildingName, $buildingLevel) {
		if($buildingLevel == 0){
			unset($this->buildingArray[$buildingName]);
		} else {
			if(isset($this->buildingArray[$buildingName])){
				$this->buildingArray[$buildingName][0] = $buildingLevel;
			} else {
				$this->buildingArray[$buildingName] = array($buildingLevel, 100);
			}
		}
	}
	
	public function setBuildingActivity($buildingName, $activity) {
		if(isset($this->buildingArray[$buildingName])){
			$this->buildingArray[$buildingName][1] = $activity;
		}
	}
	
	public function getNumBuildings() {
		$num = 0;
		foreach ($this->buildingArray as $buildID => $data) {
			$num += $data[0];
		}
		return $num;
	}
	
	//Basic accessors
	public function getBuildingArray() {
		return $this->buildingArray;
	}
	
	public function getBuildingString() {
		return json_encode($this->buildingArray);
	}
	
	public function setBuildingArray($data) {
		if($data != null) {
			$this->buildingArray = $data;	
		} else {
			$this->buildingArray = array();
		}
	}
	
	public function setBuildingString($buildingString) {
		$this->buildingArray = json_decode($buildingString, true);
	}
	
	//Constructors
	public static function fromBuildingArray($data) {
		$instance = new self();
		$instance->setBuildingArray($data);
		return $instance;
	}
	
	public static function fromBuildingString($buildingString) {
		$instance = new self();
		$instance->setBuildingString($buildingString);
		return $instance;
	}
}
?>
