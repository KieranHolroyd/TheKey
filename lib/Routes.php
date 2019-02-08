<?php

use App\Controllers\BaseController;
use App\Route;
use App\Models\Orders;

// Route::set('', function_handler, 'METHOD');
// for testing you can use BaseController

// Return Index View on /
Route::set('', function () {
	
	/*
	$ord = new Orders();
	$ord["name"] = "yeet";
	$ord["description"] = "yoinks";
	$ord->save();
	*/

	$ord = Orders::whereOne("id = %i", 4);
	$ord["name"] = "yikers";
	$ord->save();

    // BaseController::CreateView('index', ['parameter_name' => 'Parameter Value']);
}, 'GET');

?>