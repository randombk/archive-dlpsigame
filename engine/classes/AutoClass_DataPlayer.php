<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */
/**
 * Class DataPlayer
 */
class DataPlayer extends Data {
	/**
	 * @param string $key
	 * @return mixed
	 */
	public function getValue($key) {
		if(isset($this->dataArray[$key])){
			return $this->dataArray[$key];
		} else {
			return false;
		}
	}
}
