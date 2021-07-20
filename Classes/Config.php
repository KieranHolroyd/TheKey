<?php
namespace App;

class Config {

	private static $store = [];

	public static function apply(string $key, mixed $val)
	{
		self::$store[$key] = $val;
	}
	public static function get($key)
	{
		return self::$store[$key];
	}
}
