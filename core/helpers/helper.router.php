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
	
	$applicationController = FALSE;
	
	if(!segment(0)) {
		$application = _defaultApplication;	
	} elseif(segment(0) and !segment(1)) {
		if(isLang()) {
			$application = _defaultApplication;
		} else {
			$application = segment(0);	
		}
	} else {	
		if(isLang()) {
			$application = segment(1);
			
			if(segment(2)) {
				if(isController(segment(2), segment(1))) {
					$applicationController = segment(2);
					$method = segment(3);
				} else {
					$method = segment(2);
				}
			}
			
			if($applicationController) {
				if(segments() > 4) {
					$j = 4;
					
					for($i = 0; $i < segments(); $i++) {
						if(segment($j)) {
							$p[$i] = segment($j);
						
							$j++;	
						}
					}
				}			
			} else {
				if(segments() > 3) {
					$j = 3;
					
					for($i = 0; $i < segments(); $i++) {
						if(segment($j)) {
							$p[$i] = segment($j);
						
							$j++;	
						}
					}
				}	
			}
		} else {
			$application = segment(0);
			
			if(segment(1)) {
				if(isController(segment(1), segment(0))) {
					$applicationController = segment(1);
					
					if(segment(2)) {
						$method = segment(2);
					}
				} else {
					$method = segment(1);
				}	
			}
			
			if($applicationController) {
				if(segments() > 3) {
					$j = 3;
					
					for($i = 0; $i <= segments() - 1; $i++) {
						if(segment($j)) {
							$p[$i] = segment($j);
							
							$j++;
						}	
					} 
				}			
			} else {
				if(segments() > 2) {
					$j = 2;
					
					for($i = 0; $i <= segments() - 1; $i++) {
						if(segment($j)) {
							$p[$i] = segment($j);
							
							$j++;
						}	
					} 
				}
			}

		}
	}
	
	if(_webState === "Inactive" and !SESSION("ZanUserID") and $control !== _cpanel) {
		die(_webMessage);
	}
	
	
	if(isController($applicationController, $application)) {
		$controller 	= ucfirst($applicationController) . "_Controller";
		$controllerFile = _applications . _sh . strtolower($application) . _sh . _controllers . _sh . _controller . _dot . strtolower($applicationController) . _PHP;
		
		$$controller = $Load->controller($controller);
	} else { 
		$controller 	= ucfirst($application) . "_Controller";
		$controllerFile = _applications . _sh . strtolower($application) . _sh . _controllers . _sh . _controller . _dot . strtolower($application) . _PHP;
	
		$$controller = $Load->controller($controller);
	}
	
	if(file_exists($controllerFile)) {
		if(isset($method) and isset($p)) {
			if(method_exists($$controller, $method)) {
				if(count($p) === 10) {
					$$controller->$method($p[0], $p[1], $p[2], $p[3], $p[4], $p[5], $p[6], $p[7], $p[8], $p[9]);	
				} elseif(count($p) === 9) {
					$$controller->$method($p[0], $p[1], $p[2], $p[3], $p[4], $p[5], $p[6], $p[7], $p[8]);
				} elseif(count($p) === 8) {
					$$controller->$method($p[0], $p[1], $p[2], $p[3], $p[4], $p[5], $p[6], $p[7]);
				} elseif(count($p) === 7) {
					$$controller->$method($p[0], $p[1], $p[2], $p[3], $p[4], $p[5], $p[6]);
				} elseif(count($p) === 6) {
					$$controller->$method($p[0], $p[1], $p[2], $p[3], $p[4], $p[5]);
				} elseif(count($p) === 5) {
					$$controller->$method($p[0], $p[1], $p[2], $p[3], $p[4]);
				} elseif(count($p) === 4) {
					$$controller->$method($p[0], $p[1], $p[2], $p[3]);
				} elseif(count($p) === 3) {
					$$controller->$method($p[0], $p[1], $p[2]);
				} elseif(count($p) === 2) {
					$$controller->$method($p[0], $p[1]);
				} elseif(count($p) === 1) {
					$$controller->$method($p[0]);
				} else {
					$$controller->$method();
				}
			} else {
				$$controller->index();
			}
		} elseif(isset($method)) {
			if(method_exists($$controller, $method)) {
				$$controller->$method();
			} else {
				redirect(_webBase);
			}
		} else {
			$$controller->index();	
		}
	}
}

function isController($controller, $application) {
	$file = _applications . _sh . $application . _sh . _controllers . _sh . _controller . _dot . $controller . _PHP;
	
	
	if(file_exists($file)) {
		return TRUE;	
	}
	
	return FALSE;
}

function isLang() {
	if(segment(0) === "en" or segment(0) === "es" or segment(0) === "fr" or segment(0) === "pt") {
		return TRUE;	
	}
	
	return FALSE;
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
