<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class AjaxRequest
 */
abstract class AjaxRequest {
	/**
	 *
	 */
	function __construct() {	}

	/**
	 * @param array $data
	 * @param int $code
	 */
	protected function sendJSON($data, $code = 0) {
		if($code != 0) $data['code'] = $code;
		echo json_encode($data);
		exit;
	}

	/**
	 * @param int $code
	 */
	protected function sendCode($code = 0) {
		echo json_encode(array("code" => $code));
		exit;
	}

	/**
	 * @param string $str
	 */
	protected function sendJSONStr($str) {
		echo $str;
		exit;
	}
}