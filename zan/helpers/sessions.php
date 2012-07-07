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
 * Sessions Helper
 *
 * 
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	helpers
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/helpers/security_helper
 */

function COOKIE($cookie, $value = FALSE, $time = 300000, $redirect = FALSE, $URL = FALSE) {
	if($value) {
		setcookie($cookie, filter($value), time() + $time, "/");
	
		if($redirect) {
			redirect(isset($URL) ? $URL : get("webBase"));		
		}
	} else {
		if(isset($_COOKIE[$cookie])) {
			return filter($_COOKIE[$cookie]);
		} else {
			return FALSE;
		}
	}
}

/**
 * SESSION
 *
 * Returns a $_SESSION index variable value
 * 
 * @param string $session
 * @return mixed
 */ 
function SESSION($session, $value = FALSE) {
	if(!$value) {
		if(isset($_SESSION[$session])) {
			return $_SESSION[$session];
		} else {
			return FALSE;
		}
	} else {
		$_SESSION[$session] = $value;
	}
	
	return TRUE;
}

function isConnected($URL = FALSE) {
	if(!SESSION("ZanUser")) {
		redirect($URL);
	} 

	return TRUE;
}

/**
 * unsetCookie
 *
 * Removes a cookie
 * 
 * @param $cookie
 * @param $URL    = _webBase
 * @return void
 */ 
function unsetCookie($cookie, $URL = FALSE) {
	setcookie($cookie);	
	
	if($URL) {
		redirect($URL);
	} else {
		redirect();
	}
}

/**
 * unsetSessions
 *
 * Unsets all started sessions variables
 * 
 * @param $URL    = _webBase
 * @return void
 */ 
function unsetSessions($URL = FALSE) {
	session_unset(); 
	session_destroy();	
	
	if($URL) {
		redirect($URL);
	} else {
		redirect();
	}
}