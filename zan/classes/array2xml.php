<?php

class ZP_Array2XML {
    
    private $XML = NULL;
    
    private function build($array, $ID) {
        if(is_array($array)) {
            $keys = array_keys($array);
            
            for($i = 0; $i < sizeof($keys); $i++) {
                $tag = $keys[$i];
                
                if(is_numeric($tag)) {
                    $tag = $ID;
                }
                
                if($tag === "_id") {
                    $tag = "id";
                }
                
                if($tag === $ID) {
                    $this->XML .= "<". strtolower($tag) .">";
                } else {
                    $this->XML .= "<". strtolower($tag) .">";
                }
                
                $this->build($array[$keys[$i]], $ID);
                
                if($tag === $ID) {
                    $this->XML .= "</". strtolower($tag) .">";
                } else {
                    $this->XML .= "</". strtolower($tag) .">";
                }
            }
        } elseif(!empty($array)) { 
            if($this->checkForHTML($array)) {
                $array = '<![CDATA['. $array .']]>';
            }
            
            $this->XML .= $array;
        } else {
            return FALSE;
        }
    }
    
    private function checkForHTML($string) {
        if(strlen($string) !== strlen(strip_tags($string))) {
            return TRUE;
        }
        
        return FALSE;
    }

    public function printXML($array, $root = "data", $ID = "node") {
        $this->toXML($array, $root, $ID);
        
        header("Content-Type: text/xml"); 
        print $this->XML;
    }

    public function toXML($array, $root = "data", $ID = "node") {
        $this->XML .= '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $this->XML .= "<". strtolower($root) .">";
        $this->XML .= $this->build($array, $ID);
        $this->XML .= "</". strtolower($root) .">";
        
        return $this->XML;
    }
            
}
