<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class Page_Objectoverview
 */
class Page_Objectoverview extends GameAbstractPage {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();
	}

	function show() {
		$objectID = $this->updatePlayerCurrentObject();

		if($objectID) {
			$this->display('page_objectoverview.tpl');
		} else {
			GameErrorPage::printError("Invalid object");
		}
	}
}
