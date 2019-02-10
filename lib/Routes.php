<?php

use App\Controllers\BaseController;
use App\Route;
use App\Models\Orders;
use App\View;
use App\Middleware;
use App\Util;

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

	var_dump((bool)Util::validateUrl("https://google.com?req=<<"));

	//View::Open("index", ["items" => ['meme', 'meme2']]);
    // BaseController::CreateView('index', ['parameter_name' => 'Parameter Value']);
}, 'GET');


Route::set('/home/kieran', function () {
	
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