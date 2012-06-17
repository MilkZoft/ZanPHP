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

function is($var = NULL, $value = NULL) {
	return (isset($var) and $var === $value) ? TRUE : FALSE;
}

function isName($name) {
	if(strlen($name) < 7) {
		return FALSE;
	}

	$parts = explode(" ", $name);
	$count = count($parts);

	if($count > 1) {
		for($i = 0; $i <= $count; $i++) {
			if(isset($parts[$i]) and strlen($parts[$i]) > 25) {
				return FALSE;
			}
		}
	} else {
		return FALSE;
	} 

	return TRUE;
}

function isEmail($email) {
	return (filter_var($email, FILTER_VALIDATE_EMAIL)) ? TRUE : FALSE;
}

function isImage($image) {
    return (getimagesize($image)) ? TRUE : FALSE;
}

function isInjection($text, $count = 1) {
	if(is_string($text)) {
		$text = html_entity_decode($text);
		
		if(substr_count($text, "scriptalert") >= $count) {
			return TRUE;
		} elseif(substr_count($text, ";/alert") >= $count) {
			return TRUE;
		} elseif(substr_count($text, "<script") >= $count) {
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

function isSPAM($string, $max = 1) {
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
	
	return ($count >= $max) ? TRUE : FALSE;
}

function isVulgar($string, $max = 1) {	
	$words = array(	
		"puto", "puta", "perra", "tonto", "tonta", "pene", "pito", "chinga", "tu madre", "hijo de puta", "verga", "pendejo", "baboso",
		"estupido", "idiota", "joto", "gay", "maricon", "marica", "chingar", "jodete", "pinche", "panocha", "vagina", "zorra", "fuck",
		"chingada", "cojer", "imbecil", "pendeja", "piruja", "puerca", "polla", "capullo", "gilipollas", "cabron", "cagada", "cago", "cagar",
		"mierda", "marrano", "porno", "conche", "tu puta madre", "putas", "putos", "pendejas", "pendejos", "pendejadas", "mamadas", "lesbianas",
		"coño", "huevon", "sudaca", "fucker", "ramera", "fuck", "bitch"
	);
					
    $count = 0;
     
    $string = strtolower($string);
    
    if(is_array($words)) {
		foreach($words as $word) {
			$count += substr_count($string, $word);
		}
	}

	return ($count >= $max) ? TRUE : FALSE;
}

function isNumber($number) {
	$number = (int) $number;
	
	if($number > 0) {
		return TRUE;	
	}
	
	return FALSE;
}

function isMethod($method, $Controller) {
	try {
	    $Reflection = new ReflectionMethod($Controller, $method);
	    
	    return TRUE;
	} catch (Exception $e) {
	    return FALSE;
	}
}

function isController($controller, $application = NULL, $principal = FALSE) {
	if($application === TRUE) {
		if(file_exists($controller)) {
			return TRUE;
		}
	} else { 
		if($principal) {
			if($controller === $application) {
				$file = "www/applications/$application/controllers/$controller.php";

				if(file_exists($file)) {
					return TRUE;	
				}				
			} else {
				return FALSE;
			}
		}

		$file = "www/applications/$application/controllers/$controller.php";

		if(file_exists($file)) {
			return TRUE;	
		}
	}

	return FALSE;
}

function isLeapYear($year) {
	return ((((int) $year % 4 === 0) and ((int) $year % 100 !== 0 ) or ((int) $year % 400 === 0)));
}

function isDay($day) {	
	return (strlen($day) === 2 and $day > 0 and $day <= 31) ? TRUE : FALSE;	
}

function isMonth($month) {
	return (strlen($month) === 2 and $month > 0 and $month <= 12) ? TRUE : FALSE;
}

function isYear($year) {
	return (strlen($year) === 4 and $year >= 1950 and $year <= date("Y")) ? TRUE : FALSE;
}