<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

/**
 * Class RDBMSWrapper
 */
class RDBMSWrapper extends PDO
{
	public $error = "";

	/**
	 * @param string $dsn
	 * @param string $user
	 * @param string $passwd
	 */
	public function __construct($dsn, $user = "", $passwd = "")
	{
		$options = array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => true);

		try {
			parent::__construct($dsn, $user, $passwd, $options);
		} catch (PDOException $e) {
			$this->error = $e->getMessage();
		}
	}
}

/**
 * Class DBMySQL
 */
class DBMySQL
{
	private static $instance = null;
	public static $sql;
	public static $bind;

	/**
	 * @return RDBMSWrapper
	 */
	public static function getInstance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new RDBMSWrapper("mysql:host={$GLOBALS['_RDBMS']['host']};port={$GLOBALS['_RDBMS']['port']};dbname={$GLOBALS['_RDBMS']['databasename']}", $GLOBALS["_RDBMS"]["user"], $GLOBALS["_RDBMS"]["userpw"]);
		}
		return self::$instance;
	}

	/**
	 * @param PDOStatement $req
	 * @param array $array
	 * @param bool $typeArray
	 */
	public static function bindArrayValue($req, $array, $typeArray = false)
	{
		if (is_object($req) && ($req instanceof PDOStatement)) {
			foreach ($array as $key => $value) {
				if ($typeArray) {
					$req->bindValue($key, $value, $typeArray[$key]);
				} else {
					if (is_int($value))
						$param = PDO::PARAM_INT;
					elseif (is_bool($value))
						$param = PDO::PARAM_BOOL; elseif (is_null($value))
						$param = PDO::PARAM_NULL; elseif (is_string($value))
						$param = PDO::PARAM_STR; else
						$param = PDO::PARAM_STR;
					$req->bindValue($key, $value, $param);
				}
			}
		}
	}

	/**
	 * @param string $statement
	 * @param array $driver_options
	 * @return PDOStatement
	 */
	public static function prepare($statement, $driver_options = array())
	{
		return self::getInstance()->prepare($statement, $driver_options);
	}

	/**
	 * @param string $statement
	 * @return int
	 */
	public static function exec($statement)
	{
		return self::getInstance()->exec($statement);
	}

	/**
	 * @param string $table
	 * @param string $where
	 * @param string $bind
	 * @return array|bool|int
	 */
	public static function delete($table, $where, $bind = "")
	{
		$sql = "DELETE FROM " . $table . " WHERE " . $where . ";";
		return self::run($sql, $bind);
	}

	/**
	 * @param array|string $bind
	 * @return array
	 */
	public static function cleanup($bind)
	{
		if (!is_array($bind)) {
			if (!empty($bind))
				$bind = array($bind);
			else
				$bind = array();
		}
		return $bind;
	}

	/**
	 * @param string $table
	 * @param array $info
	 * @param bool $needID
	 * @return array|bool|int|null|string
	 */
	public static function insert($table, $info, $needID = false)
	{
		$fields = array_keys($info);
		$sql = "INSERT INTO " . $table . " (" . implode(", ", $fields) . ") VALUES (:" . implode(", :", $fields) . ");";
		$bind = array();
		foreach ($fields as $field)
			$bind[":$field"] = $info[$field];
		if ($needID) {
			if (self::run($sql, $bind)) {
				return self::getInstance()->lastInsertId();
			} else {
				return null;
			}
		}
		return self::run($sql, $bind);
	}

	/**
	 * @param string $table
	 * @param string $where
	 * @param string $bind
	 * @param string $fields
	 * @param string $opt
	 * @return array|bool|int
	 */
	public static function select($table, $where = "", $bind = "", $fields = "*", $opt = "")
	{
		$sql = "SELECT " . $fields . " FROM " . $table;
		if (!empty($where))
			$sql .= " WHERE " . $where;
		if (!empty($opt))
			$sql .= " " . $opt;
		$sql .= ";";
		return self::run($sql, $bind);
	}

	/**
	 * @param string $table
	 * @param string $where
	 * @param string $bind
	 * @param string $fields
	 * @param string $opt
	 * @return mixed
	 */
	public static function selectCell($table, $where = "", $bind = "", $fields = "*", $opt = "")
	{
		$sql = "SELECT " . $fields . " FROM " . $table;
		if (!empty($where))
			$sql .= " WHERE " . $where;
		if (!empty($opt))
			$sql .= " " . $opt;
		$sql .= ";";
		$result = self::run($sql, $bind, false);
		return sizeof($result) ? $result[0][0] : null;
	}

	/**
	 * @param string $table
	 * @param string $where
	 * @param string $bind
	 * @param string $fields
	 * @param string $opt
	 * @return mixed
	 */
	public static function selectTop($table, $where = "", $bind = "", $fields = "*", $opt = "")
	{
		$result = self::select($table, $where, $bind, $fields, $opt);
		return sizeof($result) ? $result[0] : null;
	}

	/**
	 * @param string $table
	 * @param array $info
	 * @param string $where
	 * @param string $bind
	 * @return array|bool|int
	 */
	public static function update($table, $info, $where, $bind = "")
	{
		$fields = array_keys($info);
		$fieldSize = sizeof($fields);

		$sql = "UPDATE " . $table . " SET ";
		for ($f = 0; $f < $fieldSize; ++$f) {
			if ($f > 0)
				$sql .= ", ";
			$sql .= $fields[$f] . " = :update_" . $fields[$f];
		}
		$sql .= " WHERE " . $where . ";";

		$bind = self::cleanup($bind);
		foreach ($fields as $field)
			$bind[":update_$field"] = $info[$field];

		return self::run($sql, $bind);
	}

	/**
	 * @param string $sql
	 * @param string $bind
	 * @param bool $assoc
	 * @return array|bool|int
	 */
	public static function run($sql, $bind = "", $assoc = true)
	{
		self::$sql = trim($sql);
		self::$bind = self::cleanup($bind);

		try {
			$pdostmt = self::prepare(self::$sql);
			self::bindArrayValue($pdostmt, self::$bind);
			if ($pdostmt->execute() !== false) {
				if (preg_match("/^(" . implode("|", array("select", "describe", "pragma")) . ") /i", self::$sql))
					return $pdostmt->fetchAll($assoc ? PDO::FETCH_ASSOC : PDO::FETCH_NUM);
				elseif (preg_match("/^(" . implode("|", array("delete", "insert", "update", "replace")) . ") /i", self::$sql))
					return $pdostmt->rowCount();
			}
		} catch (PDOException $e) {
			self::getInstance()->error = $e->getMessage();
			return false;
		}
		return false;
	}

	/**
	 * @return string
	 */
	public static function getError()
	{
		return self::getInstance()->error;
	}
}