<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

function translation($text) {
	switch($text) {
		case "January": return "Enero"; break;
		case "February": return "Febrero"; break;
		case "March": return "Marzo"; break;
		case "April": return "Abril"; break;
		case "May": return "Mayo"; break;
		case "June": return "Junio"; break;
		case "July": return "Julio"; break;
		case "August": return "Agosto"; break;
		case "September": return "Septiembre"; break;
		case "October": return "Octubre"; break;
		case "November": return "Noviembre"; break;
		case "December": return "Diciembre"; break;
	}
	
	return $text;
}
