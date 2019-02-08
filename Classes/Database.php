<?php

namespace App;
use DB;

class Database {

	private static $instance = null;

	private $host = 'localhost';
	private	$user = 'root';
	private	$pass = '';
	private	$name = 'thekey_testing';

	public function __construct()
	{
		$this->conn = new \PDO("mysql:host={$this->host};
	    dbname={$this->name}", $this->user,$this->pass,
	    array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));

		$this->conn->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
	}

	public static function getInstance()
	{
		if (self::$instance == null)
		{
			self::$instance = new Database();
		}

		return self::$instance;
	}

	public function getConnection() 
	{
		return $this->conn;
	}

	public static function queryArgs($fnc) {
		$args = func_get_args();
		
		array_shift($args);
		call_user_func_array(array(DB::getMDB(), $fnc), $args);
		
	}

	public static function query($q = "", $args = []) {
		$inst = self::getInstance();
		$conn = $inst->getConnection();

		if (trim($q) != "") {
			$stmt = $conn->prepare($q);
			if($stmt->execute($args)) {
				return $stmt->fetchAll();
			} else {
				return $conn->errorInfo();
			}
		} else {
			return false;
		}
	}

	public static function queryOne($q = "", $args = []) {
		$inst = self::getInstance();
		$conn = $inst->getConnection();
		
		if (trim($q) != "") {
			$stmt = $conn->prepare($q);
			if($stmt->execute($args)) {
				return $stmt->fetch();
			} else {
				return $conn->errorInfo();
			}
		} else {
			return false;
		}
	}
}