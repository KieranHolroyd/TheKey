<?php
namespace App;

class Config {

	private static $store = [];

	public static function apply(string $key, mixed $val)
	{
		$store[$key] = $val;
	}
	public static function get($key)
	{
		return $store[$key];
	}
}
