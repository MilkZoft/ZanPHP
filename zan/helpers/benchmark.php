<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

if (!function_exists("isMultiArray")) {
	function benchMarkEnd()
	{
		global $startTime;		
		echo '<p class="center small">'. __("Load time:") .' '. (microtime(true) - $startTime) .' '. __("seconds") .'</p>';
	}
}

if (!function_exists("isMultiArray")) {
	function benchMarkStart()
	{
		global $startTime;
		$startTime = microtime(true);
	}
}