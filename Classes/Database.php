<?php

namespace App;

class Database {

	private static $instance = null;

	private $host = 'localhost';
	private	$user = 'root';
	private	$pass = '';
	private	$name = 'loyaltyrp';

	private function __construct()
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
}