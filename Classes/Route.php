<?php
/**
 * Created by PhpStorm.
 * User: kiera
 * Date: 13/01/2019
 * Time: 03:29
 */

namespace App;

class Route
{
    public static $validRoutes = [];
    public static $foundRoute = false;

    private static $url = "";

    // if middleware fails
    private static function fail() {
        return View::Error403();
    }

    private static function validateRoute($route) {
        self::$url = Request::inputGet('_url', '');

        self::$url = rtrim(self::$url, '/');
        $route = rtrim($route, '/');

        if (strlen(self::$url) > 0) {
            if (self::$url[0] == "/") {
                self::$url = substr(self::$url, 1);
            }
        }
        if (strlen($route) > 0) {
            if ($route[0] == "/") {
                $route = substr($route, 1);
            }
        }

        return $route;
    }
     
    public static function set($route, $function, $method = 'GET', $middleware = [], $failureFnc = null)
    {
        self::$validRoutes[] = ['route' => $route, 'method' => $method];

        $route = self::validateRoute($route);

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            http_response_code(200);exit;
        }

        if (self::$url == $route && $_SERVER['REQUEST_METHOD'] == $method) {

            foreach ($middleware as $mw) {
                if (!Middleware::handle($mw)) {
                    if ($failureFnc == null) {
                        return self::fail();
                    } else {
                        if (is_string($failureFnc)) {
                            die($failureFnc);
                        } else if (is_array($failureFnc)) {
                            \App\Controllers\BaseController::JSONResponse($failureFnc);
                        } else if (!is_callable($failureFnc)) {
                            throw new \Error("Middleware callback is not valid!");
                        }
                        $failureFnc->__invoke();
                    }
                }
            } 

            $function->__invoke();
            self::$foundRoute = true;
        }
    }

    public static function end()
    {
        foreach(self::$validRoutes as $r) {
            if ($r['route'] == self::$url && !self::$foundRoute) return View::Error405();
        }
        if (!self::$foundRoute) {
            return View::Error404();
        }
    }
}