<?php
namespace App {
	// Extensions class, adds the ability to load custom user made extensions.
	
	class Extensions {
		protected static $extensions = [
	
		];
	
		public static function Load($class)
		{
			self::$extensions[] = $class;
			$class::InitializeExtension();
		}
	
		public static function List(): Array
		{
			return self::$extensions;
		}
	
	}
	

	interface Extension {
		public static function InitializeExtension();
	}
}