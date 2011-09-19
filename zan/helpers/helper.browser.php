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
 * Browser Helper
 *
 * The Helper Browser allows to detect the User's Browser
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	helpers
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/helpers/browser_helper
 */

/**
 * Get the User Browser String provided by Browser's User Agent
 *
 * @return string
 */
function browser() {
	$browsers = array(
		  "Opera" 				=> "(Opera)",
		  "Mozilla Firefox"		=> "((Firebird)|(Firefox))",
		  "Galeon" 				=> "(Galeon)",
		  "Mozilla"				=> "(Gecko)",
		  "MyIE"				=> "(MyIE)",
		  "Lynx" 				=> "(Lynx)",
		  "Netscape" 			=> "((Mozilla/4\.75)|(Netscape6)|(Mozilla/4\.08)|(Mozilla/4\.5)|(Mozilla/4\.6)|(Mozilla/4\.79))",
		  "Konqueror"			=> "(Konqueror)",
		  "Internet Explorer 9" => "((MSIE 9\.[0-9]+))",		  
		  "Internet Explorer 8" => "((MSIE 8\.[0-9]+))",
		  "Internet Explorer 7" => "((MSIE 7\.[0-9]+))",
		  "Internet Explorer 6" => "((MSIE 6\.[0-9]+))",
		  "Internet Explorer 5" => "((MSIE 5\.[0-9]+))",
		  "Internet Explorer 4" => "((MSIE 4\.[0-9]+))",
		  "Chrome"              => "((Chrome))"
	);

	foreach($browsers as $browser => $pattern) {
		if(preg_match($pattern, $_SERVER["HTTP_USER_AGENT"])) {
			if($browser == "Mozilla") {
				if(strstr($_SERVER["HTTP_USER_AGENT"], "Chrome") !== FALSE) {
					return "Google Chrome";
				}
				
				if(strstr($_SERVER["HTTP_USER_AGENT"], "Safari") !== FALSE) {
					return "Safari";
				}
			} else {
				$browser = str_replace(" 9", "", $browser);
				$browser = str_replace(" 8", "", $browser);
				$browser = str_replace(" 7", "", $browser);
				$browser = str_replace(" 6", "", $browser);
				
				return $browser;	
			}
		}
	}
	
	return "Unknown";
}