<?php

namespace App;

class ErrorHandler implements Extension
{

	static function InitializeExtension()
	{
        $handler = new self;
        
        set_error_handler(array($handler, 'error'));
        register_shutdown_function(array($handler, 'shutdown'));
    }
    
    private function writeLine(string $string = "") {
        $this->write("$string\n");
    }

    private function write(string $string) {
        echo $string;
    }

    public function shutdown() {
        $lastError = error_get_last();

        if ($lastError == NULL)
            return;
        
        $type = $lastError["type"];
        $msg = $lastError["message"];
        $file = $lastError["file"];
        $line = $lastError["line"];

        $this->error($type, $msg, $file, $line);
    } 
	
	public function error($errno, $errstr, $errfile=false, $errline=false)
	{
        $this->writeLine("<pre>");
        $type = "An unknown error";
        
        switch ($errno) {
            case E_USER_ERROR:
            case E_ERROR:
                $type = "FATAL ERROR";
                break;
            case E_USER_WARNING:
            case E_WARNING:
                $type = "A warning";
                break;
            case E_USER_NOTICE:
            case E_NOTICE:
                $type = "A notice";
                break;
            case E_USER_DEPRECATED:
            case E_DEPRECATED:
                $type = "A deprecated function";
                break;
            default:
                $type = "An unknown error [$errno]";
                break;
        }


        $this->writeLine(""); // weird bug where the first line would get fucked up sometimes
        $this->writeLine("$type was encountered");
        $this->write("[$errno] $errstr");
        if ($errfile)
            $this->writeLine(" in file: $errfile on line $errline");

        $this->writeLine("\nCall stack:");
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 15);
        foreach ($backtrace as $b) {
            $file = $b["file"] ?? "";
            $line = $b["line"] ?? 0;
            $func = $b["function"] ?? "";
            $class = $b["class"] ?? "";
            $args = $b["args"] ?? [];
            $argStr = "";

            foreach ($args as $arg) {
                $argStr .= $this->convert($arg);
                $argStr .= ",";
            }
            $argStr = substr($argStr, 0, strlen($argStr)-1);

            $this->writeLine("[L:$line] $file - $class\\$func($argStr)");
        }

        $this->writeLine("</pre>");
    }
    
    public function convert($value): string {
        if (is_string($value))
            return '"' . $value . '"';
        
        if (is_numeric($value))
            return strval($value);
        
        if (is_object($value)) {
            return gettype($value);
        }

        if (is_array($value)) {
            $str = "";
            foreach ($value as $v) {
                $str .= $this->convert($v);
            }
            return $str;
        }

        return "unknown type";
    }
	
}