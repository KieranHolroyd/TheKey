<?php

use App\Route;
use App\Middleware;
use App\Util;
use App\Extensions;
use App\Extension\Auth;
use App\Response;
use App\ErrorHandler;
use App\Extension\Time;
use App\View;

Extensions::Load(ErrorHandler::class);
Extensions::Load(Response::class);
Extensions::Load(Auth::class);
Extensions::Load(Time::class);


Route::set('/', function () {
	return View::Open('index', ["orders" => ['hello', 'world']]);
}, 'GET');
