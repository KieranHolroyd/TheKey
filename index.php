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

foreach (scandir('./Controllers') as $dir) {
    if ($dir != '.' && $dir != '..' && $dir != '_BaseController.php') require_once './Controllers/' . $dir;
}

function __autoload_third() {
    $files = scandir("./Third/");

    foreach ($files as $file) {
        if ($file[0] == ".")
            continue;

        $ext = ".php";
        $length = strlen($ext);
        if ($length == 0) {
            return true;
        }
    
        if (substr($file, -$length) === $ext) {
           require_once("./Third/" . $file); 
        }
    }

}

function __autoload_classes() {
    $files = scandir("./Classes/");

    foreach ($files as $file) {
        if ($file[0] == ".")
            continue;

        $ext = ".php";
        $length = strlen($ext);
        if ($length == 0) {
            return true;
        }
    
        if (substr($file, -$length) === $ext) {
           require_once("./Classes/" . $file); 
        }
    }

}

function __autoload_models() {
    $files = scandir("./Models/");

    foreach ($files as $file) {
        if ($file[0] == ".")
            continue;

        $ext = ".php";
        $length = strlen($ext);
        if ($length == 0) {
            return true;
        }
    
        if (substr($file, -$length) === $ext) {
           require_once("./Models/" . $file); 
        }
    }

}

__autoload_third();
__autoload_classes();
__autoload_models();

use DB;
DB::$user = 'root';
DB::$password = '';
DB::$dbName = 'thekey_testing';

require_once 'lib/Routes.php';



Route::end();

?>