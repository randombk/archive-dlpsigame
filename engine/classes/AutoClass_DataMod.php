<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class DataMod
 */
class DataMod {
	private $modArray = array();
	
	//Easy access to mod parts to prevent excess re-calculation
	public $objMods = array();
	public $weightPenalty = array();

	/**
	 * @param string $modName
	 * @return int
	 */
	public function getMod($modName) {
		if(isset($this->modArray[$modName])){
			return $this->modArray[$modName];
		} else {
			return 0;
		}
	}
	
	/**
	 * @return array
	 */
	public function getModifierArray() {
		return $this->modArray;
	}

	/**
	 * @param array $array
	 */
	public function mergeModifierArray($array) {
		if(!isset($array) || $array == null) { return; }
		
		foreach ($array as $mod => $val) {
		    $this->modArray[$mod] = (isset($this->modArray[$mod]) ? $this->modArray[$mod] : 0) + $val;
		}
	}
	
	//Constructors
	/**
	 * @param ObjectEnvironment $objectEnv
	 * @return DataMod
	 */
	public static function calculateObjectModifiers($objectEnv) {
		$instance = new self();
		
		//Add object modifiers
		$instance->objMods = CalcObject::getObjectModifiers($objectEnv);
		$instance->mergeModifierArray($instance->objMods);
		
		//Add up active building modifiers
		foreach ($objectEnv->envBuildings->getBuildingArray() as $buildingID => $data) {
			$instance->mergeModifierArray(CalcObject::getBuildingModifiers($objectEnv, $buildingID, $data[0], $data[1]));
		}
		
		//Determine weight penalties
		$instance->weightPenalty = CalcObject::getObjectWeightPenalty($objectEnv, $instance);
		$instance->mergeModifierArray($instance->weightPenalty);
		
		$objectEnv->envMods = $instance;
		return $instance;
	}
}