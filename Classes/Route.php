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

    private static function match($path, $url) {


        $pregMatch = "/^" . str_replace ("/", "\/", preg_replace("/:([a-zA-Z]+)/", "(?<$1>[0-9a-z-_]+)", $path)) . "\/?$/i";

        $val = array();

        // match
        preg_match($pregMatch, $url, $val);

        $matches = array();

        if (count($val) > 0) {
            foreach ($val as $k => $v) {
                if (gettype($k) == "string")
                    $matches[$k] = $v;
            }

            return $matches;
        } else {
            return null;
        }
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
     
    public static function set($route, $function, $method = 'GET', $middleware = [], $fail_event = null)
    {
        self::$validRoutes[] = ['route' => $route, 'method' => $method];

        $route = self::validateRoute($route);

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            http_response_code(200);exit;
        }
        $matches = self::match($route, self::$url);
        if ($matches !== NULL && $_SERVER['REQUEST_METHOD'] == $method) {

            self::handleMiddleware($middleware, $fail_event);

            $function->__invoke($matches)->send();
            self::$foundRoute = true;
        }
    }
    private static function handleMiddleware($middleware, $fail_event) {
        foreach ($middleware as $mw) {
            if (!Middleware::handle($mw)) {
                if ($fail_event == null) {
                    return self::fail();
                } else {
                    switch (true) {
                        case is_string($fail_event):
                            // failure is just a string message
                            response(403, $fail_event)->end();
                        case is_array($fail_event):
                            // failure is json
                            response(403)->json($fail_event)->end();
                        case is_callable($fail_event):
                            // failure fnc must return a response
                            $fail_event->__invoke()->end();
                        default:
                            throw new \Error("Middleware callback is not valid!");
                    }
                }
                
            }
        }
    }

    public static function end()
    {
        foreach(self::$validRoutes as $r) {
            if ($r['route'] == self::$url && !self::$foundRoute) return View::Error405();
        }
        if (!self::$foundRoute) {
            response(404)->json(["success" => false, "error" => "page not found"])->send();
        }
    }
}