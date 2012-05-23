<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class UtilPlayer
 */
class UtilPlayer {
	/**
	 * @param $password
	 * @return string
	 */
	static function cryptPassword($password) {
		require_once(ROOT_PATH . 'engine/config.php');
		return crypt($password, '$2a$09$' . $GLOBALS['_SALT'] . '$');
	}

	/**
	 * @param $name
	 * @return int
	 */
	static function isPlayerNameValid($name) {
		return preg_match("/^[\p{L}\p{N}_\-. ]*$/u", $name);
	}

	/**
	 * @param $address
	 * @return bool
	 */
	static function isPlayerEmailValid($address) {
		return filter_var($address, FILTER_VALIDATE_EMAIL) !== FALSE;
	}

	/**
	 * @param $playerName
	 * @param $password
	 * @param $mailAddress
	 * @return bool|string
	 */
	static function createPlayer($playerName, $password, $mailAddress) {
		$validationKey = md5(uniqid('2m'));
		$playerID = DBMySQL::insert(
			tblPLAYERS,
			array(
			    "playerName" => $playerName,
			    "validationKey" => $validationKey,
			    "playerPass" => UtilPlayer::cryptPassword($password),
			    "playerEmail" => $mailAddress,
			    "joinDate" => TIMESTAMP,
			    "joinIP" => $_SERVER['REMOTE_ADDR']
			),
			true	
		);
		
		if(!isset($playerID) || $playerID === null) {
			//ERROR
			return "An Unknown error occurred!";
		}
		
		$verifyURL = HTTP_PATH . "index.php?page=verify&i=" . $playerID . "&k=" . $validationKey;
		$MailContent = "Hello $playerName: \n Activation message for {$GLOBALS['_GAME_NAME']}: \nTODO: ---message--- \n $verifyURL";
		
		try {
			MailWrapper::send($mailAddress, $playerName, sprintf('Activation of registration on the game: %s', $GLOBALS['_GAME_NAME']), $MailContent);
		} catch (Exception $e) {
			return sprintf("Error: %s", $e->getMessage());
		}
		
		return true;
	}

	/**
	 * @param $playerID
	 * @param $playerData
	 * @return array
	 */
	static function activatePlayer($playerID, $playerData) {
		
		//TODO: Error handling
		//Give player a new planet
		$newPlanetID = UtilObject::createPlanet(UtilObject::getFreeObjectCoord(1, 1, "Colony"), $playerID);
		
		//Give player some resources
		$item = new DataItem();
		$item->setItem("iron", 5000);
		$item->setItem("kryptonite", 5000);
		
		UtilObject::setObjectResDataUsingID($newPlanetID, $item->getItemArray(), false);
		
		self::setPlayerResearchData(array(), $playerID);
		
		//PENDING: referrals
		//PENDING: external authentication
		DBMySQL::update(tblPLAYERS, array("validationKey" => "", "last_update" => TIMESTAMP), "playerID = :playerID", array(":playerID" => $playerID));
		
		$subject = 'Welcome';
		$message = 'TODO: Welcome Message';
		
		Message::sendMessage($playerID, 1, "System", 1, $subject, $message, time());
		Message::sendNotification($playerID, "Welcome", "Welcome to " . $GLOBALS['_GAME_NAME'], "OK", "", "game.php", TIMESTAMP);
		
		return array(
			 'playerID' => $playerID,
			 'playerName' => $playerData['playerName'],
		);
	}

	/**
	 * @param $playerID
	 * @return mixed
	 */
	static function deletePlayer($playerID) {
		$ownedAlliance = DBMySQL::selectTop(
			tblALLIANCE,
			"allianceOwnerID = :playerID",
			array(":playerID", $playerID),
			"allianceID"
		);
		
		if (!empty($ownedAlliance)) {
			//TODO: Handle alliance ownership after player deletion
		}

		return DBMySQL::prepare("DELETE FROM " . tblPLAYERS . " WHERE playerID = :playerID;")->execute(array(":playerID", $playerID));
	}

	/**
	 * @param null $playerID
	 * @return UniCoord[]
	 */
	static function getPlayerObjects($playerID = null) {
		if(is_null($playerID)) {
			$playerID = $_SESSION['playerID'];
		}
		
		$objects = DBMySQL::select(
			tblUNI_OBJECTS,
			"ownerID = :playerID",
			array(":playerID" => $playerID),
			"objectID, objectName, objectType, objectIndex, starID, objectImageID"
		);
		
		$return = array();
		foreach($objects as $object) {
			$return[$object['objectID']] = UniCoord::fromData($object);
		}
		return $return;
	}

	/**
	 * @param $researchData
	 * @param null $playerID
	 * @return mixed
	 */
	static function setPlayerResearchData($researchData, $playerID = null) {
		if(is_null($playerID)) {
			$playerID = $_SESSION['playerID'];
		}
		
		return DBMongo::setResearch("playerResearch_".$playerID, $researchData);
	}

	/**
	 * @param null $playerID
	 * @return DataResearch
	 */
	static function getPlayerResearchData($playerID = null) {
		if(is_null($playerID)) {
			$playerID = $_SESSION['playerID'];
		}
		
		$data = DataResearch::fromResearchArray(DBMongo::getResearch("playerResearch_".$playerID));
		return $data;
	}

	/**
	 * @param $playerData
	 * @param null $playerID
	 * @return mixed
	 */
	static function setPlayerData($playerData, $playerID = null) {
		if(is_null($playerID)) {
			$playerID = $_SESSION['playerID'];
		}

		return DBMongo::setPlayer("playerData_".$playerID, $playerData);
	}

	/**
	 * @param null $playerID
	 * @return DataPlayer
	 */
	static function getPlayerData($playerID = null) {
		if(is_null($playerID)) {
			$playerID = $_SESSION['playerID'];
		}
		
		$data = DataPlayer::fromDataArray(DBMongo::getPlayer("playerData_".$playerID));
		return $data;
	}
}
