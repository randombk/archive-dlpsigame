<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class Message
 */
class Message
{
	/*
	 * 0 - Notification
	 * 1 - Message
	 * 2 - Battle Report
	 * 3 - Alliance
	 * 
	 * */
	/**
	 * @param $toID
	 * @param $fromID
	 * @param $senderName
	 * @param $messageType
	 * @param $subject
	 * @param $text
	 * @param $time
	 * @return mixed
	 */
	static function sendMessage($toID, $fromID, $senderName, $messageType, $subject, $text, $time)
	{
		return DBMySQL::insert(
			tblPLAYER_MESSAGES,
			array(
				"messageOwnerID" => $toID,
				"messageSenderID" => $fromID,
				"messageTime" => $time,
				"messageType" => $messageType,
				"messageFromName" => $senderName,
				"messageSubject" => $subject,
				"messageText" => $text
			),
			true
		);
	}

	/**
	 * @param $playerID
	 * @param $messageType
	 * @param int $shift
	 * @param int $count
	 * @return mixed
	 */
	static function getMessages($playerID, $messageType, $shift = 0, $count = 10)
	{
		return DBMySQL::select(
			tblPLAYER_MESSAGES,
			"messageOwnerID = :playerID AND messageType = :type",
			array(
				":playerID" => $playerID,
				":type" => $messageType,
				":shift" => $shift,
				":count" => $count
			),
			"messageID,	messageSenderID, messageFromName, messageTime, messageSubject, messageRead",
			"ORDER BY messageTime DESC LIMIT :shift, :count"
		);
	}

	/**
	 * @param $messageID
	 * @param null $messageOwner
	 * @return mixed
	 */
	static function getMessageText($messageID, $messageOwner = null)
	{
		if ($messageOwner) {
			return DBMySQL::select(
				tblPLAYER_MESSAGES,
				"messageOwnerID = :playerID AND messageID = :id",
				array(
					":playerID" => $messageOwner,
					":id" => $messageID
				),
				"messageID,	messageSenderID, messageFromName, messageTime, messageSubject, messageText, messageRead"
			);
		} else {
			return DBMySQL::select(
				tblPLAYER_MESSAGES,
				"messageID = :id",
				array(
					":id" => $messageID
				),
				"messageID,	messageSenderID, messageFromName, messageTime, messageSubject, messageText, messageRead"
			);
		}
	}

	/**
	 * @param $messageID
	 * @param null $messageOwner
	 * @return mixed
	 */
	static function deleteMessage($messageID, $messageOwner = null)
	{
		if ($messageOwner) {
			return DBMySQL::prepare("
				DELETE
				FROM " . tblPLAYER_MESSAGES . " 
				WHERE 
					messageOwnerID	= :messageOwnerID AND
					messageID = :messageID
			;")->execute(array(":messageOwnerID" => $messageOwner, ":messageID" => $messageID));
		} else {
			return DBMySQL::prepare("
				DELETE
				FROM " . tblPLAYER_MESSAGES . " 
				WHERE 
					messageID = :messageID
			;")->execute(array(":messageID" => $messageID));
		}
	}

	/**
	 * @param $toID
	 * @param $oneLiner
	 * @param $text
	 * @param $type
	 * @param $image
	 * @param $action
	 * @param $time
	 * @return mixed
	 */
	static function sendNotification($toID, $oneLiner, $text, $type, $image, $action, $time)
	{
		return DBMySQL::insert(
			tblPLAYER_MESSAGES,
			array(
				"messageOwnerID" => $toID,
				"messageSenderID" => 1,
				"messageTime" => $time,
				"messageType" => 0,
				"messageFromName" => "NOTIFICATION",
				"messageSubject" => $oneLiner,
				"messageText" => json_encode(array(
					"msgText" => $text,
					"msgType" => $type,
					"msgImage" => $image,
					"msgAction" => $action
				))
			),
			true
		);
	}

	/**
	 * @param $playerID
	 * @param int $shift
	 * @param int $count
	 * @return mixed
	 */
	static function getNotifications($playerID, $shift = 0, $count = 10)
	{
		$messages = DBMySQL::select(
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

		for ($i = 0; $i < sizeof($messages); $i++) {
			$messages[$i]["messageText"] = json_decode($messages[$i]["messageText"], true);
		}
		return $messages;
	}

	/**
	 * @param $playerID
	 * @return mixed
	 */
	static function clearNotifications($playerID)
	{
		return DBMySQL::delete(tblPLAYER_MESSAGES, "messageOwnerID	= :messageOwnerID AND messageType = 0", array(":messageOwnerID" => $playerID));
	}
}
