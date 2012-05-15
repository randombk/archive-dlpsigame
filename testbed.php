<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */
 
define('MODE', 'INGAME');
define('ROOT_PATH', str_replace('\\', '/', dirname(__FILE__)) . '/');

//Set Custom error handlers
require (ROOT_PATH . 'engine/ErrorHandlers.php');
set_exception_handler('base_interfaceException');
set_error_handler('base_interfaceError');

require (ROOT_PATH . 'pages/game/AbstractPage.php');
require (ROOT_PATH . 'pages/game/ErrorPage.php');
require (ROOT_PATH . 'engine/common.php');

if (!GameSession::isLoggedIn()) {
	HTTP::redirectTo('index.php?code=3');
}

if (!isset($_SESSION['PLAYER'])) {
	$s_LoadUser = $GLOBALS['RDBMS']->prepare("
		SELECT 
			player.*,
			banned.*
		FROM " . tblPLAYERS . " as player 
			LEFT JOIN " . tblBANNED . " as banned ON banned.playerID = player.playerID
		WHERE player.playerID = :playerID;");	
	
	$s_LoadUser->bindValue(':playerID', $_SESSION['playerID'], PDO::PARAM_INT);
	$s_LoadUser->execute();
    $_SESSION['PLAYER'] = $s_LoadUser->fetch(PDO::FETCH_ASSOC);
}

if (empty($_SESSION['PLAYER'])) {
	exit(header('Location: index.php'));
}

if ($_SESSION['PLAYER']['banExpireTime'] > time()) {
	ErrorPage::printError("<font size=;6px'>Your account has been banned!</font><br>Expiry Time: " . $_SESSION['PLAYER']['banExpireTime']);
}

$_SESSION['OBJECTS'] = UtilPlayer::getPlayerObjects();

//BEGIN TESTBED

$newPlanetID = UtilObject::createPlanet(UtilObject::getFreeObjectCoord(1, 1, "Colony"), 2);
		
//Give player some items
$item = new DataItem();
$item->setItem("iron", 5000);
$item->setItem("kryptonite", 5000);

UtilObject::setObjectResDataUsingID($newPlanetID, $item->getItemArray(), false);
		