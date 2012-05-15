<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

class Page_Login extends AbstractPage {
	function __construct() {
		parent::__construct();
	}

	function show() {
		if (empty($_POST)) {
			HTTP::redirectTo('index.php');
		}

		$playername = HTTP::REQ('playername', '', true);
		$password = HTTP::REQ('password', '', true);
		
		$loginData = $GLOBALS['RDBMS']->selectTop(tblPLAYERS, "playerName = :playerName", array(":playerName" => $playername), "playerID, playerPass, validationKey");
		
		if (isset($loginData)) {
			$hashedPassword = UtilPlayer::cryptPassword($password);
			if ($loginData['playerPass'] != $hashedPassword) {
				HTTP::redirectTo('index.php?code=1');
			}
			
			if(strlen($loginData['validationKey'])) {
				HTTP::redirectTo('index.php?code=2');
			}
			
			GameSession::loginPlayer($loginData['playerID'], $playername);
			HTTP::redirectTo('game.php');
		} else {
			HTTP::redirectTo('index.php?code=1');
		}
	}
}
