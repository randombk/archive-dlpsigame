<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * @return string
 */
function gen_uuid() {
    return sprintf( '{%04x%04x-%04x-%04x-%04x-%04x%04x%04x}',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

/**
 * Class LoginAbstractPage
 */
abstract class LoginAbstractPage {
	/* @var $templateObj SmartyWrapper */
	protected $templateObj;
	protected $window;

	/**
	 *
	 */
	protected function __construct() {
		$this->setPageType('normal');
		$this->initTemplate();
	}

	/**
	 * @return bool
	 */
	protected function initTemplate() {
		if (isset($this->templateObj))
			return true;

		$this->templateObj = new SmartyWrapper;
		list($tplDir) = $this->templateObj->getTemplateDir();
		$this->templateObj->setTemplateDir($tplDir . 'login/html/');
		return true;
	}

	/**
	 * @param string $window
	 */
	protected function setPageType($window) {
		$this->window = $window;
	}

	protected function getPageVars() {
		$this->templateObj->assign_vars(array(
			'recaptchaPublicKey' => $GLOBALS['_RECAPTCHA_PUBKEY'],
			'gameName' => $GLOBALS['_GAME_NAME'],
			'VERSION' => $GLOBALS['_GAME_VERSION'],
			'BUILDID' => date(DATE_ATOM)
		));
	}

	/**
	 * @param string $message
	 */
	protected function showMessage($message) {
		$this->assign(array(
			 'message' => $message
		));
		$this->render('error.tpl');
	}

	/**
	 * @param array $array
	 */
	protected function assign($array) {
		$this->templateObj->assign_vars($array);
	}

	/**
	 * @param string $file
	 */
	protected function render($file) {
		$this->getPageVars();
		$this->templateObj->display('extends:layout_' . $this->window . '.tpl|' . $file);
		exit;
	}
}