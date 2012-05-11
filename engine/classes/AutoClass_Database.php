<?php
/*
 * (C) Copyright 2012 David J. W. Li
 * Project DLPSIGAME
 */

class Database extends PDO {
	private $error;
	private $sql;
	private $bind;
	private $errorCallbackFunction;
	private $errorMsgFormat;

	public function __construct($dsn, $user = "", $passwd = "") {
		$options = array(PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => true);

		try {
			parent::__construct($dsn, $user, $passwd, $options);
		} catch (PDOException $e) {
			$this->error = $e->getMessage();
		}
	}

	public function bindArrayValue($req, $array, $typeArray = false) {
		if (is_object($req) && ($req instanceof PDOStatement)) {
			foreach ($array as $key => $value) {
				if ($typeArray) {
					$req->bindValue($key, $value, $typeArray[$key]);
				} else {
					if (is_int($value))
						$param = PDO::PARAM_INT;
					elseif (is_bool($value))
						$param = PDO::PARAM_BOOL;
					elseif (is_null($value))
						$param = PDO::PARAM_NULL;
					elseif (is_string($value))
						$param = PDO::PARAM_STR;
					else
						$param = PDO::PARAM_STR;
					$req->bindValue($key, $value, $param);
				}
			}
		}
	}

	private function debug() {
		if (!empty($this->errorCallbackFunction)) {
			$error = array("Error" => $this->error);
			if (!empty($this->sql))
				$error["SQL Statement"] = $this->sql;
			if (!empty($this->bind))
				$error["Bind Parameters"] = trim(print_r($this->bind, true));

			$backtrace = debug_backtrace();
			if (!empty($backtrace)) {
				foreach ($backtrace as $info) {
					if ($info["file"] != __FILE__)
						$error["Backtrace"] = $info["file"] . " at line " . $info["line"];
				}
			}

			$msg = "";
			if ($this->errorMsgFormat == "html") {
				if (!empty($error["Bind Parameters"]))
					$error["Bind Parameters"] = "<pre>" . $error["Bind Parameters"] . "</pre>";
				$css = trim(file_get_contents(dirname(__FILE__) . "/error.css"));
				$msg .= '<style type="text/css">' . "\n" . $css . "\n</style>";
				$msg .= "\n" . '<div class="db-error">' . "\n\t<h3>SQL Error</h3>";
				foreach ($error as $key => $val)
					$msg .= "\n\t<label>" . $key . ":</label>" . $val;
				$msg .= "\n\t</div>\n</div>";
			} elseif ($this->errorMsgFormat == "text") {
				$msg .= "SQL Error\n" . str_repeat("-", 50);
				foreach ($error as $key => $val)
					$msg .= "\n\n$key:\n$val";
			}

			$func = $this->errorCallbackFunction;
			$func($msg);
		}
	}

	public function delete($table, $where, $bind = "") {
		$sql = "DELETE FROM " . $table . " WHERE " . $where . ";";
		$this->run($sql, $bind);
	}

	private function filter($table, $info) {
		$driver = $this->getAttribute(PDO::ATTR_DRIVER_NAME);
		if ($driver == 'sqlite') {
			$sql = "PRAGMA table_info('" . $table . "');";
			$key = "name";
		} elseif ($driver == 'mysql') {
			$sql = "DESCRIBE " . $table . ";";
			$key = "Field";
		} else {
			$sql = "SELECT column_name FROM information_schema.columns WHERE table_name = '" . $table . "';";
			$key = "column_name";
		}

		if (false !== ($list = $this->run($sql))) {
			$fields = array();
			foreach ($list as $record)
				$fields[] = $record[$key];
			return array_values(array_intersect($fields, array_keys($info)));
		}
		return array();
	}

	private function cleanup($bind) {
		if (!is_array($bind)) {
			if (!empty($bind))
				$bind = array($bind);
			else
				$bind = array();
		}
		return $bind;
	}

	public function insert($table, $info, $needID = false) {
		$fields = $this->filter($table, $info);
		$sql = "INSERT INTO " . $table . " (" . implode($fields, ", ") . ") VALUES (:" . implode($fields, ", :") . ");";
		$bind = array();
		foreach ($fields as $field)
			$bind[":$field"] = $info[$field];
		if ($needID) {
			if ($this->run($sql, $bind)) {
				return $this->lastInsertId();
			} else {
				return null;
			}
		}
		return $this->run($sql, $bind);
	}

	public function run($sql, $bind = "", $assoc = true) {
		$this->sql = trim($sql);
		$this->bind = $this->cleanup($bind);
		$this->error = "";

		try {
			$pdostmt = $this->prepare($this->sql);
			$this->bindArrayValue($pdostmt, $this->bind);
			if ($pdostmt->execute() !== false) {
				if (preg_match("/^(" . implode("|", array("select", "describe", "pragma")) . ") /i", $this->sql))
					return $pdostmt->fetchAll($assoc ? PDO::FETCH_ASSOC : PDO::FETCH_NUM);
				elseif (preg_match("/^(" . implode("|", array("delete", "insert", "update", "replace")) . ") /i", $this->sql))
					return $pdostmt->rowCount();
			}
		} catch (PDOException $e) {
			$this-> error = $e->getMessage();
			$this-> debug();
			return false;
		}
	}

	public function select($table, $where = "", $bind = "", $fields = "*", $opt = "") {
		$sql = "SELECT " . $fields . " FROM " . $table;
		if (!empty($where))
			$sql .= " WHERE " . $where;
		if (!empty($opt))
			$sql .= " " . $opt;
		$sql .= ";";
		return $this->run($sql, $bind);
	}

	public function selectCell($table, $where = "", $bind = "", $fields = "*", $opt = "") {
		$sql = "SELECT " . $fields . " FROM " . $table;
		if (!empty($where))
			$sql .= " WHERE " . $where;
		if (!empty($opt))
			$sql .= " " . $opt;
		$sql .= ";";
		$result = $this->run($sql, $bind, false);
		return sizeof($result) ? $result[0][0] : null;
	}

	public function selectTop($table, $where = "", $bind = "", $fields = "*", $opt = "") {
		$result = $this->select($table, $where, $bind, $fields, $opt);
		return sizeof($result) ? $result[0] : null;
	}

	public function setErrorCallbackFunction($errorCallbackFunction, $errorMsgFormat = "html") {
		//Variable functions for won't work with language constructs such as echo and print, so these are replaced with print_r.
		if (in_array(strtolower($errorCallbackFunction), array("echo", "print")))
			$errorCallbackFunction = "print_r";

		if (function_exists($errorCallbackFunction)) {
			$this->errorCallbackFunction = $errorCallbackFunction;
			if (!in_array(strtolower($errorMsgFormat), array("html", "text")))
				$errorMsgFormat = "html";
			$this->errorMsgFormat = $errorMsgFormat;
		}
	}

	public function update($table, $info, $where, $bind = "") {
		$fields = $this->filter($table, $info);
		$fieldSize = sizeof($fields);

		$sql = "UPDATE " . $table . " SET ";
		for ($f = 0; $f < $fieldSize; ++$f) {
			if ($f > 0)
				$sql .= ", ";
			$sql .= $fields[$f] . " = :update_" . $fields[$f];
		}
		$sql .= " WHERE " . $where . ";";

		$bind = $this->cleanup($bind);
		foreach ($fields as $field)
			$bind[":update_$field"] = $info[$field];

		return $this->run($sql, $bind);
	}

}
