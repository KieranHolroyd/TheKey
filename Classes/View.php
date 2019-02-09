<?php

namespace App;
use eftec\bladeone\BladeOne;

class View
{
    public static function Open($v, $params = [])
    {
        if (self::exists($v)) {
            foreach($params as $key => $param) {
                if (self::isValidParamName($key)) {
                    Parameters::set($key, $params);
                    $$key = $param;
                } else {
                    throw new \Error("Invalid Parameter Name `{$key}` Passed to View `{$v}`");
                }
            }

            include_once('./Views/' . $v . '.php');
        } else if (self::existsTpl($v)) {
            $views = "./Views/";
            $cache = "./Cache/";
            $blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
            
            die($blade->run($v, $params));
        } else {
            return self::Error404();
        }
        return;
    }

    public static function exists($v) {
        return file_exists('./Views/' . $v . '.php');
    }

    public static function existsTpl($v) {
        return file_exists('./Views/' . $v . '.blade.php');
    }

    public static function Error403($message = "Error 403 ~ Forbidden")
    {
        return self::Open('_error', ['message' => $message]);
    }

    public static function Error404($message = "Error 404 ~ Page Not Found")
    {
        return self::Open('_error', ['message' => $message]);
    }

    public static function Error405($message = "Error 405 ~ Invalid Method")
    {
        return self::Open('_error', ['message' => $message]);
    }

    public static function Error500($message = "Error 500 ~ Internal Server Error")
    {
        return self::Open('_error', ['message' => $message]);
    }

    public static function isValidParamName(string $p) {
        $firstChar = str_split($p)[0];

        if (is_numeric($firstChar)) return false;
        
        if ($firstChar == '-' 
        || $firstChar == '@' 
        || $firstChar == '>' 
        || $firstChar == '<') return false;
        
        return true;
    }
}