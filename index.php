<?php
// You can ignore all of the stuff on this page, it's sort of a setup page if you will.

namespace App;

require_once 'Classes/Parameters.php';
require_once './Controllers/_BaseController.php';

foreach (scandir('./Controllers') as $dir) {
    if ($dir != '.' && $dir != '..' && $dir != '_BaseController.php') require_once './Controllers/' . $dir;
}

function __autoload() {
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

__autoload();


require_once 'Classes/Route.php';
require_once 'Classes/Request.php';
require_once 'Classes/View.php';
require_once 'Classes/Database.php';
require_once 'lib/Routes.php';

Route::end();

?>