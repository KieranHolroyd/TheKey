<?php

namespace App\Extension;

use App\Extension;

class Time implements Extension
{

    public static function InitializeExtension()
	{
    }
    
    public static function now() {
        return self::toTime(time());
    }

    public static function toSeconds($str) {
        return strtotime($str);
    }

    public static function toTime($timestamp) {
        return date("Y-m-d H:i:s", $timestamp);
    }

    public static function distance($a, $b) {
        $a = self::toSeconds($a);
        $b = self::toSeconds($b);

        return abs($a - $b);
    }
}
