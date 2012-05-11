<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

class Page_Invwindow extends AbstractPage {
	function __construct() {
		parent::__construct();
        $this->setPageType('win');
	}

	function show() {
		$objectID = HTTP::REQ("objectID", 0);
		
		if($objectID == 0 || !isset($_SESSION['OBJECTS'][$objectID])) {
			$objectID = -1;
		}
		
		$this->templateObj->assign_vars(array(
			 'objectID' => $objectID
		));
		$this->display('win_inventory.tpl');	
	}
}