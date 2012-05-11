<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */
class DataResource {
	private $resArray = array();
	
	public function getRes($resName) {
		if(isset($this->resArray[$resName])){
			return $this->resArray[$resName];
		} else {
			return 0;
		}
	}
	
	public function setRes($resName, $resAmount) {
		if($resAmount == 0){
			unset($this->resArray[$resName]);
		} else {
			$this->resArray[$resName] = $resAmount;
		}
	}
	
	public function getTotalWeight() {
		$weight = 0;
		foreach ($this->resArray as $resID => $number) {
			$weight += GameCache::get("RESOURCES")[$resID]["resWeight"] * $number;
		}
		return $weight;
	}
	
	//Basic accessors
	public function getResourceArray() {
		return $this->resArray;
	}
	
	public function getResourceString() {
		return json_encode($this->resArray);
	}
	
	public function setResourceArray($res) {
		if($res != null) {
			$this->resArray = $res;	
		} else {
			$this->resArray = array();
		}
	}
	
	public function setResourceString($resString) {
		$this->resArray = json_decode($resString, true);
	}
	
	//Constructors
	public static function fromResourceArray($res) {
		$instance = new self();
		$instance->setResourceArray($res);
		return $instance;
	}
	
	public static function fromResourceString($resString) {
		$instance = new self();
		$instance->setResourceString($resString);
		return $instance;
	}
	
	//Math Operators
	public function addRes($resName, $resAmount) {
		$this->setRes($resName, $this->getRes($resName) + $resAmount);
	}
	
	public function sum($res2) {
		if(is_object($res2))
			$res2 = $res2->getResourceArray();	
		
		foreach ($res2 as $key => $value) {
			$this->addRes($key, $value);
		}
		return $this;
	}
	
	public function sub($res2) {
		if(is_object($res2))
			$res2 = $res2->getResourceArray();	
		
		foreach ($res2 as $key => $value) {
			$this->addRes($key, -$value);
		}
		return $this;
	}
	
	public function multiply($factor) {
		foreach ($this->resArray as $key => $value) {
			$this->setRes($key, $value * $factor);
		}
		return $this;
	}
	
	public static function sumRes($res1, $res2) {
		if(is_object($res1))
			$instance = clone $res1;
		else 
			$instance = self::fromResourceArray($res1);
		
		if(is_object($res2))
			$res2 = $res2->getResourceArray();	
		
		foreach ($res2 as $key => $value) {
			$instance->addRes($key, $value);
		}
		return $instance;
	}
	
	public static function diffRes($res1, $res2) {
		if(is_object($res1))
			$instance = clone $res1;
		else 
			$instance = self::fromResourceArray($res1);
			
		if(is_object($res2))
			$res2 = $res2->getResourceArray();	
			
		foreach ($res2 as $key => $value) {
			$instance->addRes($key, -$value);
		}
		return $instance;
	}
	
	public static function isContained($greater, $lesser) {
		if(is_object($greater))
			$greater = $greater->getResourceArray();	
		if(is_object($lesser))
			$lesser = $lesser->getResourceArray();	
		
		$contained = true;
		foreach ($lesser as $key => $value) {
			if($greater[$key] < $value) {
				return false;
			}
		}
		return true;
	}
	
	public function contains($lesser) {
		if(is_object($lesser))
			$lesser = $lesser->getResourceArray();	
		
		foreach ($lesser as $key => $value) {
			if($this->getRes($key) < $value) {
				return false;
			}
		}
		return true;
	}
}
?>
