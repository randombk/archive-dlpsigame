<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

abstract class AbstractPage {
	protected $window;
	
	protected function __construct() {
		$this->setPageType('full');
		$this->initTemplate();
	}

	protected function initTemplate() {
		if (isset($this->templateObj))
			return true;

		$this->templateObj = new SmartyWrapper;
		list($tplDir) = $this->templateObj->getTemplateDir();
		$this->templateObj->setTemplateDir($tplDir . 'game/html/');
		return true;
	}

	protected function setPageType($window) {
		$this->window = $window;
	}

	protected function getNavData() {		
		if(!isset($_SESSION['CurrentPlanet'])) {
			$key = array_rand($_SESSION['OBJECTS']);
			$_SESSION['CurrentPlanet'] = $_SESSION['OBJECTS'][$key];
		}
		
		$this->templateObj->assign_vars(array(
			 'currentObject'		=> $_SESSION['CurrentPlanet']->getObjectID(),
			 'currentObjectGalaxy'	=> $_SESSION['CurrentPlanet']->getGalaxy(),
			 'currentObjectSector'	=> $_SESSION['CurrentPlanet']->getSector(),
			 'currentObjectStar'	=> $_SESSION['CurrentPlanet']->getStar(),
			 'currentObjectObject'	=> $_SESSION['CurrentPlanet']->getObject(),
			 'currentObjectName'	=> $_SESSION['CurrentPlanet']->getName(),
			 'currentObjectImage'	=> $_SESSION['CurrentPlanet']->getImageID(),
			 'currentObjectTypeName'=> $_SESSION['CurrentPlanet']->getTypeName(),
			 'currentObjectCoord'	=> $_SESSION['CurrentPlanet']->getCoordString()
		));
	}

	protected function getPageVars() {
		if ($this->window === 'full') {
			$this->getNavData();
		}

		$this->templateObj->assign_vars(array(
			 'playerName'	=> $_SESSION['playerName'],
			 'playerID'		=> $_SESSION['playerID'],
			 'numPlanets'	=> count($_SESSION['OBJECTS']),
			 'game_name'	=> $GLOBALS['_GAME_NAME'],
			 'VERSION'		=> $GLOBALS['_GAME_VERSION'],
			 'timestamp'	=> TIMESTAMP,
			 'page'			=> HTTP::REQ('page', '')
		));
	}

	protected function showMessage($Message) {
		$this->templateObj->assign_vars(array(
			 'mes' => $Message,
		));

		$this->display('error.tpl');
	}

	protected function display($file) {
		$this->getPageVars();

		$this->templateObj->display('extends:layout_' . $this->window . '.tpl|' . $file);
		exit;
	}
}