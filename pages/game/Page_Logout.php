<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class Page_Logout
 */
class Page_Logout extends GameAbstractPage {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();
		$this->setPageType('clean');
	}

	function show() {
		GameSession::DestroySession();
		$this->display('page_logout.tpl');
	}
}