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
	return ($uppercase === TRUE) ? strtoupper(substr(md5(date("Y-m-d H:i:s", time())), 0, $max)) : substr(md5(date("Y-m-d H:i:s", time())), 0, $max);
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
	if($key === FALSE) {
		$password = $password . substr(md5(date("Y-m-d H:i:s", time())), 0, 10);
	} else {
		$password = _ZanPHP . _secretKey . $password;
	}
		
	if($strong === 1) {
		$hash = md5(md5(md5($password)));
	} elseif($strong === 2) {
		$hash = sha1(sha1(sha1($password)));
	} elseif($strong === 3) {
		$hash = sha1(md5(sha1(md5(sha1(md5($password))))));		
	}
	
	return ($uppercase === TRUE) ? strtoupper($hash) : $hash;
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
		if(isset($_SERVER["HTTP_CLIENT_IP"])) {
			return $_SERVER["HTTP_CLIENT_IP"];
		} else {
			return $_SERVER["REMOTE_ADDR"];
		}
		
		return $_SERVER["HTTP_X_FORWARDED_FOR"];
	} else {
		if(isset($_SERVER["HTTP_CLIENT_IP"])) {
			return $_SERVER["HTTP_CLIENT_IP"];
		} else {
			return $_SERVER["REMOTE_ADDR"];			
		}
	}
}

/**
 * ping
 *
 * Pings a URL
 * 
 * @param string $domain
 * @return void
 */
function ping($domain) {
	$domain = str_replace("http://", "", $domain);
	
	if(@file_get_contents("http://" . $domain) === FALSE) {
		return FALSE; 
	} else {
		return TRUE;
	}
}

/**
 * redirect
 *
 * Redirects to a URL
 * 
 * @param string $domain
 * @param mixed  $time
 * @return void
 */
function redirect($URL, $time = FALSE) {
	if($time === FALSE) {
		header("location: $URL");
	} elseif(!is_bool($time) AND $time > 0) {
		$time = $time * 1000;
		
		print '
			<script type="text/javascript">
				function delayedRedirect() { 
					window.location.replace("' . $URL . '"); 
				}
				
				setTimeout("delayedRedirect()", ' . $time . ');
			</script>';
	}
}
