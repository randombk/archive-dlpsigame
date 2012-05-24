<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */
/**
 * Class DataItem
 */
class DataItem {
	private $itemArray = array();

	/**
	 * @param string $itemName
	 * @return int
	 */
	public function getItem($itemName) {
		if(isset($this->itemArray[$itemName])){
			return $this->itemArray[$itemName];
		} else {
			return 0;
		}
	}

	/**
	 * @param string $itemName
	 * @param int $itemAmount
	 */
	public function setItem($itemName, $itemAmount) {
		if($itemAmount == 0){
			unset($this->itemArray[$itemName]);
		} else {
			$this->itemArray[$itemName] = $itemAmount;
		}
	}

	/**
	 * @return int
	 */
	public function getTotalWeight() {
		$weight = 0;
		foreach ($this->itemArray as $itemID => $number) {
			$weight += GameCache::get("ITEMS")[UtilItem::getItemBaseID($itemID)]["itemWeight"] * $number;
		}
		return $weight;
	}
	
	/**
	 * @return array
	 */
	public function getItemArray() {
		return $this->itemArray;
	}

	/**
	 * @return string
	 */
	public function getItemString() {
		return json_encode($this->itemArray);
	}

	/**
	 * @param array $item
	 */
	public function setItemArray($item) {
		if($item != null) {
			$this->itemArray = $item;	
		} else {
			$this->itemArray = array();
		}
	}

	/**
	 * @param string $itemString
	 */
	public function setItemString($itemString) {
		$this->itemArray = json_decode($itemString, true);
	}
	
	//Constructors
	/**
	 * @param string $item
	 * @return DataItem
	 */
	public static function fromItemArray($item) {
		$instance = new self();
		$instance->setItemArray($item);
		return $instance;
	}

	/**
	 * @param string $itemString
	 * @return DataItem
	 */
	public static function fromItemString($itemString) {
		$instance = new self();
		$instance->setItemString($itemString);
		return $instance;
	}
	
	//Math Operators
	/**
	 * @param string $itemName
	 * @param int $itemAmount
	 */
	public function addItem($itemName, $itemAmount) {
		$this->setItem($itemName, $this->getItem($itemName) + $itemAmount);
	}

	/**
	 * @param DataItem $item2
	 * @return DataItem
	 */
	public function sum($item2) {
		if(is_object($item2))
			$item2 = $item2->getItemArray();	
		
		foreach ($item2 as $key => $value) {
			$this->addItem($key, $value);
		}
		return $this;
	}

	/**
	 * @param DataItem $item2
	 * @return DataItem
	 */
	public function sub($item2) {
		if(is_object($item2))
			$item2 = $item2->getItemArray();	
		
		foreach ($item2 as $key => $value) {
			$this->addItem($key, -$value);
		}
		return $this;
	}

	/**
	 * @param float $factor
	 * @return DataItem
	 */
	public function multiply($factor) {
		foreach ($this->itemArray as $key => $value) {
			$this->setItem($key, $value * $factor);
		}
		return $this;
	}

	/**
	 * @param DataItem $item1
	 * @param DataItem $item2
	 * @return DataItem
	 */
	public static function sumRes($item1, $item2) {
		if(is_object($item1))
			$instance = clone $item1;
		else 
			$instance = self::fromItemArray($item1);
		
		if(is_object($item2))
			$item2 = $item2->getItemArray();	
		
		foreach ($item2 as $key => $value) {
			$instance->addItem($key, $value);
		}
		return $instance;
	}

	/**
	 * @param DataItem
	 * @param DataItem
	 * @return DataItem
	 */
	public static function diffRes($item1, $item2) {
		if(is_object($item1))
			$instance = clone $item1;
		else 
			$instance = self::fromItemArray($item1);
			
		if(is_object($item2))
			$item2 = $item2->getItemArray();	
			
		foreach ($item2 as $key => $value) {
			$instance->addItem($key, -$value);
		}
		return $instance;
	}

	/**
	 * @param DataItem $greater
	 * @param DataItem $lesser
	 * @return bool
	 */
	public static function isContained($greater, $lesser) {
		if(is_object($greater))
			$greater = $greater->getItemArray();	
		if(is_object($lesser))
			$lesser = $lesser->getItemArray();	
		
		foreach ($lesser as $key => $value) {
			if($greater[$key] < $value) {
				return false;
			}
		}
		return true;
	}

	/**
	 * @param DataItem $lesser
	 * @return bool
	 */
	public function contains($lesser) {
		if(is_object($lesser))
			$lesser = $lesser->getItemArray();	
		
		foreach ($lesser as $key => $value) {
			if($this->getItem($key) < $value) {
				return false;
			}
		}
		return true;
	}
}