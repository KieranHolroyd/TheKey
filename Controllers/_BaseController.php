<?php

namespace App\Controllers;

use App\View;

class BaseController
{
    public static function CreateView($view = null, $params = [])
    {
        if (!View::exists($view)) {
            View::Error500('View Doesn\'t Exist'); die();
        }
        if (!isset($view) || empty($view)) {
            View::Error500('View Not Defined'); die();
        }
        echo View::Open($view, $params);
    }

    public static function JSONResponse($j) {
        header('Content-Type: application/json');
        echo json_encode($j);
        exit;
    }

    public static function JSONResponseError($code = 400, $e = '') {
        if ($code == 400) {
            self::JSONResponse(["status" => false, "error" => "400 ~ Invalid Request", "details" => $e]);
        }
    }
}