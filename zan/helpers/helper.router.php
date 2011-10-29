<?php
/* ex: set tabstop=2 noexpandtab: */
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

	$match = FALSE;

	if(file_exists("www/config/config.routes.php")) {
		include "www/config/config.routes.php";
		
		if(is_array($routes)) {
			if(isLang()) {
				$application = segment(1);
			} else {
				$application = segment(0);
			}

			foreach($routes as $route) {
				$pattern = $route["pattern"]; 
				$match   = preg_match($pattern, $application);
				
				if($match) {
					$application 		   = $route["application"];
					$applicationController = $route["controller"];
					$method                = $route["method"];
					$params      		   = $route["params"];

					break;
				}	
			}
			
		}
	}
	
	if(!$match) {
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
						
						if(segment(3) and !isNumber(segment(3))) {
							$method = segment(3);
						} else {
							$method = "index";	
						}
					} else { 
						if(!isNumber(segment(2))) { 
							$method = segment(2);
						}
					}
				}
				
				if($applicationController) {
					if(segments() > 4) {
						$j = 4;
					
						for($i = 0; $i < segments(); $i++) {
							if(segment($j) or segment($j) === 0) {
								$params[$i] = segment($j);
							
								$j++;	
							}
						}
					}			
				} else {
					if(segments() > 3) {
						$j = 3;
						
						for($i = 0; $i < segments(); $i++) {
							if(segment($j) or segment($j) === 0) {
								$params[$i] = segment($j);
							
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
						
						if(segment(2) and !isNumber(segment(2))) {
							$method = segment(2); 
						} else {
							$method = "index";	
						}
					} else {
						if(!isNumber(segment(1))) { 
							$method = segment(1);
						} 
					}	
				}
				
				if($applicationController) {
					if(segments() > 3) {
						$j = 3;
						
						for($i = 0; $i <= segments() - 1; $i++) {
							if(segment($j) or segment($j) === 0) {
								$params[$i] = segment($j);
								
								$j++;
							}	
						} 
					}			
				} else {
					if(segments() > 2) {
						$j = 2;
						
						for($i = 0; $i <= segments() - 1; $i++) {
							if(segment($j) or segment($j) === 0) {
								$params[$i] = segment($j);
								
								$j++;
							}	
						} 
					}
				}

			}
		}
	}

	if(_webSituation !== "Active" and !SESSION("ZanUserID") and $application !== "cpanel") {
		die(_webMessage);
	}
	
	$Load->app($application);

	if(isController($applicationController, $application)) {
		$controller 	= ucfirst($applicationController) . "_Controller";
		$controllerFile = _www . _sh . _applications . _sh . strtolower($application) . _sh . _controllers . _sh . _controller . _dot . strtolower($applicationController) . _PHP;
		
		$$controller = $Load->controller($controller);
	} else { 
		$controller 	= ucfirst($application) . "_Controller";
		$controllerFile = _www . _sh . _applications . _sh . strtolower($application) . _sh . _controllers . _sh . _controller . _dot . strtolower($application) . _PHP;
		
		$$controller = $Load->controller($controller);
	}

	if(file_exists($controllerFile)) {
		if(isset($method) and isset($params)) { 
			if(method_exists($$controller, $method)) {
				try {
					$reflection = new ReflectionMethod($$controller, $method);
				
					if(!$reflection->isPublic()) {
						throw new RuntimeException("The called method is not public.", 100);
					}
					
					call_user_func_array(array($$controller, $method), $params);
				} catch(RuntimeException $e) {
					getException($e);
				}
			} else {
				$$controller->index();
			}
		} elseif(isset($method)) {
			if(method_exists($$controller, $method)) {
				try {
					$Reflection = new ReflectionMethod($$controller, $method);
					
					if(!$Reflection->isPublic()) {
						throw new RuntimeException("The called method is not public.", 100);
					} elseif($Reflection->getNumberOfRequiredParameters() > 0 and !isset($params)) {
						$params 	= $Reflection->getParameters();
						$parameters = NULL;
						
						if(count($params) > 0) {
							$i = 0;
							
							foreach($params as $param) {
								if($i === count($params) - 1) {
									$parameters .= $param->name;
								} else {
									$parameters .= $param->name .", ";	
								}
								
								$i++;
							}
						}
						
						throw new RuntimeException("The called method need required parameters ($parameters).", 200);
					}
	
					$$controller->$method();
				} catch(RuntimeException $e) {
					getException($e);
				}
			} else {
				$$controller->index();	
			}
		} else {
			$$controller->index();	
		}
	}
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

function isController($controller, $application) {
	$file = _www . _sh . _applications . _sh . $application . _sh . _controllers . _sh . _controller . _dot . $controller . _PHP;

	if(file_exists($file)) {
		return TRUE;	
	}
	
	return FALSE;
}

function whichApplication() {
	if(file_exists(_www . _sh . _applications . _sh . segment(0) . _sh . _controllers . _sh . _controller . _dot . segment(0) . _PHP)) {
		return segment(0); 
	} elseif(file_exists(_www . _sh . _applications . _sh . segment(1) . _sh . _controllers . _sh . _controller . _dot . segment(1) . _PHP)) {
		return segment(1);
	} elseif(file_exists(_www . _sh . _applications . _sh . segment(0) . _sh . _models . _sh . _model . _dot . segment(0) . _PHP)) {
		return segment(0);
	} elseif(file_exists(_www . _sh . _applications . _sh . segment(1) . _sh . _models . _sh . _model . _dot . segment(1) . _PHP)) {
		return segment(1);
	} elseif(file_exists(_www . _sh . _applications . _sh . _defaultApplication . _sh . _controllers . _sh . _controller . _dot . _defaultApplication . _PHP)) {
		return _defaultApplication;	
	}
	
	return FALSE;
}

function isNumber($number) {
	$number = (int) $number;
	
	if($number > 0) {
		return TRUE;	
	}
	
	return FALSE;
}

/**
 * route
 *
 * Returns an Array from $_SERVER["REQUEST_URI"] exploding each position with slashes
 * 
 * @return array		
 */
function route() {
	$URL   = explode("/", substr($_SERVER["REQUEST_URI"], 1));
	$paths = explode("/", dirname($_SERVER["SCRIPT_FILENAME"]));
	$path  = $paths[count($paths) - 1];

	if(is_array($URL)) {		 
		$URL = array_diff($URL, array(""));
		
		if(!_domain) {
			$vars[] = array_shift($URL);
		}
		
		if(isset($URL[0]) and $URL[0] === $path) {
			$vars[] = array_shift($URL);
		}

		if(!_modRewrite and isset($URL[0])) { 
			if($URL[0] === basename($_SERVER["SCRIPT_FILENAME"])) { 
				$vars[] = array_shift($URL);
			}
		}
	}
	
	return $URL;
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
		if(isset($route[$segment]) and strlen($route[$segment]) > 0) {
			if($route[$segment] === "0") {
				return (int) 0;
			}
			
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
