<?php

namespace App;

class Request {
    public static function input($key, $default = null) {
        if (isset($_REQUEST[$key])) {
            return $_REQUEST[$key];
        }

        return $default;
    }

    public static function inputGet($key, $default = null) {
        if (isset($_GET[$key]))
            return $_GET[$key];
        return $default;
    }

    public static function inputPost($key, $default = null) {
        if (isset($_POST[$key]))
            return $_POST[$key];
        return $default;
    }

}