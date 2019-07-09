<?php

namespace App\Controllers;

use App\View;

class BaseController
{
    /**
     * @deprecated
     */
    public static function CreateView($view = null, $params = [])
    {
        trigger_error("BaseController class and it's methods (CreateView) are deprecated and will be removed in the future.", E_USER_DEPRECATED);

        if (!View::exists($view)) {
            View::Error500('View Doesn\'t Exist'); die();
        }
        if (!isset($view) || empty($view)) {
            View::Error500('View Not Defined'); die();
        }
        echo View::Open($view, $params);
    }

    /**
     * @deprecated
     */
    public static function JSONResponse($j) {
        trigger_error("BaseController class and it's methods (JSONResponse) are deprecated and will be removed in the future.", E_USER_DEPRECATED);

        header('Content-Type: application/json');
        echo json_encode($j);
        exit;
    }

    /**
     * @deprecated
     */
    public static function JSONResponseError($code = 400, $e = '') {
        trigger_error("BaseController class and it's methods (JSONResponseError) are deprecated and will be removed in the future.", E_USER_DEPRECATED);

        if ($code == 400) {
            self::JSONResponse(["status" => false, "error" => "400 ~ Invalid Request", "details" => $e]);
        }
    }
}