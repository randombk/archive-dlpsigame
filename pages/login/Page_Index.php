<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class Page_Index
 */
class Page_Index extends LoginAbstractPage {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();
		$this->setPageType('clean');
	}

	function show() {
		$Code = HTTP::REQ('code', 0);
		$loginCode = false;

		switch ($Code) {
			case 1: $loginCode = 'Wrong username/password!'; break;
			case 2: $loginCode = 'You need to verify your email before logging in.'; break;
			case 3: $loginCode = 'Your session has expired!'; break;
			case 4: $loginCode = 'Logged out.'; break;
		}

		$this->assign(array(
			 'code' => $loginCode,
			 'loginInfo' => 'By logging in, I agree with the <a href="index.php?page=rules">Rules</a>'
		));

		$this->render('page_index.tpl');
	}
}