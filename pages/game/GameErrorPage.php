<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class GameErrorPage
 */
class GameErrorPage extends GameAbstractPage {
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
