<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class Mongo
 */
class DBMongo
{
	private static $instance = null;
	protected $connect;
	protected $database;
	protected $exception;

	/**
	 *
	 */
	public function __construct()
	{
		try {
			$this->connect = new MongoClient("mongodb://localhost:27017", array("connect" => TRUE));
			$this->database = $this->connect->{$GLOBALS['_MONGO']['databasename']};
		} catch (Exception $e) {
			throw new Exception("Connection to Mongo failed");
		}
	}

	/**
	 * @return DBMongo|null
	 */
	public static function getInstance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new DBMongo();
		}
		return self::$instance;
	}

	/**
	 * @param $collection MongoCollection
	 * @param $uniqueID
	 * @return array
	 * @throws Exception
	 */
	protected static function get($collection, $uniqueID)
	{
		try {
			$data = $collection->findOne(array('_id' => $uniqueID));
			if (isset($data["__EMPTY__"])) {
				return array();
			} else {
				unset($data["_id"]);
				return $data;
			}
		} catch (Exception $e) {
			throw new Exception("Unknown error while querying Mongo");
		}
	}

	/**
	 * @param $collection MongoCollection
	 * @param $uniqueID
	 * @param $content
	 * @return mixed
	 * @throws Exception
	 */
	protected static function update($collection, $uniqueID, $content)
	{
		try {
			$content["_id"] = $uniqueID;
			if (!sizeof($content)) {
				$content["__EMPTY__"] = true;
			}
			return $collection->update(array('_id' => $uniqueID), $content, array('upsert' => true));
		} catch (Exception $e) {
			throw new Exception("Unknown error while updating Mongo");
		}
	}

	/*
	 * Items
	 * 
	 * */
	/**
	 * @return MongoCollection
	 */
	public static function collItems()
	{
		return self::getInstance()->database->itemData;
	}

	/**
	 * @param $uniqueID
	 * @return array
	 * @throws Exception
	 */
	public static function getItem($uniqueID)
	{
		try {
			return self::get(self::collItems(), $uniqueID);
		} catch (Exception $e) {
			throw new Exception("Unknown error");
		}
	}

	/**
	 * @param $uniqueID
	 * @param $itemData
	 * @return mixed
	 * @throws Exception
	 */
	public static function setItem($uniqueID, $itemData)
	{
		try {
			return self::update(self::collItems(), $uniqueID, $itemData);
		} catch (Exception $e) {
			throw new Exception("Unknown error");
		}
	}

	/*
	 * Item Parameters
	 * 
	 * */
	/**
	 * @return MongoCollection
	 */
	public static function collItemParams()
	{
		return self::getInstance()->database->itemParamData;
	}

	/**
	 * @param $itemID
	 * @return array
	 * @throws Exception
	 */
	public static function getItemParams($itemID)
	{
		try {
			return self::get(self::collItemParams(), $itemID);
		} catch (Exception $e) {
			throw new Exception("Unknown error");
		}
	}

	/**
	 * @param $itemID
	 * @param $itemParamData
	 * @return mixed
	 * @throws Exception
	 */
	public static function setItemParams($itemID, $itemParamData)
	{
		try {
			return self::update(self::collItemParams(), $itemID, $itemParamData);
		} catch (Exception $e) {
			throw new Exception("Unknown error");
		}
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public static function getCachableItemParams()
	{
		try {
			$paramData = array();
			$cursor = self::collItemParams()->find(array('cached' => true));

			foreach ($cursor as $doc) {
				$id = $doc["_id"];
				unset($doc["_id"]);
				$paramData[$id] = $doc;
			}
			return $paramData;
		} catch (Exception $e) {
			throw new Exception("Unknown error");
		}
	}

	/*
	 * Buildings
	 * 
	 * */
	/**
	 * @return MongoCollection
	 */
	public static function collBuildings()
	{
		return self::getInstance()->database->buildingData;
	}

	/**
	 * @param $uniqueID
	 * @return array
	 */
	public static function getBuildings($uniqueID)
	{
		return self::get(self::collBuildings(), $uniqueID);
	}

	/**
	 * @param $uniqueID
	 * @param $buildingData
	 * @return mixed
	 */
	public static function setBuildings($uniqueID, $buildingData)
	{
		return self::update(self::collBuildings(), $uniqueID, $buildingData);
	}

	/*
	 * Object Data
	 * 
	 * */
	/**
	 * @return MongoCollection
	 */
	public static function collObject()
	{
		return self::getInstance()->database->objectData;
	}

	/**
	 * @param $uniqueID
	 * @return array
	 */
	public static function getObject($uniqueID)
	{
		return self::get(self::collObject(), $uniqueID);
	}

	/**
	 * @param $uniqueID
	 * @param $objectData
	 * @return mixed
	 */
	public static function setObject($uniqueID, $objectData)
	{
		return self::update(self::collObject(), $uniqueID, $objectData);
	}

	/*
	 * Player Data
	 * 
	 * */
	/**
	 * @return MongoCollection
	 */
	public static function collPlayer()
	{
		return self::getInstance()->database->playerData;
	}

	/**
	 * @param $uniqueID
	 * @return array
	 */
	public static function getPlayer($uniqueID)
	{
		return self::get(self::collPlayer(), $uniqueID);
	}

	/**
	 * @param $uniqueID
	 * @param $playerData
	 * @return mixed
	 */
	public static function setPlayer($uniqueID, $playerData)
	{
		return self::update(self::collPlayer(), $uniqueID, $playerData);
	}

	/*
	 * Research Data
	 * 
	 * */
	/**
	 * @return MongoCollection
	 */
	public static function collResearch()
	{
		return self::getInstance()->database->researchData;
	}

	/**
	 * @param $uniqueID
	 * @return array
	 */
	public static function getResearch($uniqueID)
	{
		return self::get(self::collResearch(), $uniqueID);
	}

	/**
	 * @param $uniqueID
	 * @param $researchData
	 * @return mixed
	 */
	public static function setResearch($uniqueID, $researchData)
	{
		return self::update(self::collResearch(), $uniqueID, $researchData);
	}
}
