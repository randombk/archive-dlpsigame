<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */
class DataResearch {
	private $researchArray = array();
	
	public function getValue($key) {
		if(isset($this->researchArray[$key])){
			return $this->researchArray[$key];
		} else {
			return 0;
		}
	}
	
	public function getResearch($researchID) {
		if(isset($this->researchArray[$researchID][0])){
			return $this->researchArray[$researchID][0];
		} else {
			return 0;
		}
	}
	
	public function getResearchPoints($researchID) {
		if(isset($this->researchArray[$researchID][1])){
			return $this->researchArray[$researchID][1];
		} else {
			return 0;
		}
	}
	
	public function setResearchLevel($researchID, $researchLevel) {
		if($researchLevel == 0){
			unset($this->researchArray[$researchID]);
		} else {
			$this->researchArray[$researchID] = array($researchLevel, 0);
		}
	}
	
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
	public function getResearchArray() {
		return $this->researchArray;
	}
	
	public function getResearchString() {
		return json_encode($this->researchArray);
	}
	
	public function setResearchArray($data) {
		if($data != null) {
			$this->researchArray = $data;	
		} else {
			$this->researchArray = array();
		}
	}
	
	public function setResearchString($researchString) {
		$this->researchArray = json_decode($researchString, true);
	}
	
	//Constructors
	public static function fromResearchArray($data) {
		$instance = new self();
		$instance->setResearchArray($data);
		return $instance;
	}
	
	public static function fromResearchString($researchString) {
		$instance = new self();
		$instance->getResearchString($researchString);
		return $instance;
	}
}
?>
