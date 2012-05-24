<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class LoginErrorPage
 */
class LoginErrorPage extends LoginAbstractPage {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();
		$this->initTemplate();
	}

	/**
	 * @param string $Message
	 */
	static function printError($Message) {
		$pageObj = new self;
		$pageObj->showMessage($Message);
	}
}
