<?php

namespace App;

class Middleware {
    private static $handlers = [];

    public static function handle($ware = '') {
        if ($ware == '')
            return true;

        foreach (self::$handlers as $name => $func) {
            if ($name == $ware) {
                return $func->__invoke();
            }
        }

        throw new \Error("Invalid middleware name ('$ware') provided");
    }

    public static function addHandler($name, $callback) {
        self::$handlers[$name] = $callback;
    }
}