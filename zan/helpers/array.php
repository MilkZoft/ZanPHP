<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

if (!function_exists("isMultiArray")) {
	function isMultiArray($multiArray)
	{
		if (is_array($multiArray)) {  
			foreach ($multiArray as $array) {  
				if (is_array($array)) {
					return true;  
				}  
			}  
		}  
		
		return false; 
	}
}

if (!function_exists("string2Array")) {
	function string2Array($string, $char = ",")
	{
		$string = str_replace(", ", ",", $string);
		$array = explode($char, $string);

		if (count($array) > 0) {
			return $array; 
		}

		return false;
	}
}

if(!function_exists("arrayPushAfter")) {
	function arrayPushAfter($src, $in, $pos)
	{
	    if (is_int($pos)) {
	    	$r = array_merge(array_slice($src, 0, $pos + 1), $in, array_slice($src,$pos + 1));
	    } else {
	        foreach ($src as $k => $v) {
	            $r[$k] = $v;
	            
	            if ($k == $pos) {
	            	$r = array_merge($r, $in);
	            }
	        }
	    }

	    return $r;
	}
}