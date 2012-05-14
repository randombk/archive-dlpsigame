<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

class ResearchCalc {
	//Get list of research points
	
	public static function getStarResearchPoints($playerEnv) {
		
		
		
		if($mod == null) $mod = DataMod::calculateObjectModifiers($objectEnv);
		
		$points = array(
			"Weapons" 	=> 0,
			"Defense" 	=> 0,
			"Diplomatic"=> 0,
			"Economic" 	=> 0,
			"Fleet" 	=> 0
		);
		
		//Add up active building modifiers
		foreach ($objectEnv->envBuildings->getBuildingArray() as $buildingID => $data) {
			foreach(ObjectCalc::getBuildingResearch($objectEnv, $buildingID, $data[0], $mod, $data[1]) as $type => $value) {
				$points[$type] += $value;
			}
		}
		
		return $points;
	}
}

?>
