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
	if((strlen($email) >= 6) and (substr_count($email, "@") == 1) and (substr($email, 0, 1) != "@") and (substr($email, strlen($email) - 1, 1) != "@")) { 
		if((!strstr($email, "'")) and (!strstr($email, "\"")) and (!strstr($email, "\\")) and (!strstr($email, "\$")) and (!strstr($email, " "))) {
			if(substr_count($email, ".") >= 1) {
				$domain = substr(strrchr ($email, "."), 1);
				
				if(strlen($domain) > 1 and strlen($domain) < 5 and (!strstr($domain, "@")) ) {
					$prev = substr($email, 0, strlen($email) - strlen($domain) - 1);
					$last = substr($prev, strlen($prev)-1, 1);
					
					if($last != "@" and $last != ".") {
							return TRUE;					
					}
				}
			}
		}
	}
	
	return FALSE;	
}

function isInjection($text, $count = 1) {
	$text = html_entity_decode($text);
	
	if(substr_count($text, "<script") >= $count) {
			return TRUE;
	} elseif(substr_count($text, "<iframe") >= $count) {
			return TRUE;
	} elseif(substr_count($text, "<img") >= $count) {
			return TRUE;
	}
	
	return FALSE;
}

function isSPAM($content, $count = 2) {
	$content = strtolower($content);
	
	if(substr_count($content, "http") >= $count) {
		return TRUE;
	} elseif(substr_count($content, "www") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".com") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".mx")  >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".org") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".net") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".co.uk") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".jp") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".ch") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".info") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".me") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".mobi") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".us") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".biz") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".ca") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".ws") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".ag") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".com.co") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".net.co") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".com.ag") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".net.ag") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".it") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".fr") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".tv") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".am") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".asia") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".at") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".be") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".cc") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".de") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".es") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".com.es") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".eu") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".fm") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".in") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".tk") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".com.mx") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".nl") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".nu") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".tw") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, ".vg") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "sex") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "porn") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "fuck") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "buy") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "free") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "dating") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "viagra") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "off") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "money") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "dollars") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "usd") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "payment") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "website") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "games") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "toys") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "poker") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "cheap") >= $count) { 
		return TRUE;
	}
	
	return FALSE;
}

function isVulgar($content, $count = 1) {
	$content = strtolower($content);
	
	if(substr_count($content, "puto") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "puta") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "perra") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "tonto") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "tonta") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "pene") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "pito") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "chinga") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "tu madre")  >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "hijo de puta") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "verga") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "pendejo") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "baboso") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "estupido")  >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "idiota") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "joto") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "gay") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "maricon") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "marica") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "chingar") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "jodete") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "pinche") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "panocha") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "vagina") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "zorra") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "fuck") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "chingada") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "cojer") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "coger") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "imbecil") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "pendeja") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "piruja") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "puerca") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "gorda") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "polla") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "capullo") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "gilipollas") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "cabron") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "cagada") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "cago") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "cagar") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "mierda") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "marrano") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "porno") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "concha") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "conche") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "tu madre") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "putas") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "putos") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "pendejas") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "pendejos") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "pendejadas") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "mamadas") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "lesbianas") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "coño") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "huevon") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "sudaca") >= $count) { 
		return TRUE;
	} elseif(substr_count($content, "fucker") >= $count) { 
		return TRUE;
	}
	
	return FALSE;
}
