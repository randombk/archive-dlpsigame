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
	ErrorPage::printError("<font size=6px;'>Your account has been banned!</font><br>Expiry Time: " . $_SESSION['PLAYER']['banExpireTime']);
}

if(!isset($_SESSION['OBJECTS']))
	UtilPlayer::getPlayerObjects();

$page = HTTP::REQ('page', 'index');
$mode = HTTP::REQ('mode', 'show');
$mode = strictString(str_replace(array('_', '\\', '/', '.', "\0"), '', $mode));
$pageName = strictString(str_replace(' ', '', ucwords(str_replace('-', ' ', strtolower($page)))));
$pageClass = 'Page_' . $pageName;
$pageSrc = ROOT_PATH . 'pages/game/' . $pageClass . '.php';

if (!file_exists($pageSrc)) {
	ErrorPage::printError("Requested Page not Found");
} else {
	require ($pageSrc);
	$pageObj = new $pageClass;
	$pageProps = get_class_vars($pageClass);

	if (!is_callable(array($pageObj, $mode))) {
		if (!isset($pageProps['defaultController']) || !is_callable(array($pageObj, $pageProps['defaultController']))) {
			ErrorPage::printError("Requested Page not Found");
		}
		$mode = $pageProps['defaultController'];
	}
	$pageObj -> {$mode}();
}
