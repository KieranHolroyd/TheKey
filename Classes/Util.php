<?php
namespace App;


class Util {
    public static function hash($str) {
        return password_hash($str, PASSWORD_BCRYPT);
    }

    public static function hashCompare($plain, $hash) {
        return password_verify($plain, $hash);
    }

    public static function cookieGet($name, $default = null) {
        if (isset($_COOKIE[$name])) {
            return $_COOKIE[$name];
        }
        return $default;
    }

    public static function cookieSet($name, $val = "", $time = 0) {
        setcookie($name, $val, $time);
    }

    public static function getClientIP() {
        $ip = $_SERVER["REMOTE_ADDR"];
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        return $ip;
    }

    public static function redirect($url, $exit = true) {
        header("LOCATION: $url");

        if ($exit)
            die();
    }

    public static function randomStr($length, $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ") {
        if (function_exists("random_int")) {
            $str = "";
            $max = mb_strlen($chars, "8bit") - 1;
            for ($i = 0; $i < $length; ++$i) {
                $str .= $chars[random_int(0, $max)];
            }
            return $str;
        } else {
            $output = "";
            for ($i=0; $i < $length; $i++) {
                $output .= $chars[rand(0, strlen($chars) - 1)];
            }
            return $output;
        }
    }

    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function validateIP($ip) {
        return filter_var($ip, FILTER_VALIDATE_IP);
    }

    public static function validateUrl($url) {
        return (bool)filter_var(htmlspecialchars($url), FILTER_VALIDATE_URL);
    }

    public static function timeToString($time, $shortened=true) {
        if ($time < 60)
            return $time . ($shortened ? "s" : " second(s)");
        else if ($time < 3600)
            return floor($time / 60) . ($shortened ? "m" : " minute(s)");
        else if ($time < 86400)
            return floor(($time / 60) / 60) . ($shortened ? "h" : " hour(s)");
        else if ($time < 604800)
            return floor((($time / 60) / 60) / 24) . ($shortened ? "d" : " day(s)");
        else if ($time < 2419200)
            return floor(((($time / 60) / 60) / 24) / 7) . ($shortened ? "w" : " week(s)");
        else if ($time < 31536000)
            return floor(((($time / 60) / 60) / 24) / 28) . " month(s)";
        else
            return floor(((($time / 60) / 60) / 24) / 365) . ($shortened ? "y" : " year(s)");
    }

    public static function timeDistance($time, $relative=false) {
        // recalculate relative
        if (!$relative)
            $time = time() - $time;

        // big if statement
        if ($time == 0) {
            $timeStr = "Just now";
        } elseif ($time < 60) {
            $timeVal = $time;
            $timeStr = $timeVal . " second" . ($timeVal!=1?"s":"") .  " ago";
        } elseif ($time < 3600) {
            $timeVal = floor($time / 60);
            $timeStr = $timeVal . " minute" . ($timeVal!=1?"s":"") .  " ago";
        } elseif ($time < 86400) {
            $timeVal = floor(($time / 60) / 60);
            $timeStr = $timeVal . " hour" . ($timeVal!=1?"s":"") .  " ago";
        } elseif ($time < 604800) {
            $timeVal = floor((($time / 60) / 60) / 24);
            $timeStr = $timeVal . " day" . ($timeVal!=1?"s":"") .  " ago";
        } elseif ($time < 2419200) {
            $timeVal = floor(((($time / 60) / 60) / 24) / 7);
            $timeStr = $timeVal . " week" . ($timeVal!=1?"s":"") .  " ago";
        } elseif ($time < 29030400) {
            $timeVal = floor(((($time / 60) / 60) / 24) / 28);
            $timeStr = $timeVal . " month" . ($timeVal!=1?"s":"") .  " ago";
        } else {
            $timeVal = floor((((($time / 60) / 60) / 24) / 28) / 12);
            $timeStr = $timeVal . " year" . ($timeVal!=1?"s":"") .  " ago";
        }

        return $timeStr;
    }
}
