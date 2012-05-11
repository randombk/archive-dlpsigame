<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

class Page_Notificationwindow extends AbstractPage {
	function __construct() {
		parent::__construct();
        $this->setPageType('win');
	}

	function show() {
		$this->display('win_notifications.tpl');
	}
}