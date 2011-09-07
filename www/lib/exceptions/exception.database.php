<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

function e($exception = NULL) {
	if(is_null($exception)) {
		return __(_("An unknown error has occurred"));	
	}
	
	switch($exception) {
		case "Connection Error": return __(_("Connection error"));	
		
		default: return $exception; 
	}
}