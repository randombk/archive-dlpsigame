<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class Page_Verify
 */
class Page_Verify extends LoginAbstractPage {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();
	}

	/**
	 * @return array
	 */
	private function activateUser() {
		$playerID = HTTP::REQ('i', 0);
		$validationKey = HTTP::REQ('k', '');

		$playerData = DBMySQL::selectTop(
			tblPLAYERS, 
			"playerID = :playerID AND validationKey = :key", 
			array(":playerID" => $playerID, ":key" => $validationKey),
			"*"
		);

		if (!isset($playerData)) {
			$this->showMessage('Invalid Request!');
		}
		
		GameSession::loginPlayer($playerData['playerID'], $playerData['playerName']);
		return UtilPlayer::activatePlayer($playerID, $playerData);
	}

	function show() {
		$this->activateUser();
		HTTP::redirectTo('game.php');
	}
}