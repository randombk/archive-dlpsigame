<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */
class DataResearch {
	private $researchArray = array();
	
	public function getResearch($researchID) {
		if(isset($this->researchArray[$researchID])){
			return $this->researchArray[$researchID];
		} else {
			return 0;
		}
	}
	
	public function setResearch($researchID, $researchLevel) {
		if($researchLevel == 0){
			unset($this->researchArray[$researchID]);
		} else {
			$this->researchArray[$researchID] = $researchLevel;
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
