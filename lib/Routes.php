<?php

use App\Controllers\BaseController;
use App\Route;
use App\Models\Orders;
use App\View;
use App\Middleware;
use App\Util;
use App\Extensions;
use App\Extension\Auth;
use App\Response;
use App\ErrorHandler;
Extensions::Load(ErrorHandler::class);

// Return Index View on /
Route::set('/', function () {
	Extensions::Load(Response::class);
	Extensions::Load(Auth::class);

	$auth = new Auth;

	$auth->setIndexField("email");

	$auth->set("email", "kieran@nitrexdesign.co.uk");
	$auth->set("password", "itsasecret");

	$auth->signin(Auth::COOKIE);

	return response()->json($auth->toArray());
}, 'GET');