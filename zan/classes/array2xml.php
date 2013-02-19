<?php
if (!defined("ACCESS")) {
    die("Error: You don't have permission to access here...");
}

class ZP_Array2XML 
{
    private $XML = NULL;
    
    private function build($array, $ID)
    {
        if (is_array($array)) {
            $keys = array_keys($array);
            
            for($i = 0; $i < sizeof($keys); $i++) {
                $tag = $keys[$i];
                
                if (is_numeric($tag)) {
                    $tag = $ID;
                }
                
                if ($tag === "_id") {
                    $tag = "id";
                }
                
                $this->XML .= ($tag === $ID) ? "<". strtolower($tag) .">" : "<". strtolower($tag) .">";
                $this->build($array[$keys[$i]], $ID);
                $this->XML .= ($tag === $ID) ? "</". strtolower($tag) .">" : "</". strtolower($tag) .">";
            }
        } elseif (!empty($array)) { 
            if ($this->checkForHTML($array)) {
                $array = '<![CDATA['. $array .']]>';
            }
            
            $this->XML .= $array;
        } else {
            return false;
        }
    }
    
    private function checkForHTML($string)
    {
        if (strlen($string) !== strlen(strip_tags($string))) {
            return true;
        }
        
        return false;
    }

    public function printXML($array, $root = "data", $ID = "node")
    {
        $this->toXML($array, $root, $ID);
        header("Content-Type: text/xml");
        print $this->XML;
    }

    public function toXML($array, $root = "data", $ID = "node")
    {
        $this->XML .= '<?xml version="1.0" encoding="UTF-8"?>';
        $this->XML .= "<". strtolower($root) .">";
        $this->XML .= $this->build($array, $ID);
        $this->XML .= "</". strtolower($root) .">";
        return $this->XML;
    }
}