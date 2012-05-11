<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

class HTTP {
	static public function redirectTo($URL, $external = false) {
		if ($external) {
			self::sendHeader('Location', $URL);
		} else {
			self::sendHeader('Location', HTTP_PATH . $URL);
		}
		exit;
	}

	static public function sendHeader($name, $value = NULL) {
		header($name . (!is_null($value) ? ': ' . $value : ''));
	}

	static public function REQ($name, $default, $multibyte = false) {
		if (!isset($_REQUEST[$name])) {
			return $default;
		}

		if (is_int($default)) {
			return (int) $_REQUEST[$name];
		}

		if (is_float($default)) {
			return (float) $_REQUEST[$name];
		}

		if ($default === "json") {
			return json_decode($_REQUEST[$name], true);
		}
		
		if (is_string($default)) {
			$var = trim(htmlspecialchars(str_replace(array("\r\n", "\r", "\0"), array("\n", "\n", ''), $_REQUEST[$name]), ENT_QUOTES | ENT_HTML5));

			if (empty($var)) {
				return $default;
			}

			if ($multibyte) {
				if (!preg_match('/^./u', $var)) {
					$var = '';
				}
			} else {
				$var = preg_replace('/[\x80-\xFF]/', '?', $var); // no multibyte, allow only ASCII (0-127)
			}
			return $var;
		}
		return $default;
	}
}
