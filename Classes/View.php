<?php

namespace App;

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
            include_once('./Views/' . $v . '.page.php');
        } else {
            return self::Error404();
        }
        return;
    }

    public static function exists($v) {
        return file_exists('./Views/' . $v . '.page.php');
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