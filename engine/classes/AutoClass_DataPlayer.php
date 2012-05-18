<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */
class DataPlayer {
	private $dataArray = array();
	
	public function getValue($key) {
		if(isset($this->dataArray[$key])){
			return $this->dataArray[$key];
		} else {
			return false;
		}
	}
	
	public function setValue($key, $data) {
		if($data === false){
			unset($this->dataArray[$key]);
		} else {
			$this->dataArray[$key] = $data;
		}
	}
	
	//Basic accessors
	public function getDataArray() {
		return $this->dataArray;
	}
	
	public function getDataString() {
		return json_encode($this->dataArray);
	}
	
	public function setDataArray($data) {
		if($data != null) {
			$this->dataArray = $data;	
		} else {
			$this->dataArray = array();
		}
	}
	
	public function setDataString($dataString) {
		$this->dataArray = json_decode($dataString, true);
	}
	
	//Constructors
	public static function fromDataArray($data) {
		$instance = new self();
		$instance->setDataArray($data);
		return $instance;
	}
	
	public static function fromDataString($dataString) {
		$instance = new self();
		$instance->setDataString($dataString);
		return $instance;
	}
}
?>
