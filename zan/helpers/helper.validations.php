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
	if((strlen($email) >= 6) and (substr_count($email, "@") === 1) and (substr($email, 0, 1) !== "@") and (substr($email, strlen($email) - 1, 1) !== "@")) { 
		if((!strstr($email, "'")) and (!strstr($email, "\"")) and (!strstr($email, "\\")) and (!strstr($email, "\$")) and (!strstr($email, " "))) {
			if(substr_count($email, ".") >= 1) {
				$domain = substr(strrchr($email, "."), 1);
				
				if(strlen($domain) > 1 and strlen($domain) < 5 and (!strstr($domain, "@"))) {
					$prev = substr($email, 0, strlen($email) - strlen($domain) - 1);
					$last = substr($prev, strlen($prev) - 1, 1);
					
					if($last !== "@" and $last !== ".") {
						return TRUE;					
					}
				}
			}
		}
	}
	
	return FALSE;	
}

function isImage($image) {
    if(!getimagesize($image)) {
        return FALSE;
    } else {
        return TRUE;
    }
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

function isVulgar($content, $max = 1) {	
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

