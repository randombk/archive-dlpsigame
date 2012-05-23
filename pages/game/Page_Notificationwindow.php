<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class Page_Notificationwindow
 */
class Page_Notificationwindow extends GameAbstractPage {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();
        $this->setPageType('win');
	}

	function show() {
		$this->display('win_notifications.tpl');
	}
}