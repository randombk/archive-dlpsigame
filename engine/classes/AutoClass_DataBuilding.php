<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */
/**
 * Class DataBuilding
 */
class DataBuilding {
	private $buildingArray = array();

	/**
	 * @param $key
	 * @return int
	 */
	public function getValue($key) {
		if(isset($this->buildingArray[$key])){
			return $this->buildingArray[$key];
		} else {
			return 0;
		}
	}

	/**
	 * @param $buildingName
	 * @return int
	 */
	public function getBuildingLevel($buildingName) {
		if(isset($this->buildingArray[$buildingName])){
			return $this->buildingArray[$buildingName][0];
		} else {
			return 0;
		}
	}

	/**
	 * @param $buildingName
	 * @return int
	 */
	public function getBuildingActivity($buildingName) {
		if(isset($this->buildingArray[$buildingName])){
			return $this->buildingArray[$buildingName][1];
		} else {
			return 0;
		}
	}

	/**
	 * @param $buildingName
	 * @param $buildingLevel
	 */
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

	/**
	 * @param $buildingName
	 * @param $activity
	 */
	public function setBuildingActivity($buildingName, $activity) {
		if(isset($this->buildingArray[$buildingName])){
			$this->buildingArray[$buildingName][1] = $activity;
		}
	}

	/**
	 * @return int
	 */
	public function getNumBuildings() {
		$num = 0;
		foreach ($this->buildingArray as $data) {
			$num += $data[0];
		}
		return $num;
	}
	
	//Basic accessors
	/**
	 * @return array
	 */
	public function getBuildingArray() {
		return $this->buildingArray;
	}

	/**
	 * @return string
	 */
	public function getBuildingString() {
		return json_encode($this->buildingArray);
	}

	/**
	 * @param $data
	 */
	public function setBuildingArray($data) {
		if($data != null) {
			$this->buildingArray = $data;	
		} else {
			$this->buildingArray = array();
		}
	}

	/**
	 * @param $buildingString
	 */
	public function setBuildingString($buildingString) {
		$this->buildingArray = json_decode($buildingString, true);
	}
	
	//Constructors
	/**
	 * @param $data
	 * @return DataBuilding
	 */
	public static function fromBuildingArray($data) {
		$instance = new self();
		$instance->setBuildingArray($data);
		return $instance;
	}

	/**
	 * @param $buildingString
	 * @return DataBuilding
	 */
	public static function fromBuildingString($buildingString) {
		$instance = new self();
		$instance->setBuildingString($buildingString);
		return $instance;
	}
}
?>
