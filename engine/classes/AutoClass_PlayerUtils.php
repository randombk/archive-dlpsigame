<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

//NONOPTIMAL: Load star data into cache, as that is fixed
class PlayerUtils {
	static function cryptPassword($password) {
		require_once(ROOT_PATH . 'engine/config.php');
		return crypt($password, '$2a$09$' . $GLOBALS['_SALT'] . '$');
	}
	
	static function isPlayerNameValid($name) {
		return preg_match("/^[\p{L}\p{N}_\-. ]*$/u", $name);
	}

	static function isPlayerEmailValid($address) {
		return filter_var($address, FILTER_VALIDATE_EMAIL) !== FALSE;
	}
	
	static function createPlayer($playerName, $password, $mailAddress) {
		$validationKey = md5(uniqid('2m'));
		$playerID = $GLOBALS['RDBMS']->insert(
			tblPLAYERS,
			array(
			    "playerName" => $playerName,
			    "validationKey" => $validationKey,
			    "playerPass" => PlayerUtils::cryptPassword($password),
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
		$MailSubject = 'Activation of registration on the game: %s';
		$MailContent = "Hello $playerName: \n Activation message for {$GLOBALS['_GAME_NAME']}: \nTODO: ---message--- \n$verifyURL";
		
		try {
			MailWrapper::send($mailAddress, $playerName, sprintf('Activation of registration on the game: %s', $GLOBALS['_GAME_NAME']), $MailContent);
		} catch (Exception $e) {
			return sprintf("Error: %s", $e->getMessage());
			$this->showMessage(sprintf("Error: %s", $e->getMessage()));
		}
		
		return true;
	}
	
	static function activatePlayer($playerID, $playerData) {
		//TODO: Error handling
		//Give player a new planet
		$newPlanetID = ObjectUtils::createPlanet(ObjectUtils::getFreeObjectCoord(1, 1, "Colony"), $playerID);
		
		//Give player some resources
		$item = new DataItem();
		$item->setItem("iron", 5000);
		$item->setItem("kryptonite", 5000);
		
		ObjectUtils::setObjectResDataUsingID($newPlanetID, $item->getItemArray(), false);
		
		self::setPlayerResearchData(array(), $playerID);
		
		//PENDING: referrals
		//PENDING: external authentiation
		$GLOBALS['RDBMS']->update(tblPLAYERS, array("validationKey" => ""), "playerID = :playerID", array(":playerID" => $playerID));
		
		$nameSender = 'Administrator';
		$subject = 'Welcome';
		$message = 'TODO: Welcome Message';
		
		Message::sendMessage($playerID, 1, "System", 1, $subject, $message, time());
		Message::sendNotification($playerID, "Welcome", "Welcome to " . $GLOBALS['_GAME_NAME'], "OK", "", "game.php", TIMESTAMP);
		
		return array(
			 'playerID' => $playerID,
			 'playerName' => $playerData['playerName'],
		);
	}
	
	static function deletePlayer($playerID) {
		$ownedAlliance = $GLOBALS['RDBMS']->selectTop(
			tblALLIANCE,
			"allianceOwnerID = :playerID",
			array(":playerID", $playerID),
			"allianceID"
		);
		
		if (!empty($ownedAlliance)) {
			//TODO: Handle alliance ownership after player deletion
		}

		return $GLOBALS['RDBMS']->prepare("DELETE FROM " . tblPLAYERS . " WHERE playerID = :playerID;")->execute(array(":playerID", $playerID));
	}

	static function getPlayerObjects($playerID = null) {
		if(is_null($playerID)) {
			$playerID = $_SESSION['playerID'];
		}
		
		$objects = $GLOBALS['RDBMS']->select(
			tblUNI_OBJECTS,
			"ownerID = :playerID",
			array(":playerID" => $playerID),
			"objectID, objectName, objectType, objectIndex, starID, objectImageID"
		);
		
		$return = array();
		foreach($objects as $object) {
			$return[$object['objectID']] = UniCoord::fromData($object);
		}
		
		if($playerID == $_SESSION['playerID']) {
			$_SESSION['OBJECTS'] = $return;
		}
		
		return $return;
	}
	
	static function setPlayerResearchData($researchData, $playerID = null) {
		if(is_null($playerID)) {
			$playerID = $_SESSION['playerID'];
		}

		if($playerID == $_SESSION['playerID']) {
			$_SESSION['RESEARCH'] = $researchData;
		}
		
		return $GLOBALS['MONGO']->setResearch("playerResearch_".$playerID, $researchData->getResearchArray());
	}
	
	static function getPlayerResearchData($playerID = null) {
		if(is_null($playerID)) {
			$playerID = $_SESSION['playerID'];
		}
		
		$data = DataResearch::fromResearchArray($GLOBALS['MONGO']->getResearch("playerResearch_".$playerID));
		
		if($playerID == $_SESSION['playerID']) {
			$_SESSION['RESEARCH'] = $data;
		}
		
		return $data;
	}
}
