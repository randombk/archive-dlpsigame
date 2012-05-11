<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

class Message {
	/*
	 * 0 - Notification
	 * 1 - Message
	 * 2 - Battle Report
	 * 3 - Alliance
	 * 
	 * */
	static function sendMessage($toID, $fromID, $senderName, $messageType, $subject, $text, $time) {
		return $GLOBALS['RDBMS']->insert(
			tblPLAYER_MESSAGES, 
			array(
				"messageOwnerID"	=> $toID,
				"messageSenderID"	=> $fromID,
				"messageTime"		=> $time,
				"messageType"		=> $messageType,
				"messageFromName"	=> $senderName,
				"messageSubject"	=> $subject,
				"messageText"		=> $text 
			),
			true
		);
	}
	
	static function getMessages($playerID, $messageType, $shift = 0, $count = 10) {
		return $GLOBALS['RDBMS']->select(
			tblPLAYER_MESSAGES, 
			"messageOwnerID = :playerID AND messageType = :type", 
			array(
				":playerID" => $playerID,
				":type"	=> $messageType,
				":shift" => $shift,
				":count" => $count
			), 
			"messageID,	messageSenderID, messageFromName, messageTime, messageSubject, messageRead",
			"ORDER BY messageTime DESC LIMIT :shift, :count"
		);
	}
	
	static function getMessageText($messageID, $messageOwner = null) {
		if($messageOwner) {
			return $GLOBALS['RDBMS']->select(
				tblPLAYER_MESSAGES, 
				"messageOwnerID = :playerID AND messageID = :id", 
				array(
					":playerID" => $messageOwner,
					":id"	=> $messageID
				), 
				"messageID,	messageSenderID, messageFromName, messageTime, messageSubject, messageText, messageRead"
			);
		} else {
			return $GLOBALS['RDBMS']->select(
				tblPLAYER_MESSAGES, 
				"messageID = :id", 
				array(
					":id"	=> $messageID
				), 
				"messageID,	messageSenderID, messageFromName, messageTime, messageSubject, messageText, messageRead"
			);
		}
	}
	
	static function deleteMessage($messageID, $messageOwner = null) {
		if($messageOwner) {
			return $GLOBALS['RDBMS']->prepare("
				DELETE
				FROM " . tblPLAYER_MESSAGES . " 
				WHERE 
					messageOwnerID	= :messageOwnerID AND
					messageID = :messageID
			;")->execute(array(":messageOwnerID" => $messageOwner, ":messageID" => $messageID));
		} else {
			return $GLOBALS['RDBMS']->prepare("
				DELETE
				FROM " . tblPLAYER_MESSAGES . " 
				WHERE 
					messageID = :messageID
			;")->execute(array(":messageID" => $messageID));
		}
	}
	
	static function sendNotification($toID, $oneLiner, $text, $type, $image, $action, $time) {
		return $GLOBALS['RDBMS']->insert(
			tblPLAYER_MESSAGES, 
			array(
				"messageOwnerID"	=> $toID,
				"messageSenderID"	=> 1,
				"messageTime"		=> $time,
				"messageType"		=> 0,
				"messageFromName"	=> "NOTIFICATION",
				"messageSubject"	=> $oneLiner,
				"messageText"		=> json_encode(array(
					"msgText" => $text,
					"msgType" => $type,
					"msgImage" => $image,
					"msgAction" => $action
				)) 
			),
			true
		);
	}
	
	static function getNotifications($playerID, $shift = 0, $count = 10) {
		$messages = $GLOBALS['RDBMS']->select(
			tblPLAYER_MESSAGES, 
			"messageOwnerID = :playerID AND messageType = 0", 
			array(
				":playerID" => $playerID,
				":shift" => $shift,
				":count" => $count
			), 
			"messageID, messageTime, messageSubject, messageText",
			"ORDER BY messageTime DESC LIMIT :shift, :count"
		);
		
		for ($i=0; $i < sizeof($messages); $i++) { 
			$messages[$i]["messageText"] = json_decode($messages[$i]["messageText"], true);
		}
		return $messages;
	}
	
	static function clearNotifications($playerID) {
		return $GLOBALS['RDBMS']->delete(tblPLAYER_MESSAGES, "messageOwnerID	= :messageOwnerID AND messageType = 0", array(":messageOwnerID" => $playerID));
	}
}
?>
