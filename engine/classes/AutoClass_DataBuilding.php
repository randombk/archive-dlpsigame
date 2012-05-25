<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */
/**
 * Class DataBuilding
 */
class DataBuilding extends Data {
	/**
	 * @param array $data
	 * @return DataBuilding
	 */
	public static function fromBuildingArray($data) {
		$instance = new self();
		$instance->setDataArray($data);
		return $instance;
	}

	/**
	 * @param string $buildingString
	 * @return DataBuilding
	 */
	public static function fromBuildingString($buildingString) {
		$instance = new self();
		$instance->setDataString($buildingString);
		return $instance;
	}

	/**
	 * @param string $buildingName
	 * @return int
	 */
	public function getBuildingLevel($buildingName) {
		if(isset($this->dataArray[$buildingName])){
			return $this->dataArray[$buildingName][0];
		} else {
			return 0;
		}
	}

	/**
	 * @param string $buildingName
	 * @return int
	 */
	public function getBuildingActivity($buildingName) {
		if(isset($this->dataArray[$buildingName])){
			return $this->dataArray[$buildingName][1];
		} else {
			return 0;
		}
	}

	/**
	 * @param string $buildingName
	 * @param int $buildingLevel
	 */
	public function setBuildingLevel($buildingName, $buildingLevel) {
		if($buildingLevel == 0){
			unset($this->dataArray[$buildingName]);
		} else {
			if(isset($this->dataArray[$buildingName])){
				$this->dataArray[$buildingName][0] = $buildingLevel;
			} else {
				$this->dataArray[$buildingName] = array($buildingLevel, 100);
			}
		}
	}

	/**
	 * @param string $buildingName
	 * @param int $activity
	 */
	public function setBuildingActivity($buildingName, $activity) {
		if(isset($this->dataArray[$buildingName])){
			$this->dataArray[$buildingName][1] = $activity;
		}
	}

	/**
	 * @return int
	 */
	public function getNumBuildings() {
		$num = 0;
		foreach ($this->dataArray as $data) {
			$num += $data[0];
		}
		return $num;
	}
}
