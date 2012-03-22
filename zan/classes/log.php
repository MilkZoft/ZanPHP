<?php
class ZP_Log {
    protected $path;
    protected $fileName = "mylog.log";
    protected $logLevel = 0;

    public function __construct($path) {
        if(empty($path)) {
            throw new Exception("Path must be filled");
        }

        if(!file_exists($path)) {
            throw new Exception("The Path doesn't exists.");
        }

        if(!is_writeable($path)) {
            throw new Exception("You can write on the give path");
        }

        $this->path = $this->parsePath($path);
        $this->config("log");
        $this->setLevel();
    }   

    protected function parsePath($path) {
        $strLenght = strlen($path);
        $lastChar  = substr($path, $strLenght - 1, $strLenght);
        $path      = ($lastChar !== "/") ? $path . "/" : $path;

        if(is_dir($path)) {
            return $path . $this->fileName;
        } else {
            return $path;
        }
    }

    protected function save($line) {
        $fhandle = fopen($this->path, "a+");
        
        fwrite($fhandle, $line);
        fclose($fhandle);
    }
    protected function setLevel() {
        $str = strtoupper(_oldLogLevel);
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
    defaul:
        $this->logLevel = 0;
     }
    }
 
    public function fatal($event){
    	#Very severe error events that will presumably lead the application to abort.
    	if($this->logLevel <= 5){
    	   $this->addLine('FATAL: ' . $event);
    	}
    }
    public function error($event){
    	#Error events that might still allow the application to continue running.
    	if($this->logLevel <= 4){
    	   $this->addLine('ERROR: ' . $event);
    	}
    }
    public function warn($event){
    	#Potentially harmful situations which still allow the application to continue running.
    	if($this->logLevel <= 3){
    	   $this->addLine('WARN: ' . $event);
    	}
    }
    public function info($event){
    	#Informational messages that highlight the progress of the application at coarse-grained level.
    	if($this->logLevel <= 2){
    	   $this->addLine('INFO: ' . $event);
    	}
    }
    public function debug($event){
    	#Fine-grained informational events that are most useful to debug an application.
    	if($this->logLevel <= 1){
    	   $this->addLine('DEBUG: ' . $event);
    	}
    }
    public function trace($event){
    	#Finest-grained informational events.
    	if($this->logLevel <= 0){
    	   $this->addLine('TRACE: ' . $event);
    	}
    }
    public function addLine($line) {
        $line = is_array($line) ? print_r($line, true) : $line;
        $line = date("d-m-Y h:i:s") . ": $line\n";

        $this->save($line);
    }
}