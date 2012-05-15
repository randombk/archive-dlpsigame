<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

//NONOPTIMAL: Handle MySQL errors instead of performing manual error checking
class UtilObject {
	
	static function getStarID($Position) {
		if(!is_null($Position) && $Position instanceof UniCoord && $Position->isOK()) {
			$PosString = ''.$Position->getGalaxy().'.'.$Position->getSector().'.'.$Position->getStar();
			
			if(isset(GameCache::get('STARPOS')[$PosString])) {
				return GameCache::get('STARPOS')[$PosString]['starID'];
			} else {
				return 0;
			}
		} else {
			throw new Exception("Invalid Parameter - Needs to be UniCoord: $Position");
		}
	}
	
	static function getObjectID($Position) {
		if(!is_null($Position) && $Position instanceof UniCoord && $Position->isOK()) {
			$starID = self::getStarID($Position);
			if($starID == 0) {
				throw new Exception("Invalid Parameter - Star ID not valid");
			}
			
			$object = $GLOBALS["RDBMS"]->selectTop(
				tblUNI_OBJECTS,
				"starID = :starID AND objectIndex = :objectID",
				array(
					":starID" => $starID,
					":objectID" => $Position->getObject()
				),
				"objectID"
			);
			
			if(isset($object)) {
				return $object['objectID'];
			} else {
				return 0;
			}
		} else {
			throw new Exception("Invalid Parameter - Needs to be UniCoord: $Position");
		}
	}
	
	static function getFreeObjectCoord($starID, $type = 1, $name = "Colony") {
		if(isset(GameCache::get('STARS')[$starID])) {
			//SELECT objectIndex FROM dlpsigame_dev1.uniobjects WHERE starID = 1 ORDER BY objectIndex DESC LIMIT 1;
			
			$star = GameCache::get('STARS')[$starID];
			$objectIndex = $GLOBALS["RDBMS"]->selectCell(
				tblUNI_OBJECTS,
				"starID = :starID",
				array(
					":starID" => $starID
				),
				"objectIndex",
				"ORDER BY objectIndex DESC LIMIT 1"
			);
			
			if($objectIndex == null) {
				$objectIndex = 0;
			}
			
			return UniCoord::fromCoord($star["galaxyID"], $star["sectorID"], $star['starIndex'], $objectIndex + 1, $type, $name);
		} else {
			throw new Exception("Invalid Parameter - Not a valid star ID");
		}
		
		if(!is_null($Position) && $Position instanceof UniCoord && $Position->isOK()) {
			$starID = self::getStarID($Position);
			if($starID == 0) {
				return 0;
			}
			
			$object = $GLOBALS["RDBMS"]->selectTop(
				tblUNI_OBJECTS,
				"starID = :starID AND objectIndex = :objectID",
				array(
					":starID" => $starID,
					":objectID" => $Position->getObject()
				),
				"objectID"
			);
			
			if(isset($object)) {
				return $object['objectID'];
			} else {
				return 0;
			}
		} else {
			throw new Exception("Invalid Parameter - Needs to be UniCoord: $Position");
		}
	}
	
	//Return starID if OK
	static function isPositionFree($Position) {
		if(!is_null($Position) && $Position instanceof UniCoord && $Position->isOK()) {
			$starID = self::getStarID($Position);
			if($starID == 0) {
				return false;
			}
			
			if(self::getObjectID($Position) == 0) {
				return $starID;
			} else {
				return false;
			}
		} else {
			throw new Exception("Invalid Parameter - Needs to be UniCoord: $Position");
		}
	}
	
	static function generatePlanetType(&$temp, &$humidity, &$size, &$randSeed, $distance) {
		
		//Have a small chance at making the planet volcanic
		if($randSeed >= 990) {
			//Make the planet volcanic
			$temp = max($temp, 60) + max(Math::nDn(4, 16), Math::nDn(4, 5) + 14);
			$humidity = 0;
			$size = max($size, 350+Math::nDn(6, 10));
			return "Volcanic";
		}
		
		//Hanve a small chance at making the planet a dwarf
		if($randSeed <= 10) {
			//Make the planet a dwarf planet
			$temp -= Math::nDn(3, 13);
			$humidity = 0;
			$size = min($size, 150-Math::nDn(3, 20));
			return "Dwarf";
		}
		
		if($size >= 200 && $size <= 350 && $humidity >= 30 && $humidity <= 70 && $temp >= 0 && $temp <= 40) {
			if($randSeed < 20) {
				return "Oasis";
			}
			return "Temperate";
		}
		
		if($temp < 0 && $humidity > 70) {
			return "Icy";
		}
		
		if($temp > 0 && $humidity > 70) {
			return "Ocean";
		}
		
		if(($temp > 40 && $humidity <= 20) || $humidity == 0) {
			return "Desert";
		}
		
		return "Rocky";
	}
		
	static function createPlanet($Object, $PlanetOwnerID) {
		if(!is_null($Object) && get_class($Object) == "UniCoord" && $Object->isOK()) {
			$starID = self::isPositionFree($Object);
			if($starID === false) {
				return false;
			}
			
			//HARDCODED: Planet data ranges
			$ObjectImageID = mt_rand(1,10);
			$r = Math::nDn(3, 33);
			$theta = Math::mt_randf(0, 2*M_PI);
			
			//Generate planet data
			$planetTemp = (int)(- (0.001 * pow($r, 3)) + (0.141 * pow($r, 2)) - (7 * $r) + 150 + Math::nDn(6, 6) - 18);
			$planetHumidity = (int)(max(0, Math::nDn(4, 31) - $planetTemp + 15 ));
			if($planetTemp > 80)
				$planetHumidity = 0;
			$planetSize = (int)(mt_rand(0, 100) <= 40 ? Math::nDn(20, 20) + 9 + Math::nDn(12, 18) : Math::nDn(20, 20));
			$randSeed = mt_rand(0, 1000);
		
			$planetType = self::generatePlanetType($planetTemp, $planetHumidity, $planetSize, $randSeed, $r);
			
			$objectData = array(
				"planetTemp" => $planetTemp,
				"planetHumidity" => $planetHumidity,
				"planetSize" => $planetSize,
				"planetType" => $planetType,
				"randSeed"	 => $randSeed
			);
			
			$newID = $GLOBALS["RDBMS"]->insert(
				tblUNI_OBJECTS,
				array(
					"starID" => $starID,
					"objectIndex" => $Object->getObject(),
					"objectType" => $Object->getType(),
					"objectName" => $Object->getName(),
					"objectImageID" => $ObjectImageID,
					"ownerID" => $PlanetOwnerID,
					"last_update" => TIMESTAMP,
					"r" => $r,
					"theta" => $theta
				),
				true
			);
			
			self::setObjectResDataUsingID($newID, array(), false);
			self::setObjectBuildingDataUsingID($newID, array(), false);
			self::setObjectDataUsingID($newID, $objectData, false);
			return $newID;
		} else {
			throw new Exception("Invalid Parameter - Needs to be UniCoord: $Object");
		}
	}

	static function deleteObject($objectID) {
		return $GLOBALS['RDBMS']->prepare("
			DELETE
			FROM " . tblUNI_OBJECTS . " 
			WHERE 
				objectID	= :objectID
		;")->execute(array(":objectID" => $objectID));
	}
	
	//Data functions
	
	//
	// Items
	//
	static function setObjectResDataUsingLoc($Position, $itemData) {
		if(!is_null($Position) && $Position instanceof UniCoord && $Position->isOK()) {
			$objID = self::getObjectID($Position);
			if($objID == 0) {
				return 0;
			}
			
			return self::setObjectResDataUsingID($objID, $itemData, false);
		} else {
			throw new Exception("Invalid Parameter - Needs to be UniCoord: $Position");
		}
	}
	
	static function setObjectResDataUsingID($objectID, $itemData, $checkID = true) {
		if($checkID) {
			if(is_null(UniCoord::fromObjectID($objectID))) {
				throw new Exception("Invalid ID - Object does not exist: $objectID");
			}
		}
		return $GLOBALS['MONGO']->setItem("objectItem_".$objectID, $itemData);
	}
	
	static function getObjectResDataUsingLoc($Position) {
		if(!is_null($Position) && $Position instanceof UniCoord && $Position->isOK()) {
			$objID = self::getObjectID($Position);
			if($objID == 0) {
				return 0;
			}
			
			return self::getObjectResDataUsingID($objID);
		} else {
			throw new Exception("Invalid Parameter - Needs to be UniCoord: $Position");
		}
	}
	
	static function getObjectResDataUsingID($objectID) {
		return DataItem::fromItemArray($GLOBALS['MONGO']->getItem("objectItem_".$objectID));
	}
	
	//
	// Buildings
	//
	static function setObjectBuildingDataUsingLoc($Position, $buildingData) {
		if(!is_null($Position) && $Position instanceof UniCoord && $Position->isOK()) {
			$objID = self::getObjectID($Position);
			if($objID == 0) {
				return 0;
			}
			
			return self::setObjectBuildingDataUsingID($objID, $buildingData);
		} else {
			throw new Exception("Invalid Parameter - Needs to be UniCoord: $Position");
		}
	}
	
	static function setObjectBuildingDataUsingID($objectID, $buildingData) {
		return $GLOBALS['MONGO']->setBuildings("objectBuildings_".$objectID, $buildingData);
	}
	
	static function getObjectBuildingDataUsingLoc($Position) {
		if(!is_null($Position) && $Position instanceof UniCoord && $Position->isOK()) {
			$objID = self::getObjectID($Position);
			if($objID == 0) {
				return 0;
			}
			
			return self::getObjectBuildingDataUsingID($objID);
		} else {
			throw new Exception("Invalid Parameter - Needs to be UniCoord: $Position");
		}
	}
	
	static function getObjectBuildingDataUsingID($objectID) {
		return DataBuilding::fromBuildingArray($GLOBALS['MONGO']->getBuildings("objectBuildings_".$objectID));
	}
	
	//
	// Object Data
	//
	static function setObjectDataUsingLoc($Position, $objectData) {
		if(!is_null($Position) && $Position instanceof UniCoord && $Position->isOK()) {
			$objID = self::getObjectID($Position);
			if($objID == 0) {
				return 0;
			}
			
			return self::setObjectDataUsingID($objID, $objectData);
		} else {
			throw new Exception("Invalid Parameter - Needs to be UniCoord: $Position");
		}
	}
	
	static function setObjectDataUsingID($objectID, $objectData) {
		return $GLOBALS['MONGO']->setObject("objectData_".$objectID, $objectData);
	}
	
	static function getObjectDataUsingLoc($Position) {
		if(!is_null($Position) && $Position instanceof UniCoord && $Position->isOK()) {
			$objID = self::getObjectID($Position);
			if($objID == 0) {
				return 0;
			}
			
			return self::getObjectDataUsingID($objID);
		} else {
			throw new Exception("Invalid Parameter - Needs to be UniCoord: $Position");
		}
	}
	
	static function getObjectDataUsingID($objectID) {
		return $GLOBALS['MONGO']->getObject("objectData_".$objectID);
	}
}
?>
