<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */
/**
 * Class DataPlayer
 */
class DataPlayer {
	private $dataArray = array();

	/**
	 * @param $key
	 * @return bool
	 */
	public function getValue($key) {
		if(isset($this->dataArray[$key])){
			return $this->dataArray[$key];
		} else {
			return false;
		}
	}

	/**
	 * @param $key
	 * @param $data
	 */
	public function setValue($key, $data) {
		if($data === false){
			unset($this->dataArray[$key]);
		} else {
			$this->dataArray[$key] = $data;
		}
	}
	
	//Basic accessors
	/**
	 * @return array
	 */
	public function getDataArray() {
		return $this->dataArray;
	}

	/**
	 * @return string
	 */
	public function getDataString() {
		return json_encode($this->dataArray);
	}

	/**
	 * @param $data
	 */
	public function setDataArray($data) {
		if($data != null) {
			$this->dataArray = $data;	
		} else {
			$this->dataArray = array();
		}
	}

	/**
	 * @param $dataString
	 */
	public function setDataString($dataString) {
		$this->dataArray = json_decode($dataString, true);
	}
	
	//Constructors
	/**
	 * @param $data
	 * @return DataPlayer
	 */
	public static function fromDataArray($data) {
		$instance = new self();
		$instance->setDataArray($data);
		return $instance;
	}

	/**
	 * @param $dataString
	 * @return DataPlayer
	 */
	public static function fromDataString($dataString) {
		$instance = new self();
		$instance->setDataString($dataString);
		return $instance;
	}
}
?>
