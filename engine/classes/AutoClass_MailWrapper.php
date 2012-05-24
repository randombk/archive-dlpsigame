<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class MailWrapper
 */
class MailWrapper {

	/**
	 * @param string $MailTarget
	 * @param string $MailTargetName
	 * @param string $MailSubject
	 * @param string $MailContent
	 */
	static function send($MailTarget, $MailTargetName, $MailSubject, $MailContent) {
		$transport = self::getSwiftTransport();
		$mailer = Swift_Mailer::newInstance($transport);

		$mailFrom = $GLOBALS['_MAIL_SMTP_USER'];
		$mailTo = $GLOBALS['_GAME_NAME'];

		$mail = Swift_Message::newInstance();
		$mail->setSubject($MailSubject);
		$mail->setFrom(array($mailFrom => $mailTo));
		$mail->setTo(array($MailTarget => $MailTargetName));
		$mail->setBody($MailContent);

		$mailer->send($mail);
	}

	/**
	 * @param string $MailTargets
	 * @param string $MailSubject
	 * @param string $MailContent
	 */
	static function multiSend($MailTargets, $MailSubject, $MailContent = NULL) {
		$transport = self::getSwiftTransport();
		$mailer = Swift_Mailer::newInstance($transport);

		$mailFrom = $GLOBALS['_MAIL_SMTP_USER'];
		$mailTo = $GLOBALS['_GAME_NAME'];

		$mail = Swift_Message::newInstance();
		$mail->setSubject($MailSubject);
		$mail->setFrom(array($mailFrom => $mailTo));

		foreach ($MailTargets as $address => $data) {
			$content = isset($data['body']) ? $data['body'] : $MailContent;
			$mail->setTo(array($address => $data['playername']));
			$mail->setBody(strip_tags($content));
			$mail->addPart($content, 'text/html');

			$mailer->send($mail);
		}
	}

	/**
	 * @return Swift_SmtpTransport
	 */
	static function getSwiftTransport() {
		require_once(ROOT_PATH . 'engine/libs/swift/swift_required.php');
		$transport = Swift_SmtpTransport::newInstance($GLOBALS['_MAIL_SMTP_HOST'], $GLOBALS['_MAIL_SMTP_PORT']);
		$transport->setEncryption($GLOBALS['_MAIL_SMTP_ENC']);
		$transport->setPassword($GLOBALS['_MAIL_SMTP_PASS']);
		$transport->setUsername($GLOBALS['_MAIL_SMTP_USER']);
		return $transport;
	}

}