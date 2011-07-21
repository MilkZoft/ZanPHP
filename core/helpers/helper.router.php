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
 * Router Helper
 *
 * 
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	helpers
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/helpers/router_helper
 */

/**
 * execute
 *
 * Executes the run() method which is inside all application controllers 
 * 
 * @return void
 * @link		
 */
function execute() {		
	global $Load;
	
	if(!segment(0)) {
		$control = _defaultApplication;	
	} elseif(segment(0) and !segment(1)) {
		$control = _defaultApplication;
	} else {
		$control = segment(1);	
	}
	
	if(segment(0) === _URL) {
		$control = _URL;
	} 
	
	if(_webState === "Inactive" and !SESSION("ZanUserID") and $control !== _cpanel) {
		die(_webMessage);
	}
	
	$controller 	= ucfirst($control) . "_Controller";
	$controllerFile = _applications . _sh . strtolower($control) . _sh . _controller . _dot . strtolower($control) . _PHP;
	
	$$controller = $Load->controller($controller);
	
	if(file_exists($controllerFile)) {
		$$controller->run();
	}
}

/**
 * segment
 *
 * Returns an specific segment of a URL from route()
 * 
 * @param int $segment
 * @return mixed		
 */
function segment($segment = 0) {
	$route = route();
	
	if(count($route) > 0) {		
		if(isset($route[$segment])) {
			return filter($route[$segment]);
		} else {
			return FALSE;
		}
	} else {
		return FALSE;
	}
}

/**
 * segments
 *
 * Returns the Amount of segments contained on route()
 * 
 * @param int $segment
 * @return mixed		
 */
function segments() {
	$route = route();
	
	return count($route);
}

/**
 * route
 *
 * Returns an Array from $_SERVER["REQUEST_URI"] exploding each position with slashes
 * 
 * @return array		
 */
function route() {
	$URL = explode("/", substr($_SERVER["REQUEST_URI"], 1));
	
	if(is_array($URL)) {		 
		$URL = array_diff($URL, array(""));
		
		if(_domain === FALSE) {
			$vars[] = array_shift($URL);
		}
		
		if(_modRewrite === FALSE and isset($URL[0])) { 
			if($URL[0] == basename($_SERVER["SCRIPT_FILENAME"])) {
				$vars[] = array_shift($URL);
			}
		}
	}
	
	return $URL;
}

/**
 * getURL
 *
 * Returns and URL built with _webBase and the Amount of segments contained on route()
 * 
 * @return array		
 */
function getURL() {		
	$URL = NULL;

	for($i = 0; $i <= segments() - 1; $i++) {
		$URL .= segment($i) . _sh; 	
	}
	
	$URL = _webBase . _sh . $URL;
	
	return $URL;
}
