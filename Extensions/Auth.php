<?php

namespace App\Extension;

use App\Extension;
use App\Util;
use App\Database;
use App\Config;
use App\Request;
use DB;

interface AuthProviders
{
	const LOGIN = 0;
	const SESSION = 1;
	const JWT = 2;
}

trait Authenticatable
{
}

class InvalidMethodException extends \Exception
{
}
class InvalidIndexException extends \Exception
{
}

class Auth implements Extension, AuthProviders
{
	// Extension::Load(Auth::class);
	// InitializeExtension() -> $table, $usernameField, $pwField
	// $user = Auth::attempt($username, $password);
	// $user = Auth::check(Auth::JWT Auth::Cookie Auth::Session);
	// $auth->signin($username, $password);
	use Authenticatable;

	public $searchableIndex = "";
	public $data = [];
	public $token = "";

	public static function InitializeExtension()
	{
	}

	public function setIndexField(string $field)
	{
		$this->searchableIndex = $field;
	}

	public function set(string $field, $value, string $hash = "")
	{
		if ($hash != "")
			$value = hash($hash, $value);

		$this->data[$field] = $value;
	}

	private function withLogin()
	{

		$username = $this->data[$this->searchableIndex];
		if (!$username) {
			return false;
		}

		$user = DB::queryFirstRow("SELECT * FROM users WHERE $this->searchableIndex = %s", $username);

		if (!$user) {
			return false;
		}

		$password = $this->data["password"];

		if (getenv("DEBUG")) {
			if ($password === $user["password"]) {
				$user["password"] = Util::hash($password);

				DB::update("users", [
					"password" => $user["password"]
				], "id = %i", $user["id"]);
			}
		}

		if (!Util::hashCompare($password, $user["password"]))
			return false;

		$session = Util::randomStr(64);

		$user["session"] = $session;
		DB::update("users", [
			"session" => $session
		], "id = %i", $user["id"]);

		return $user;
	}

	private function withSession()
	{
		$session = $this->data["session"];
		if (!$session) {
			return false;
		}

		$user = DB::queryFirstRow("SELECT * FROM users WHERE users.session = %s", $session);

		if (!$user) {
			return false;
		}

		return $user;
	}

	public function signin($auth_method = self::JWT)
	{
		match ($auth_method) {
			self::LOGIN => $this->withLogin(),
			self::SESSION => $this->withSession(),
			default => $this->throwMethodException($auth_method)
			
		};
	}

	private function throwMethodException($auth_method)
	{
		$const_name = $this->getConstNameFromValue($auth_method);
		throw new InvalidMethodException("The method {$const_name} is an invalid signin method");
	}

	public function toArray()
	{
		return ["data" => $this->data, "token" => $this->token];
		//return ["email" => $this->email, "password" => $this->password, "token" => $this->token];
	}

	private function getConstNameFromValue($val = null)
	{
		if ($val === null)
			return "Invalid Argument";
		$class = new \ReflectionClass(__CLASS__);
		$constants = array_flip($class->getConstants());

		return $constants[$val];
	}
}
