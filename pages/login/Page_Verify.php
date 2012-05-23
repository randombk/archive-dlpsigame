<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

class Page_Verify extends AbstractPage {
	function __construct() {
		parent::__construct();
	}

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
		$playerData = $this->activateUser();
		HTTP::redirectTo('game.php');
	}
}