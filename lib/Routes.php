<?php

use App\Controllers\BaseController;
use App\Route;

// Route::set('', function_handler, 'METHOD');
// for testing you can use BaseController

// Return Index View on /
Route::set('', function () {
	$order = App\Database::queryOne('SELECT * FROM orders ORDER BY id DESC');

	echo $order->name;

	// echo json_encode($order);

    // BaseController::CreateView('index', ['parameter_name' => 'Parameter Value']);
}, 'GET');

?>