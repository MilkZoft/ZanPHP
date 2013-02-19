<?php
if (!defined("ACCESS")) {
    die("Error: You don't have permission to access here...");
}

class ZP_Log
{
    protected $path;
    protected $fileName = "mylog.log";
    protected $logLevel = 0;

    public function __construct($path)
    {
        if (empty($path)) {
            throw new Exception("Path must be filled");
        }

        if (!file_exists($path)) {
            throw new Exception("The Path doesn't exists.");
        }

        if (!is_writeable($path)) {
            throw new Exception("You can write on the give path");
        }

        $this->path = $this->parsePath($path);
        $this->config("log");
        $this->setLevel();
    }   

    protected function parsePath($path)
    {
        $strLenght = strlen($path);
        $lastChar = substr($path, $strLenght - 1, $strLenght);
        $path = ($lastChar !== "/") ? $path . "/" : $path;
        return is_dir($path) ? $path . $this->fileName : $path;
    }

    protected function save($line)
    {
        $fhandle = fopen($this->path, "a+");
        fwrite($fhandle, $line);
        fclose($fhandle);
    }

    protected function setLevel()
    {
        $str = strtoupper(OLD_LOG_LEVEL);
        
        switch ($str) {
            case "FATAL":
                $this->logLevel = 5;
                break;
            case "ERROR":
                $this->logLevel = 4;
                break;
            case "WARN":
                $this->logLevel = 3;
                break;
            case "INFO":
                $this->logLevel = 2;
                break;
            case "DEBUG":
                $this->logLevel = 1;
                break;
            case "TRACE":
                $this->logLevel = 0;
                break;
            default:
                $this->logLevel = 0;
        }
    }

    public function fatal($event)
    {
    	if ($this->logLevel <= 5) {
    	   $this->addLine('FATAL: '. $event);
    	}
    }
    
    public function error($event)
    {
    	if ($this->logLevel <= 4) {
    	   $this->addLine('ERROR: '. $event);
    	}
    }
    
    public function warn($event)
    {
    	if ($this->logLevel <= 3) {
    	   $this->addLine('WARN: '. $event);
    	}
    }
    
    public function info($event) 
    {
    	if ($this->logLevel <= 2) {
    	   $this->addLine('INFO: '. $event);
    	}
    }

    public function debug($event) 
    {
    	if ($this->logLevel <= 1) {
    	   $this->addLine('DEBUG: '. $event);
    	}
    }

    public function trace($event) 
    {
    	if ($this->logLevel <= 0) {
    	   $this->addLine('TRACE: '. $event);
    	}
    }

    public function addLine($line) 
    {
        $line = is_array($line) ? print_r($line, true) : $line;
        $line = date("d-m-Y h:i:s") . ": $line\n";
        $this->save($line);
    }
}