<?php
class ZP_Log {
    protected $path;
    protected $fileName = "mylog.log";

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

    public function addLine($line) {
        $line = is_array($line) ? print_r($line, true) : $line;
        $line = date("d-m-Y h:i:s") . ": $line\n";

        $this->save($line);
    }
}