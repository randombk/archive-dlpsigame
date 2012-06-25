<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class Page_Map
 */
class Page_Map extends GameAbstractPage {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();
	}

	function show() {
		$this->uniMap();
	}

	function uniMap() {
		$this->display('page_mapuni.tpl');
	}

	function sectorMap() {
		$galaxy = HTTP::REQ("galaxy", 1);
		$sector = HTTP::REQ("sector", 1);

		$this->templateObj->assign_vars(array(
			 'galaxyID' => $galaxy,
			 'sectorIndex' => $sector,
		));
		$this->display('page_mapsector.tpl');
	}

	function unifiedMap() {
		$galaxy = HTTP::REQ("galaxy", 1);
		$sector = HTTP::REQ("sector", 1);

		$this->templateObj->assign_vars(array(
			'galaxyID' => $galaxy,
			'sectorIndex' => $sector,
		));
		$this->display('page_mapunified.tpl');
	}
}
