<?php

use App\Controllers\BaseController;
use App\Controllers\APIController;
use App\Route;

// Route::set('', function_handler, 'METHOD');
// for testing you can use BaseController

// Return Index View on /
Route::set('', function () {
    BaseController::CreateView('index', ['parameter_name' => 'Parameter Value']);
}, 'GET');

?>