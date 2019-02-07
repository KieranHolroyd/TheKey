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

    public static function set($route, $function, $method = 'GET')
    {
        self::$validRoutes[] = ['route' => $route, 'method' => $method];

        if (Request::inputGet('_url', '') == $route && $_SERVER['REQUEST_METHOD'] == $method) {
            $function->__invoke();
            self::$foundRoute = true;
        }
    }

    public static function end()
    {
        foreach(self::$validRoutes as $r) {
            if ($r['route'] == Request::inputGet('_url', '') && !self::$foundRoute) return View::Error405();
        }
        if (!self::$foundRoute) {
            return View::Error404();
        }
    }
}