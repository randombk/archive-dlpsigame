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
	 * @param $Message
	 */
	static function printError($Message) {
		$pageObj = new self;
		$pageObj->showMessage($Message);
	}
}
