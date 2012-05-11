<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

class Page_Research extends AbstractPage {
	function __construct() {
		parent::__construct();
	}

	function show() {
		$this->display('page_research.tpl');
	}
}