<?php
namespace App\Extension;

use App\Extension;
use App\Util;

interface AuthProviders {
	const COOKIE 	= 0;
	const JWT 		= 1;
}

trait Authenticatable {

}

class InvalidMethodException extends \Exception {}

class Auth implements Extension, AuthProviders {
    // Extension::Load(Auth::class);
    // InitializeExtension() -> $table, $usernameField, $pwField
    // $user = Auth::attempt($username, $password);
    // $user = Auth::check(Auth::JWT Auth::Cookie Auth::Session);
	// $auth->signin($username, $password);
	use Authenticatable;

	public $email = "";
	public $password = "";
	public $token = "";

	public static function InitializeExtension() {}

	public function signin($auth_method = JWT)
	{
		switch ($auth_method) {
			case self::COOKIE:
				$this->token = Util::randomStr(64);
				break;
			
			default:
				$const_name = $this->getConstNameFromValue($auth_method);
				throw new InvalidMethodException("The method {$const_name} is an invalid signin method");
		}
	}

	public function toArray()
	{
		return ["email" => $this->email, "password" => $this->password, "token" => $this->token];
	}

	private function getConstNameFromValue($val = null) {
		if ($val === null) 
			return "Invalid Argument";
		$class = new \ReflectionClass(__CLASS__);
		$constants = array_flip($class->getConstants());

		return $constants[$val];
	}
}