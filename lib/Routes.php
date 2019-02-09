<?php

use App\Controllers\BaseController;
use App\Route;
use App\Models\Orders;
use App\View;
use App\Middleware;

// Return Index View on /
Route::set('/', function () {
	
	/*
	$ord = new Orders();
	$ord["name"] = "yeet";
	$ord["description"] = "yoinks";
	$ord->save();
	*/

	$ord = Orders::whereId(4);
	var_dump($ord);

	//View::Open("index", ["items" => ['meme', 'meme2']]);
    // BaseController::CreateView('index', ['parameter_name' => 'Parameter Value']);
}, 'GET');


?>