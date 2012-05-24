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
	 * @param string $key
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
	 * @param string $key
	 * @param mixed $data
	 */
	public function setValue($key, $data) {
		if($data === false){
			unset($this->dataArray[$key]);
		} else {
			$this->dataArray[$key] = $data;
		}
	}
	
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
	 * @param array $data
	 */
	public function setDataArray($data) {
		if($data != null) {
			$this->dataArray = $data;	
		} else {
			$this->dataArray = array();
		}
	}

	/**
	 * @param string $dataString
	 */
	public function setDataString($dataString) {
		$this->dataArray = json_decode($dataString, true);
	}
	
	//Constructors
	/**
	 * @param array $data
	 * @return DataPlayer
	 */
	public static function fromDataArray($data) {
		$instance = new self();
		$instance->setDataArray($data);
		return $instance;
	}

	/**
	 * @param string $dataString
	 * @return DataPlayer
	 */
	public static function fromDataString($dataString) {
		$instance = new self();
		$instance->setDataString($dataString);
		return $instance;
	}
}
