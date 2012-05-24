<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class Page_Research
 */
class Page_Research extends GameAbstractPage {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();
	}

	function show() {
		$objectID = $this->updatePlayerCurrentObject();

		if($objectID) {
			$this->display('page_research.tpl');
		} else {
			GameErrorPage::printError("Invalid object");
		}
	}
}
