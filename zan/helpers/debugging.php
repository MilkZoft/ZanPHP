<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

if (!function_exists("____")) {
	function ____($var, $dump = true, $exit = true)
	{
		echo '<pre style="font-size: 1.3em; color: #FF0000; line-height: 18px;">';
			
		if (!$dump) {
			print_r($var);
		} else {
			var_dump($var);
		}
		
		echo '</pre>';	
		
		if ($exit) {
			exit();
		}
	}
}