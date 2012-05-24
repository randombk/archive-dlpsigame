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
	 * @return DBMongo
	 */
	public static function getInstance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new DBMongo();
		}
		return self::$instance;
	}

	/**
	 * @param MongoCollection $collection
	 * @param string $uniqueID
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
	 * @param MongoCollection $collection
	 * @param string $uniqueID
	 * @param array $content
	 * @return bool
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
	 * @param string $uniqueID
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
	 * @param string $uniqueID
	 * @param array $itemData
	 * @return bool
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
	 * @param string $itemID
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
	 * @param string $itemID
	 * @param array $itemParamData
	 * @return bool
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
	 * @param string $uniqueID
	 * @return array
	 */
	public static function getBuildings($uniqueID)
	{
		return self::get(self::collBuildings(), $uniqueID);
	}

	/**
	 * @param string $uniqueID
	 * @param array $buildingData
	 * @return bool
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
	 * @param string $uniqueID
	 * @return array
	 */
	public static function getObject($uniqueID)
	{
		return self::get(self::collObject(), $uniqueID);
	}

	/**
	 * @param string $uniqueID
	 * @param array $objectData
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
	 * @param string $uniqueID
	 * @return array
	 */
	public static function getPlayer($uniqueID)
	{
		return self::get(self::collPlayer(), $uniqueID);
	}

	/**
	 * @param string $uniqueID
	 * @param array $playerData
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
	 * @param string $uniqueID
	 * @return array
	 */
	public static function getResearch($uniqueID)
	{
		return self::get(self::collResearch(), $uniqueID);
	}

	/**
	 * @param string $uniqueID
	 * @param array $researchData
	 * @return mixed
	 */
	public static function setResearch($uniqueID, $researchData)
	{
		return self::update(self::collResearch(), $uniqueID, $researchData);
	}
}
