<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class DataMod
 */
class DataMod extends Data {
	//Easy access to mod parts to prevent excess re-calculation
	public $objMods = array();
	public $weightPenalty = array();

	/**
	 * @param ObjectEnvironment $objectEnv
	 * @return DataMod
	 */
	public static function calculateObjectModifiers($objectEnv, $buildingFilter = array()) {
		$instance = new self();

		//Add object modifiers
		$instance->objMods = CalcObject::getObjectModifiers($objectEnv);
		$instance->mergeModifierArray($instance->objMods);

		//Add up active building modifiers
		foreach ($objectEnv->envBuildings->getDataArray() as $buildingID => $data) {
			if(isset($buildingFilter[$buildingID])) {
				continue;
			}
			$instance->mergeModifierArray(CalcObject::getBuildingModifiers($objectEnv, $buildingID, $data[0], $data[1]));
		}

		//Determine weight penalties
		$instance->weightPenalty = CalcObject::getObjectWeightPenalty($objectEnv, $instance);
		$instance->mergeModifierArray($instance->weightPenalty);

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
