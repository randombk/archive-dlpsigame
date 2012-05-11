<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

class Page_Rules extends AbstractPage {
	function __construct() {
		parent::__construct();
	}

	function show() {
		$this->render('page_rules.tpl');
	}

}
