<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

class AjaxRequest_ResearchHandler extends AjaxRequest {
	function __construct() {
		parent::__construct();
	}
	
	function getResearch() {
		$playerEnv = UniUpdater::updatePlayer($_SESSION["playerID"]);
		$data = array(
			"research" => $playerEnv->envResearch->getResearchArray()//,
			//"researchQueue" => $playerEnv->researchQueue,
			//"researchProduction" => $playerEnv->researchProduction
		);
		$this->sendJSON($data);
	}	
}
