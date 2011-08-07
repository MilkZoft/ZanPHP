<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

function translation($text) {
	switch($text) {
		case "Hello World!": return "Hola Mundo!"; break;
		case "Goodbye Cruel World!": return "Adiós Mundo Cruel!"; break;
	}
	
	return $text;
}
