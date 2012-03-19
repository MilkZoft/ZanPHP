<?php
/**
 * ZanPHP
 *
 * An open source agile and rapid development framework for PHP 5
 *
 * @package		ZanPHP
 * @author		MilkZoft Developer Team
 * @copyright	Copyright (c) 2011, MilkZoft, Inc.
 * @license		http://www.zanphp.com/documentation/en/license/
 * @link		http://www.zanphp.com
 * @version		1.0
 */
 
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

/**
 * Validations Helper
 *
 * 
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	helpers
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/helpers/validations_helper
 */
function isEmail($email) {
	return (filter_var($email, FILTER_VALIDATE_EMAIL)) ? TRUE : FALSE;
}

function isImage($image) {
    return (getimagesize($image)) ? TRUE : FALSE;
}

function isInjection($text, $count = 1) {
	if(is_string($text)) {
		$text = html_entity_decode($text);

		if(substr_count($text, "<script") >= $count) {
			return TRUE;
		} elseif(substr_count($text, "<iframe") >= $count) {
			return TRUE;
		} elseif(substr_count($text, "<img") >= $count) {
			return TRUE;
		}	
	}
	
	return FALSE;
}

function isIP($IP) {
	return filter_var($IP, FILTER_VALIDATE_IP) ? TRUE : FALSE;
}

function isSPAM($string, $max = 2) {
	$words = array(	
		"http", "www", ".com", ".mx", ".org", ".net", ".co.uk", ".jp", ".ch", ".info", ".me", ".mobi", ".us", ".biz", ".ca", ".ws", ".ag", 
		".com.co", ".net.co", ".com.ag", ".net.ag", ".it", ".fr", ".tv", ".am", ".asia", ".at", ".be", ".cc", ".de", ".es", ".com.es", ".eu", 
		".fm", ".in", ".tk", ".com.mx", ".nl", ".nu", ".tw", ".vg", "sex", "porn", "fuck", "buy", "free", "dating", "viagra", "money", "dollars", 
		"payment", "website", "games", "toys", "poker", "cheap"
	);
					
    $count = 0;
    
    $string = strtolower($string);
     
    if(is_array($words)) {
		foreach($words as $word) {
			$count += substr_count($string, $word);
		}
	}
	
	return ($count > $max) ? TRUE : FALSE;
}

function isVulgar($string, $max = 1) {	
	$words = array(	
		"puto", "puta", "perra", "tonto", "tonta", "pene", "pito", "chinga", "tu madre", "hijo de puta", "verga", "pendejo", "baboso",
		"estupido", "idiota", "joto", "gay", "maricon", "marica", "chingar", "jodete", "pinche", "panocha", "vagina", "zorra", "fuck",
		"chingada", "cojer", "imbecil", "pendeja", "piruja", "puerca", "polla", "capullo", "gilipollas", "cabron", "cagada", "cago", "cagar",
		"mierda", "marrano", "porno", "conche", "tu puta madre", "putas", "putos", "pendejas", "pendejos", "pendejadas", "mamadas", "lesbianas",
		"coño", "huevon", "sudaca", "fucker", "ramera"
	);
					
    $count = 0;
     
    $string = strtolower($string);
    
    if(is_array($words)) {
		foreach($words as $word) {
			$count += substr_count($string, $word);
		}
	}
	
	return ($count > $max) ? TRUE : FALSE;
}

