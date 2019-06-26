<?php

namespace App;

class Middleware {
    private static $handlers = [];
    public static $user = null;

    public static function user() {
        return self::$user;
    }

    public static function handle($ware = '') {
        if ($ware == '')
            return true;

        foreach (self::$handlers as $name => $func) {
            if ($name == $ware) {
                $ret = $func->__invoke();
                if ($ret == null)
                    return false;
                
                if (is_bool($ret))
                    return $ret;

                self::$user = $ret;
                return true;
            }
        }

        throw new \Error("Invalid middleware name ('$ware') provided");
    }

    public static function addHandler($name, $callback) {
        self::$handlers[$name] = $callback;
    }
}
