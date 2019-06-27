<?php
namespace App;

// Extensions class, adds the ability to load custom user made extensions.

class Extensions {
	protected $extensions = [

	];

	public static function Load($class)
	{
		self::$extensions .= $class;
	}

	public static function List(): Array
	{
		return self::$extensions;
	}

	public static function Init()
	{
		foreach($extensions as $extension) {
			$extension::InitializeExtension();
		}
	}
}

class Extension {

}