<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

class Page_Register extends AbstractPage {

	function __construct() {
		parent::__construct();
	}

	function show() {
		//PENDING: referrals

		$this->assign(array(
			 'registerRulesDesc' => 'I agree with the <a href="index.php?page=rules">Rules</a>'
		));

		$this->render('page_register.tpl');
	}

	function send() {
		$playerName = HTTP::REQ('playername', '', true);
		$password = HTTP::REQ('password', '', true);
		$password2 = HTTP::REQ('passwordReplay', '', true);
		$mailAddress = HTTP::REQ('email', '');
		$mailAddress2 = HTTP::REQ('emailReplay', '');
		$rulesChecked = HTTP::REQ('rules', 0);

		$errors = array();

		if (empty($playerName)) {
			$errors[] = 'You must enter a username!';
		}

		if (!UtilPlayer::isPlayerNameValid($playerName)) {
			$errors[] = 'Your username must consist in numbers, Letters, Spaces, _, -, . only!';
		}

		if (strlen($password) < 6) {
			$errors[] = 'The password must be at least 6 characters long!';
		}

		if ($password != $password2) {
			$errors[] = 'Entering 2 different Passwords!';
		}

		if (!UtilPlayer::isPlayerEmailValid($mailAddress)) {
			$errors[] = 'Invalid E-Mail address!';
		}

		if (empty($mailAddress)) {
			$errors[] = 'You must specify an E-Mail address!';
		}

		if ($mailAddress != $mailAddress2) {
			$errors[] = 'You have specified 2 different email addresses!';
		}

		if ($rulesChecked != 1) {
			$errors[] = 'You have to accept the rules!';
		}

		$countUsername = DBMySQL::selectCell(tblPLAYERS, "playerName = :playerName", array(":playerName" => $playerName), "COUNT(*)");
		$countMail = DBMySQL::selectCell(tblPLAYERS, "playerEmail = :playerEmail", array(":playerEmail" => $mailAddress), "COUNT(*)");
		
		if ($countUsername != 0) {
			$errors[] = 'The username is already taken!';
		}

		if ($countMail != 0) {
			$errors[] = 'The E-Mail address is already registered!';
		}

		if ($GLOBALS['_RECAPTCHA_PRIVKEY'] !== '1') {
			require_once('engine/libs/reCAPTCHA/recaptchalib.php');

			$resp = recaptcha_check_answer($GLOBALS['_RECAPTCHA_PRIVKEY'], $_SERVER['REMOTE_ADDR'], $_REQUEST['recaptcha_challenge_field'], $_REQUEST['recaptcha_response_field']);

			if (!$resp->is_valid) {
				$errors[] = 'The security code is incorrect!';
			}
		}

		if (!empty($errors)) {
			$this->showMessage(implode("<br>\r\n", $errors));
			exit;
		}

		$result = UtilPlayer::createPlayer($playerName, $password, $mailAddress);
		if($result === true) {
			$this->showMessage('Thank you for the registration. Check your email for activation.');
		} else {
			$this->showMessage($result);
		}
	}
}