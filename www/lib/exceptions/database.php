<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

if (!function_exists("e")) {
	function e($exception = NULL)
	{
		if (is_null($exception)) {
			return __("An unknown error has occurred");
		}
		
		switch ($exception) {
			case "Connection Error": 
				return __("Connection error");	
				
			default: 
				return $exception; 
		}
	}
}	