<?php

namespace App;

class Parameters {
	private static $params = [];

	public static function get($key = '') {
		if ($key == '') {
			throw new \Error('Invalid Parameter Key Get');
		}
		return self::$params[$key];
	}

	public static function set($key = '', $val = '') {
		if ($key == '') {
			throw new \Error('Invalid Parameter Key Set');
		}
		if ($val == '') {
			throw new \Error('Invalid Parameter Value Set');
		}
		self::$params[$key] = $val;

		return self::$params[$key];
	}
}