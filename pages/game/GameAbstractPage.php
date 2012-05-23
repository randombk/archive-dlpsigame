<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class GameAbstractPage
 */
abstract class GameAbstractPage {
	/* @var $templateObj SmartyWrapper */
	protected $templateObj;
	protected $window;

	/**
	 *
	 */
	protected function __construct() {
		$this->setPageType('full');
		$this->initTemplate();
	}

	/**
	 * @return bool
	 */
	protected function initTemplate() {
		if (isset($this->templateObj))
			return true;

		$this->templateObj = new SmartyWrapper;
		list($tplDir) = $this->templateObj->getTemplateDir();
		$this->templateObj->setTemplateDir($tplDir . 'game/html/');
		return true;
	}

	/**
	 * @param $window
	 */
	protected function setPageType($window) {
		$this->window = $window;
	}

	protected function getNavData() {		
		if(!isset($_SESSION['CurrentPlanet'])) {
			$key = array_rand($_SESSION['OBJECTS']);
			$_SESSION['CurrentPlanet'] = $_SESSION['OBJECTS'][$key];
		}

		/* @var $curPlanet UniCoord */
		$curPlanet = $_SESSION['CurrentPlanet'];

		$this->templateObj->assign_vars(array(
			 'currentObject'		=> $curPlanet->getObjectID(),
			 'currentObjectGalaxy'	=> $curPlanet->getGalaxy(),
			 'currentObjectSector'	=> $curPlanet->getSector(),
			 'currentObjectStar'	=> $curPlanet->getStar(),
			 'currentObjectObject'	=> $curPlanet->getObject(),
			 'currentObjectName'	=> $curPlanet->getName(),
			 'currentObjectImage'	=> $curPlanet->getImageID(),
			 'currentObjectTypeName'=> $curPlanet->getTypeName(),
			 'currentObjectCoord'	=> $curPlanet->getCoordString()
		));
	}

	protected function getPageVars() {
		if ($this->window === 'full') {
			$this->getNavData();
		}

		$this->templateObj->assign_vars(array(
			 'playerName'	=> $_SESSION['playerName'],
			 'playerID'		=> $_SESSION['playerID'],
			 'isOP'			=> (int)$_SESSION['PLAYER']['isOP'],
			 'isAdmin'		=> (int)$_SESSION['PLAYER']['isAdmin'],
			 'numPlanets'	=> count($_SESSION['OBJECTS']),
			 'game_name'	=> $GLOBALS['_GAME_NAME'],
			 'VERSION'		=> $GLOBALS['_GAME_VERSION'],
			 'timestamp'	=> TIMESTAMP,
			 'cacheTime'	=> GameCache::getCacheTime(),
			 'page'			=> HTTP::REQ('page', '')
		));
	}

	/**
	 * @param $Message
	 */
	protected function showMessage($Message) {
		$this->templateObj->assign_vars(array(
			 'mes' => $Message,
		));

		$this->display('error.tpl');
	}

	/**
	 * @param $file
	 */
	protected function display($file) {
		$this->getPageVars();

		$this->templateObj->display('extends:layout_' . $this->window . '.tpl|' . $file);
		exit;
	}
}