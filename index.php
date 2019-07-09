<?php
// You can ignore all of the stuff on this page, it's sort of a setup page if you will.

namespace App;

require_once './Controllers/_BaseController.php';


/*
$dir = str_replace("\\", "/", __DIR__);
$uri = $_SERVER["REQUEST_URI"];

$dirParts = explode("/", $dir);
$uriParts = explode("/", $uri);


$dif = array_diff($uriParts, $dirParts);
$endpoint = join("/", $dif);
$_GET["_url"] = $endpoint;
*/

require('vendor/autoload.php');

use DB;
DB::$user = 'root';
DB::$password = '';
DB::$dbName = 'thekey_testing';

require_once 'lib/Routes.php';



Route::end();

?>
