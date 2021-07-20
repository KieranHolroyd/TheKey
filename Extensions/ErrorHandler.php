<?php
namespace App;

use App\Config;

error_reporting(0);

class ErrorHandler implements Extension
{

    private $initialized = false;

	static function InitializeExtension()
	{
        $handler = new self;
        
        set_error_handler(array($handler, 'error'));
        set_exception_handler(array($handler, 'exception'));
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

    private function parseExceptionTrace(Array $arr): string {
        $string = "";
        foreach ($arr as $k => $v) {
            $v = (object) $v;
            $string .= "[L:{$v->line}] {$v->file}::{$v->function}\n";
        }
        return $string;
    }

    public function exception($e, $prev_e = null, $only_trace = false)
    {
        $this->initTemplate();
        $this->write("<pre>");
        if (!$only_trace)
            $this->writeLine("Exception Encountered: " . $e->getMessage());
        
        $this->writeLine($this->parseExceptionTrace($e->getTrace()));

        if ($prev_e !== null) {
            $this->exception($prev_e, $prev_e->getPrevious(), true);
        }
        $this->write("</pre>");
    }
	
	public function error($errno, $errstr, $errfile=false, $errline=false)
	{
        $this->initTemplate();
        $this->write("<pre>");
        $type = "An unknown error";
        
        switch ($errno) {
            case E_USER_ERROR:
            case E_ERROR:
                $type = "<span class='err'>Fatal error</span>";
                break;
            case E_USER_WARNING:
            case E_WARNING:
                $type = "<span class='warning'>A warning</span>";
                break;
            case E_USER_NOTICE:
            case E_NOTICE:
                $type = "<span class='notice'>A notice</span>";
                break;
            case E_USER_DEPRECATED:
            case E_DEPRECATED:
                $type = "<span class='notice'>A deprecated function</span>";
                break;
            default:
                $type = "<span class='err'>An unknown error [$errno]</span>";
                break;
        }

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
        $this->write("</pre>");
    }

    private function initTemplate() {
        if ($this->initialized)
            return;

        $this->initialized = true;
        $this->write("<style>*{padding: 0; margin: 0;} pre {padding: 10px;margin:10px;border-radius:10px;box-shadow: 0 2px 10px 0 rgba(0,0,0,0.1);width:calc(100vw - 40px);white-space:pre-line;word-wrap: break-word;overflow:hidden !important;text-align:left !important;}
        .err{color:red;font-weight:bold;}
        .notice{color:blue;font-weight:bold;}
        .warning{color:orange;font-weight:bold;}</style>");
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