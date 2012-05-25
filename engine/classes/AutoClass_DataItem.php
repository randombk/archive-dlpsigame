<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */
/**
 * Class DataItem
 */
class DataItem extends Data {
	/**
	 * @param string $item
	 * @return DataItem
	 */
	public static function fromItemArray($item) {
		$instance = new self();
		$instance->setDataArray($item);
		return $instance;
	}

	/**
	 * @param string $itemString
	 * @return DataItem
	 */
	public static function fromItemString($itemString) {
		$instance = new self();
		$instance->setDataString($itemString);
		return $instance;
	}

	/**
	 * @param string $itemName
	 * @return int
	 */
	public function getItem($itemName) {
		if(isset($this->dataArray[$itemName])){
			return $this->dataArray[$itemName];
		} else {
			return 0;
		}
	}

	/**
	 * @param string $itemName
	 * @param int $itemAmount
	 * @return int
	 */
	public function setItem($itemName, $itemAmount) {
		if($itemAmount == 0){
			unset($this->dataArray[$itemName]);
		} else {
			$this->dataArray[$itemName] = $itemAmount;
		}
	}

	/**
	 * @return int
	 */
	public function getTotalWeight() {
		$weight = 0;
		foreach ($this->dataArray as $itemID => $number) {
			$weight += GameCache::get("ITEMS")[UtilItem::getItemBaseID($itemID)]["itemWeight"] * $number;
		}
		return $weight;
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
			$item2 = $item2->getDataArray();

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
			$item2 = $item2->getDataArray();

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
		foreach ($this->dataArray as $key => $value) {
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
			$item2 = $item2->getDataArray();

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
			$item2 = $item2->getDataArray();

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
			$greater = $greater->getDataArray();
		if(is_object($lesser))
			$lesser = $lesser->getDataArray();

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
			$lesser = $lesser->getDataArray();

		foreach ($lesser as $key => $value) {
			if($this->getItem($key) < $value) {
				return false;
			}
		}
		return true;
	}
}
