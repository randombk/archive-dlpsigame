<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */
//Custom datatype for spcifying locations and basic information about the stuff in them
class UniCoord {
	protected $objGalaxy 	= 0;
	protected $objSector 	= 0;
	protected $objStar		= 0;
	protected $objObject 	= 0;

	/* 
	 * Object Types:
	 *		1 -> Habitable Planet
	 */
	protected $objName		= 0;
	protected $objType		= 0;
	protected $objID		= 0;
	protected $objStarID	= 0;
	protected $objImageID	= 0;
	
	protected $OK			= true;
	
	//Objects only
	public static function fromObjectID($id) {
		$instance = new self();
		$instance->getObjectData($id, false);
		if(!$instance->OK)
			return null;
		return $instance;
	}
	
	//Objects only
	public static function fromStarID($id) {
		$instance = new self();
		$instance->getObjectData($id, true);
		if(!$instance->OK)
			return null;
		return $instance;
	}
	
	public static function fromCoord($Galaxy, $Sector, $Star, $Object, $Type=0, $Name="Object", $ImageID = 0) {
		$instance = new self();
		$instance->objGalaxy = $Galaxy;
		$instance->objSector = $Sector;
		$instance->objStar = $Star;
		$instance->objObject = $Object;
		$instance->objType = $Type;
		$instance->objName = $Name;
		$instance->objImageID = $ImageID;
		return $instance;
	}
	
	public static function fromData($objectData) {
		$instance = new self();
		$star = GameCache::get('STARS')[$objectData['starID']];
		$instance->objID		= $objectData['objectID'];
		$instance->objGalaxy	= $star['galaxyID'];
		$instance->objSector	= $star['sectorID'];
		$instance->objStar		= $star['starIndex'];
		$instance->objObject	= $objectData['objectIndex'];
		$instance->objType		= $objectData['objectType'];
		$instance->objName		= $objectData['objectName'];
		$instance->objImageID	= $objectData['objectImageID'];
		$instance->objStarID	= $objectData['starID'];
		return $instance;
	}
	
	//if isStar is true, select implicit star object
	//else select the object		
	public function getObjectData($id, $isStar = false) {
		$this->objID = $id;
		if($isStar) {
			$starData = GameCache::get('STARS')[$id];
			if(isset($starData)){
				$this->objGalaxy		= $starData['galaxyID'];
				$this->objSector		= $starData['sectorID'];
				$this->objStar			= $starData['starIndex'];
				$this->objStarID		= $id;
			} else {
				$this->OK = false;
			}
		} else {
			$objectData = $GLOBALS['RDBMS']->selectTop(
				tblUNI_OBJECTS,
				"objectID = :objectID",
				array(":objectID" => $id),
				"objectIndex, objectType, objectName, objectImageID, starID"
			);
			
			if(isset($objectData)){
				$star = GameCache::get('STARS')[$objectData['starID']];
				$this->objGalaxy	= $star['galaxyID'];
				$this->objSector	= $star['sectorID'];
				$this->objStar		= $star['starIndex'];
				$this->objObject	= $objectData['objectIndex'];
				$this->objType		= $objectData['objectType'];
				$this->objName		= $objectData['objectName'];
				$this->objImageID	= $objectData['objectImageID'];
				$this->objStarID	= $objectData['starID'];
			} else {
				$this->OK = false;
			}
		}
	}

	public function getGalaxy() {
		return $this->objGalaxy;
	}
	
	public function getSector() {
		return $this->objSector;
	}
	
	public function getStar() {
		return $this->objStar;
	}
	
	public function getObject() {
		return $this->objObject;
	}
	
	public function getName() {
		return $this->objName;
	}
	
	public function getCoordString() {
		return ''.$this->objGalaxy.'.'.$this->objSector.'.'.$this->objStar.'.'.$this->objObject;
	}
	
	public function getType() {
		return $this->objType;
	}
	
	public function getTypeName() {
		switch($this->objType) {
			case 1:
				return "Planet";
			default:
				return "Object";						
		}
	}
	
	public function getObjectID() {
		return $this->objID;
	}
	
	public function getStarID() {
		return $this->objStarID;
	}
	
	public function getImageID() {
		return $this->objImageID;
	}
	
	public function isOK() {
		return $this->OK;
	}
}
?>
