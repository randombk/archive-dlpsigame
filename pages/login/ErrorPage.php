<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

class ErrorPage extends AbstractPage {
	function __construct() {
		parent::__construct();
		$this->initTemplate();
	}

	static function printError($Message) {
		$pageObj = new self;
		$pageObj->showMessage($Message);
	}
}
