<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

function base_interfaceException($exception) {
	if(!headers_sent()) {
		HTTP::sendHeader('HTTP/1.1 503 Service Unavailable');
	}

	if (method_exists($exception, 'getSeverity')) {
		$errno = $exception->getSeverity();
	} else {
		$errno = E_USER_ERROR;
	}

	$errorType = array(
		 E_ERROR => 'ERROR',
		 E_WARNING => 'WARNING',
		 E_PARSE => 'PARSING ERROR',
		 E_NOTICE => 'NOTICE',
		 E_CORE_ERROR => 'CORE ERROR',
		 E_CORE_WARNING => 'CORE WARNING',
		 E_COMPILE_ERROR => 'COMPILE ERROR',
		 E_COMPILE_WARNING => 'COMPILE WARNING',
		 E_USER_ERROR => 'USER ERROR',
		 E_USER_WARNING => 'USER WARNING',
		 E_USER_NOTICE => 'USER NOTICE',
		 E_STRICT => 'STRICT NOTICE',
		 E_RECOVERABLE_ERROR => 'RECOVERABLE ERROR'
	);


	echo "
<html>
	<head>
		<title> Internal Error:{$errorType[$errno]}</title>
	</head>
	<body>
		<table>
			<tr>
				<th>{$errorType[$errno]}</th>
			</tr>
			<tr>
				<td>
					<b>Message: </b>".	$exception->getMessage()."<br>
					<b>File: 	</b>".	$exception->getFile()	."<br>
					<b>Line: 	</b>".	$exception->getLine()	."<br>
					<b>URL:  	</b>".	$_SERVER['REQUEST_URI']	."<br>
					<b>Debug Backtrace:</b>
					<br>" . nl2br(htmlspecialchars($exception->getTraceAsString()), false) . "
				</td>
			</tr>
		</table>
	</body>
</html>";

	$errorText = date("[d-M-Y H:i:s]", TIMESTAMP) . ' ' . $errorType[$errno] . ': "' . strip_tags($exception->getMessage()) . "\"\r\n";
	$errorText .= 'File: ' . $exception->getFile() . ' | Line: ' . $exception->getLine() . "\r\n";
	$errorText .= 'URL: ' . PROTOCOL . HTTP_HOST . $_SERVER['REQUEST_URI'] . "\r\n";
	$errorText .= "Stack trace:\r\n";
	$errorText .= str_replace(ROOT_PATH, '/', htmlspecialchars(str_replace('\\', '/', $exception->getTraceAsString()))) . "\r\n";

	file_put_contents(ROOT_PATH . 'engine/error.log', $errorText, FILE_APPEND);
}

function base_interfaceError($errno, $errstr, $errfile, $errline) {
	if (!($errno & error_reporting())) {
		return;
	}
	throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}

function base_ajaxException($exception) {
	if(!headers_sent()) {
		HTTP::sendHeader('HTTP/1.1 500 Service Unavailable');
	}

	echo json_encode($exception);
	if (method_exists($exception, 'getSeverity')) {
		$errno = $exception->getSeverity();
	} else {
		$errno = E_USER_ERROR;
	}

	$errorType = array(
		 E_ERROR => 'ERROR',
		 E_WARNING => 'WARNING',
		 E_PARSE => 'PARSING ERROR',
		 E_NOTICE => 'NOTICE',
		 E_CORE_ERROR => 'CORE ERROR',
		 E_CORE_WARNING => 'CORE WARNING',
		 E_COMPILE_ERROR => 'COMPILE ERROR',
		 E_COMPILE_WARNING => 'COMPILE WARNING',
		 E_USER_ERROR => 'USER ERROR',
		 E_USER_WARNING => 'USER WARNING',
		 E_USER_NOTICE => 'USER NOTICE',
		 E_STRICT => 'STRICT NOTICE',
		 E_RECOVERABLE_ERROR => 'RECOVERABLE ERROR'
	);
	
	$errorText = date("[d-M-Y H:i:s]", TIMESTAMP) . ' ' . $errorType[$errno] . ': "' . strip_tags($exception->getMessage()) . "\"\r\n";
	$errorText .= 'File: ' . $exception->getFile() . ' | Line: ' . $exception->getLine() . "\r\n";
	$errorText .= 'URL: ' . PROTOCOL . HTTP_HOST . $_SERVER['REQUEST_URI'] . "\r\n";
	$errorText .= "Stack trace:\r\n";
	$errorText .= str_replace(ROOT_PATH, '/', htmlspecialchars(str_replace('\\', '/', $exception->getTraceAsString()))) . "\r\n";
	file_put_contents(ROOT_PATH . 'engine/error.log', $errorText, FILE_APPEND);
}

function base_ajaxError($errno, $errstr, $errfile, $errline) {
	if (!($errno & error_reporting())) {
		return;
	}
	throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}

?>
