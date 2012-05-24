<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class Page_Buildings
 */
class Page_Buildings extends GameAbstractPage {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();
	}

	function show() {
		$objectID = $this->updatePlayerCurrentObject();

		if($objectID) {
			$this->display('page_building.tpl');
		} else {
			GameErrorPage::printError("Invalid object");
		}
	}
}
