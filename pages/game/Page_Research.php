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
		$objectID = HTTP::REQ("objectID", 0);
		
		if($objectID == 0 || !isset($_SESSION['OBJECTS'][$objectID])) {
			ErrorPage::printError("Invalid object");
		} else {
			if($_SESSION['OBJECTS'][$objectID]->getType() == 1) {
				$_SESSION['CurrentPlanet'] = $_SESSION['OBJECTS'][$objectID];
				$this->templateObj->assign_vars(array(
					 'objectID' => $objectID
				));
				$this->display('page_research.tpl');	
			} else {
				ErrorPage::printError("Invalid object");
			}
		}
	}
}