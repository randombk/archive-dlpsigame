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
	 * @param string $window
	 */
	protected function setPageType($window) {
		$this->window = $window;
	}

	/**
	 * @return UniCoord
	 */
	protected function getPlayerCurrentObject() {
		if(!isset($_SESSION['CurrentPlanet'])) {
			$key = array_rand($_SESSION['OBJECTS']);
			$_SESSION['CurrentPlanet'] = $_SESSION['OBJECTS'][$key];
		}

		return $_SESSION['CurrentPlanet'];
	}

	/**
	 * @return int
	 */
	protected function updatePlayerCurrentObject() {
		$objectID = HTTP::REQ("objectID", 0);

		if($objectID == 0 || !isset($_SESSION['OBJECTS'][$objectID])) {
			GameErrorPage::printError("Invalid object");
		} else {
			$_SESSION['CurrentPlanet'] = $_SESSION['OBJECTS'][$objectID];
			return $objectID;
		}
		return 0;
	}

	protected function getNavData() {
		$curPlanet = $this->getPlayerCurrentObject();

		$this->templateObj->assign_vars(array(
			'objectID'		=> $curPlanet->getObjectID(),
			'objectGalaxy'	=> $curPlanet->getGalaxy(),
			'objectSector'	=> $curPlanet->getSector(),
			'objectStar'	=> $curPlanet->getStar(),
			'objectIndex'	=> $curPlanet->getObject(),
			'objectName'	=> $curPlanet->getName(),
			'objectImage'	=> $curPlanet->getImageID(),
			'objectTypeName'=> $curPlanet->getTypeName(),
			'objectCoord'	=> $curPlanet->getCoordString()
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
	 * @param string $Message
	 */
	protected function showMessage($Message) {
		$this->templateObj->assign_vars(array(
			 'mes' => $Message,
		));

		$this->display('error.tpl');
	}

	/**
	 * @param string $file
	 */
	protected function display($file) {
		$this->getPageVars();

		$this->templateObj->display('extends:layout_' . $this->window . '.tpl|' . $file);
		exit;
	}
}
