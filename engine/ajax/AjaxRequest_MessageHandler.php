<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */
/**
 * Class AjaxRequest_MessageHandler
 */
class AjaxRequest_MessageHandler extends AjaxRequest {
		/**
		 *
		 */
		function __construct() {
			parent::__construct();
		}

		function sendMessage() {
			$targetID 	= HTTP::REQ("targetID", 0);
			$msgSubject = HTTP::REQ("subject", "Message");
			$msgText 	= HTTP::REQ("text", "(No Message Body)");
			
			if ($targetID > 1) {
				$this->sendCode(Message::sendMessage($targetID, $_SESSION['playerID'], $_SESSION['playerName'], 1, $msgSubject, $msgText, TIMESTAMP));
			} else {
				AjaxError::sendError("Invalid Parameters");
			}
		}
		
		function getMessages() {
			$messageType 	= HTTP::REQ("messageType", 0);
			$shift 			= HTTP::REQ("shift", 0);
			$count 			= HTTP::REQ("count", 10);
			
			$Result = Message::getMessages($_SESSION['playerID'], $messageType, $shift, $count);
			$this->sendJSON($Result);
		}
		
		function getMessageText() {
			$messageID 	= HTTP::REQ("messageID", 0);
			
			$Result = Message::getMessageText($messageID, $_SESSION['playerID'])[0];
			$this->sendJSON($Result);
		}
		
		function deleteMessage() {
			$messageID 	= HTTP::REQ("messageID", 0);
			
			$Result = Message::deleteMessage($messageID, $_SESSION['playerID']);
			$this->sendCode($Result?0:-1);
		}
		
		function getNotifications() {
			$shift 			= HTTP::REQ("shift", 0);
			$count 			= HTTP::REQ("count", 100);
			
			$Result = Message::getNotifications($_SESSION['playerID'], $shift, $count);
			$this->sendJSON($Result);
		}
		
		function clearNotifications() {
			$Result = Message::clearNotifications($_SESSION['playerID']);
				
			$this->sendCode($Result?0:-1);
		}
	}
