<?php
// You can ignore all of the stuff on this page, it's sort of a setup page if you will.

namespace App;

require_once 'Classes/Parameters.php';
require_once './Controllers/_BaseController.php';

foreach (scandir('./Controllers') as $dir) {
    if ($dir != '.' && $dir != '..' && $dir != '_BaseController.php') require_once './Controllers/' . $dir;
}

function __autoload($class_name) {
    if (file_exists('./Classes/' . $class_name . '.php')) {
        require_once('./Classes/' . $class_name . '.php');
        return true;
    } else if (file_exists('./Controllers/' . $class_name . '.php')) {
        require_once('./Controllers/' . $class_name . '.php');
        return true;
    } else {
        return false;
    }
};

spl_autoload_register(function ($class_name) {
    return __autoload($class_name);
});

require_once 'Classes/Route.php';
require_once 'Classes/View.php';
require_once 'Classes/Database.php';
require_once 'lib/Routes.php';

Route::end();

?>