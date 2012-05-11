<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

class AjaxError extends AjaxRequest {
	function __construct() {
		parent::__construct();
	}

	static function sendError($Message, $code = -1) {
		parent::sendJSON(array("message" => $Message), $code);
	}
}
