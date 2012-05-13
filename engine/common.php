<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

function strictString($str) {
	$pattern = '/^([a-zA-Z0-9]+)$/';
	preg_match($pattern, $str, $matches);
	
	if(isset($matches[1]) && $matches[1] === $str) {
		return $str;
	} else {
		return "";
	}
}

function loadClass($class_name) {
   $classFile =  ROOT_PATH . 'engine/classes/AutoClass_' . $class_name . '.php';
	if(file_exists($classFile)) {
		require_once($classFile);
	}
}

spl_autoload_register('loadClass');
//mb_internal_encoding("UTF-8");

require(ROOT_PATH . 'engine/constants.php');
require(ROOT_PATH . 'engine/config.php');
require(ROOT_PATH . 'engine/dbtables.php');

ignore_user_abort(true);
header('Content-Type: text/html; charset=UTF-8');
define('TIMESTAMP', time());

date_default_timezone_set($GLOBALS['_SERVER_TZ']);

//Connect to databases
$GLOBALS['RDBMS'] = new Database("mysql:host={$GLOBALS['_RDBMS']['host']};port={$GLOBALS['_RDBMS']['port']};dbname={$GLOBALS['_RDBMS']['databasename']}", $GLOBALS["_RDBMS"]["user"], $GLOBALS["_RDBMS"]["userpw"]);
$GLOBALS['MONGO'] = new MongoHandler();
$GLOBALS["GameCache"] = array();

