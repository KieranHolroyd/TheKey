<?php

use App\Controllers\BaseController;
use App\Route;
use App\Models\Orders;
use App\View;
use App\Middleware;
use App\Util;
use App\Extensions;
use App\Response;

// Return Index View on /
Route::set('/', function () {
	Extensions::Load(Response::class);
	$rand = random_int(1000, 10000000);
	$rand_2 = random_int(1000, 10000000);
	return response(200)->json([$rand => $rand_2]);
}, 'GET');

?>