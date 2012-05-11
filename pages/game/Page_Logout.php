<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

class Page_Logout extends AbstractPage {
	function __construct() {
		parent::__construct();
		$this->setPageType('clean');
	}

	function show() {
		GameSession::DestroySession();
		$this->display('page_logout.tpl');
	}
}