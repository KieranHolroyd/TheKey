<?php
namespace App\Extension;

use App\Extension;
use App\Util;
use App\Database;
use App\Config;
use DB;

interface AuthProviders {
	const COOKIE = 0;
	const JWT = 1;
}

trait Authenticatable {

}

class InvalidMethodException extends \Exception {}
class InvalidIndexException extends \Exception {}

class Auth implements Extension, AuthProviders {
    // Extension::Load(Auth::class);
    // InitializeExtension() -> $table, $usernameField, $pwField
    // $user = Auth::attempt($username, $password);
    // $user = Auth::check(Auth::JWT Auth::Cookie Auth::Session);
	// $auth->signin($username, $password);
	use Authenticatable;

	public $searchableIndex = "";
	public $data = [];
	public $token = "";

	public static function InitializeExtension() {}

	public function setIndexField(string $field) {
		$this->searchableIndex = $field;
	}

	public function set(string $field, string $value, string $hash = "") {
		if ($hash != "")
			$value = hash($hash, $value);
			
		$this->data[$field] = $value;
	}

	private function withCookie() {
		
		$indexField = $this->searchableIndex;
		if (!isset($this->data[$indexField]))
			throw new InvalidIndexException("The searchable index for the auth class has not been defined. Call Auth->setIndexField(\$fieldname).");

		$user = DB::queryFirstRow("SELECT * FROM users WHERE $indexField = %s", $this->data[$indexField]);
		
		if (Config::get("allow_update_password_plaintext") === true) {
			if ($this->data["password"] === $user["password"]) {
				$user["password"] = Util::hash($user["password"]);
			}
		}
			
		
		if (!Util::hashCompare($this->data["password"], $user["password"])) 
			return false;

		$cookie = $this->createCookieSession();
		$cookie = Util::randomStr(64);
		Util::cookieSet("_authentication", $cookie);
	}

	public function signin($auth_method = JWT)
	{
		switch ($auth_method) {
			case self::COOKIE:
				return $this->withCookie();
			
			default:
				$const_name = $this->getConstNameFromValue($auth_method);
				throw new InvalidMethodException("The method {$const_name} is an invalid signin method");
		}
	}

	public function toArray()
	{
		return ["data" => $this->data, "token" => $this->token];
		//return ["email" => $this->email, "password" => $this->password, "token" => $this->token];
	}

	private function getConstNameFromValue($val = null) {
		if ($val === null) 
			return "Invalid Argument";
		$class = new \ReflectionClass(__CLASS__);
		$constants = array_flip($class->getConstants());

		return $constants[$val];
	}
}