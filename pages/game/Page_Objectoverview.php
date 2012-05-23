<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class Page_Objectoverview
 */
class Page_Objectoverview extends GameAbstractPage {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();
	}

	function show() {
		$objectID = HTTP::REQ("objectID", 0);
		
		if($objectID == 0 || !isset($_SESSION['OBJECTS'][$objectID])) {
			GameErrorPage::printError("Invalid object");
		} else {
			if($_SESSION['OBJECTS'][$objectID]->getType() == 1) {
				$_SESSION['CurrentPlanet'] = $_SESSION['OBJECTS'][$objectID];
				$this->templateObj->assign_vars(array(
					 'objectID' => $objectID,
					 'objectName' => $_SESSION['OBJECTS'][$objectID]->getName(),
					 'objectGalaxy'	=> $_SESSION['OBJECTS'][$objectID]->getGalaxy(),
					 'objectSector'	=> $_SESSION['OBJECTS'][$objectID]->getSector(),
					 'objectStar'	=> $_SESSION['OBJECTS'][$objectID]->getStar(),
					 'objectObject'	=> $_SESSION['OBJECTS'][$objectID]->getObject(),
					 'objectTypeName' => $_SESSION['OBJECTS'][$objectID]->getTypeName()
				));
				$this->display('page_objectoverview.tpl');	
			} else {
				GameErrorPage::printError("Invalid object");
			}
		}
	}
}