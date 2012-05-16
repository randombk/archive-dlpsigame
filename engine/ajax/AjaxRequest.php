<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

abstract class AjaxRequest {
	function __construct() {	}

	protected function sendJSON($data, $code = 0) {
		if($code != 0) $data['code'] = $code;
		echo json_encode($data);
		exit;
	}
	
	protected function sendCode($code = 0) {
		echo json_encode(array("code" => $code));
		exit;
	}
	
	protected function sendJSONStr($str) {
		echo $str;
		exit;
	}
}