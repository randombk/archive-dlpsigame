<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */
/**
 * Class DataResearch
 */
class DataResearch {
	private $researchArray = array();

	/**
	 * @param $key
	 * @return int
	 */
	public function getValue($key) {
		if(isset($this->researchArray[$key])){
			return $this->researchArray[$key];
		} else {
			return 0;
		}
	}

	/**
	 * @param $researchID
	 * @return int
	 */
	public function getResearchLevel($researchID) {
		if(isset($this->researchArray[$researchID][0])){
			return $this->researchArray[$researchID][0];
		} else {
			return 0;
		}
	}

	/**
	 * @param $researchID
	 * @return int
	 */
	public function getResearchPoints($researchID) {
		if(isset($this->researchArray[$researchID][1])){
			return $this->researchArray[$researchID][1];
		} else {
			return 0;
		}
	}

	/**
	 * @param $researchID
	 * @param $researchLevel
	 */
	public function setResearchLevel($researchID, $researchLevel) {
		if($researchLevel == 0){
			unset($this->researchArray[$researchID]);
		} else {
			$this->researchArray[$researchID] = array($researchLevel, 0);
		}
	}

	/**
	 * @param $researchID
	 * @param $researchPoints
	 */
	public function setResearchPoints($researchID, $researchPoints) {
		if(isset($this->researchArray[$researchID])) {
			if($this->researchArray[$researchID][0] == 0 && $researchPoints == 0) {
				unset($this->researchArray[$researchID]);
			}
			$this->researchArray[$researchID][1] = $researchPoints;
		} else {
			$this->researchArray[$researchID] = array(0, $researchPoints);
		}
	}
	
	//Basic accessors
	/**
	 * @return array
	 */
	public function getResearchArray() {
		return $this->researchArray;
	}

	/**
	 * @return string
	 */
	public function getResearchString() {
		return json_encode($this->researchArray);
	}

	/**
	 * @param $data
	 */
	public function setResearchArray($data) {
		if($data != null) {
			$this->researchArray = $data;	
		} else {
			$this->researchArray = array();
		}
	}

	/**
	 * @param $researchString
	 */
	public function setResearchString($researchString) {
		$this->researchArray = json_decode($researchString, true);
	}
	
	//Constructors
	/**
	 * @param $data
	 * @return DataResearch
	 */
	public static function fromResearchArray($data) {
		$instance = new self();
		$instance->setResearchArray($data);
		return $instance;
	}

	/**
	 * @param $researchString
	 * @return DataResearch
	 */
	public static function fromResearchString($researchString) {
		$instance = new self();
		$instance->setResearchString($researchString);
		return $instance;
	}
	
	//Special actions
	public function resetUnsavedPoints() {
		foreach ($this->researchArray as $research => $data) {
			$this->researchArray[$research][1] = 0;
		}
	}
}
?>
