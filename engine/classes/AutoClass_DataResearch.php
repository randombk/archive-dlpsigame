<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */
/**
 * Class DataResearch
 */
class DataResearch extends Data {

	/**
	 * @param array $data
	 * @return DataResearch
	 */
	public static function fromResearchArray($data) {
		$instance = new self();
		$instance->setDataArray($data);
		return $instance;
	}

	/**
	 * @param string $researchString
	 * @return DataResearch
	 */
	public static function fromResearchString($researchString) {
		$instance = new self();
		$instance->setDataString($researchString);
		return $instance;
	}

	/**
	 * @param string $researchID
	 * @return int
	 */
	public function getResearchLevel($researchID) {
		if(isset($this->dataArray[$researchID][0])){
			return $this->dataArray[$researchID][0];
		} else {
			return 0;
		}
	}

	/**
	 * @param string $researchID
	 * @return int
	 */
	public function getResearchPoints($researchID) {
		if(isset($this->dataArray[$researchID][1])){
			return $this->dataArray[$researchID][1];
		} else {
			return 0;
		}
	}

	/**
	 * @param string $researchID
	 * @param int $researchLevel
	 */
	public function setResearchLevel($researchID, $researchLevel) {
		if($researchLevel == 0){
			unset($this->dataArray[$researchID]);
		} else {
			$this->dataArray[$researchID] = array($researchLevel, 0);
		}
	}

	/**
	 * @param string $researchID
	 * @param int $researchPoints
	 */
	public function setResearchPoints($researchID, $researchPoints) {
		if(isset($this->dataArray[$researchID])) {
			if($this->dataArray[$researchID][0] == 0 && $researchPoints == 0) {
				unset($this->dataArray[$researchID]);
			}
			$this->dataArray[$researchID][1] = $researchPoints;
		} else {
			$this->dataArray[$researchID] = array(0, $researchPoints);
		}
	}

	//Special actions
	public function resetUnsavedPoints() {
		foreach ($this->dataArray as $research => $data) {
			$this->dataArray[$research][1] = 0;
		}
	}
}
