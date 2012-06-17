<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class DataMod
 */
class DataMod extends Data {
	//Easy access to mod components to prevent excess re-calculation
	public $objMods = array();
	public $researchMods = array();
	public $researchQueuePassive = array();
	public $weightPenalty = array();

	/**
	 * @param PlayerEnvironment $playerEnv
	 * @param ObjectEnvironment $objectEnv
	 * @param array $buildingFilter
	 * @return DataMod
	 */
	public static function calculateObjectModifiers($playerEnv, $objectEnv, $buildingFilter = array()) {
		$instance = new self();

		//Add object modifiers
		$instance->objMods = CalcObject::getObjectModifiers($objectEnv);

		//Determine weight penalties
		$instance->weightPenalty = CalcObject::getObjectWeightPenalty($playerEnv, $objectEnv, $instance);

		//Get research queue modifier
		$curResearchQueueItem = QueueResearch::getCurrentResearch($objectEnv);
		if($curResearchQueueItem) {
			$instance->researchQueuePassive = CalcResearch::getResearchNotePassive($playerEnv, $objectEnv, $curResearchQueueItem);
		}

		//Add up active building modifiers
		foreach ($objectEnv->envBuildings->getDataArray() as $buildingID => $data) {
			if(isset($buildingFilter[$buildingID])) {
				continue;
			}
			//No need to cache building modifiers, as they follow a standard, exposed formula
			$instance->mergeModifierArray(CalcObject::getBuildingModifiers($objectEnv, $buildingID, $data[0], $data[1]));
		}

		$instance->mergeModifierArray($instance->objMods);
		$instance->mergeModifierArray($instance->researchQueuePassive);
		$instance->mergeModifierArray($instance->weightPenalty);

		//Cache an updated copy in the object environment
		$objectEnv->envMods = $instance;
		return $instance;
	}

	/**
	 * @param string $modName
	 * @return int
	 */
	public function getMod($modName) {
		if(isset($this->dataArray[$modName])){
			return $this->dataArray[$modName];
		} else {
			return 0;
		}
	}

	/**
	 * @param array $array
	 */
	public function mergeModifierArray($array) {
		if(empty($array)) {
			return;
		}

		foreach ($array as $mod => $val) {
		    $this->dataArray[$mod] = (isset($this->dataArray[$mod]) ? $this->dataArray[$mod] : 0) + $val;
		}
	}
}
