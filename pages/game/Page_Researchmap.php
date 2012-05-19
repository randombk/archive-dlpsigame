<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

class Page_Researchmap extends AbstractPage {
	function __construct() {
		parent::__construct();
	}

	function show() {
		$this->display('page_researchmap.tpl');
	}
}