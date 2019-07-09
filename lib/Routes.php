<?php

use App\Controllers\BaseController;
use App\Route;
use App\Models\Orders;
use App\View;
use App\Middleware;
use App\Util;
use App\Extensions;
use App\Response;
use App\ErrorHandler;

// Return Index View on /
Route::set('/', function () {
	Extensions::Load(Response::class);
	Extensions::Load(ErrorHandler::class);

	return response(200)->string($rand_2);
}, 'GET');

?>