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
 * Security Helper
 *
 * 
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	helpers
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/helpers/security_helper
 */

/**
 * code
 *
 * Generates and returns a unique code based on a timestamp
 * 
 * @param int     $max = 10
 * @param boolean $uppercase
 * @return string value
 */
function code($max = 10, $uppercase = TRUE) {
	return ($uppercase) ? strtoupper(substr(md5(date("Y-m-d H:i:s", time())), 0, $max)) : substr(md5(date("Y-m-d H:i:s", time())), 0, $max);
}

/**
 * encrypt
 *
 * Generates and returns a unique code
 * 
 * @param string  $password = NULL
 * @param int     $strong
 * @param boolean $key
 * @param boolean $uppercase
 * @return string value
 */
function encrypt($password = NULL, $strong = 3, $key = TRUE, $uppercase = FALSE) {		
	$password = (!$key) ? $password . substr(md5(date("Y-m-d H:i:s", time())), 0, 10) : "ZanPHP" . _secretKey . $password;
	
	if($strong === 1) {
		$hash = md5(md5(md5($password)));
	} elseif($strong === 2) {
		$hash = sha1(sha1(sha1($password)));
	} else {
		$hash = sha1(md5(sha1(md5(sha1(md5($password))))));		
	}
	
	return ($uppercase) ? strtoupper($hash) : $hash;
}

/**
 * getIP
 *
 * Returns the User's IP
 * 
 * @return string value
 */
function getIP() {
	if(isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
		return isset($_SERVER["HTTP_CLIENT_IP"]) ? $_SERVER["HTTP_CLIENT_IP"] : $_SERVER["REMOTE_ADDR"];
	} else {
		return isset($_SERVER["HTTP_CLIENT_IP"]) ? $_SERVER["HTTP_CLIENT_IP"] : $_SERVER["REMOTE_ADDR"];
	}
}

function parseHTTP($string) {
    $parts = array("nonce" => TRUE, "nc" => TRUE, "cnonce" => TRUE, "qop" => TRUE, "username" => TRUE, "uri" => TRUE, "response" => TRUE);
    $data  = array();
    $keys  = implode("|", array_keys($parts));
	
    preg_match_all('@('. $keys .')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $string, $matches, PREG_SET_ORDER);
	
    foreach($matches as $match) {
        $data[$match[1]] = $match[3] ? $match[3] : $match[4];
        
        unset($parts[$match[1]]);
    }
	
    return (count($parts) === 0) ? $data : FALSE;
}