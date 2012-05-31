<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class AjaxRequest_DataLoader
 */
class AjaxRequest_DataLoader extends AjaxRequest {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();
	}

	function getGameData() {
		$this->sendJSON(array(
			"dataBUILDINGS" 	    => GameCache::get("BUILDINGS"),
			"dataITEMS" 	    	=> GameCache::get("ITEMS"),
			"dataITEMPARAMSPUBLIC"	=> GameCache::get("ITEMPARAMSPUBLIC"),
			"dataMODIFIERS"     	=> GameCache::get("MODIFIERS"),
			"dataRESEARCH" 	    	=> GameCache::get("RESEARCH"),
			"dataRESEARCHPOS" 	    => GameCache::get("RESEARCHPOS"),
			"cacheTime"			    => GameCache::getCacheTime()
		));
	}

	function clearCache() {
		if((int)$_SESSION['PLAYER']['isAdmin']) {
			GameCache::flush();
			$this->sendCode(0);
		} else {
			AjaxError::sendError("Access Denied");
		}
	}

}
