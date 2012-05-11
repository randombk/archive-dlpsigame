<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */
define('HTTPS', isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on');
define('PROTOCOL', HTTPS ? 'https://' : 'http://');

define('HTTP_BASE', str_replace(array('\\', '//'), '/', dirname($_SERVER['SCRIPT_NAME']) . '/'));
define('HTTP_ROOT', str_replace(basename($_SERVER['SCRIPT_FILENAME']), '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));

define('HTTP_FILE', basename($_SERVER['SCRIPT_NAME']));
define('HTTP_HOST', $_SERVER['HTTP_HOST']);
define('HTTP_PATH', PROTOCOL . HTTP_HOST . HTTP_ROOT);

// How much IP Block ll be checked
// 1 = (AAA); 2 = (AAA.BBB); 3 = (AAA.BBB.CCC)
define('COMPARE_IP_BLOCKS', 2);