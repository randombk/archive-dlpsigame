<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class Page_Researchmap
 */
class Page_Researchmap extends GameAbstractPage {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();
	}

	function show() {
		$techID = HTTP::REQ("techID", "tech1");
		
		$this->templateObj->assign_vars(array(
			 'techID' => $techID
		));
		$this->display('page_researchmap.tpl');
	}
}