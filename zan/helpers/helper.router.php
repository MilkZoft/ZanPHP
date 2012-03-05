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

	$match   = FALSE;
	$special = FALSE;

	if(file_exists("www/config/config.routes.php")) {
		include "www/config/config.routes.php";
		
		if(is_array($routes)) {
			$application = segment(0, isLang());

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
			$application = segment(0, isLang());
			$params      = array();

			if(segment(1, isLang())) { 
				if(isController(segment(1, isLang()), segment(0, isLang()))) { 
					$applicationController = segment(1, isLang());
					$Controller     	   = getController($applicationController, $application);
					$controllerFile        = getController($applicationController, $application, TRUE);

					if(segment(2, isLang()) and !isNumber(segment(2, isLang()))) {
						$method = segment(2, isLang());
					} else {
						$method = "index";	
					}
				} else {
					$Controller     = getController(NULL, $application);
					$controllerFile = getController(NULL, $application, TRUE);

					if(!isNumber(segment(1, isLang()))) { 
						if(method_exists($Controller, segment(1, isLang()))) {
							$method = segment(1, isLang());
						} else {
							$special = TRUE;
						}
					}
				}
			}
			
			if($applicationController) {
				if(segments() > 3) {
					$j = isLang() ? 4 : 3;
					$j = ($special) ? $j - 1 : $j; 

					for($i = 0; $i < segments(); $i++) {
						if(segment($j) or segment($j) === 0) {
							$params[$i] = segment($j);
							
							$j++;	
						}
					}
				}			
			} else {
				$count = ($special) ? 1 : 2;

				if(segments() > $count) {
					$j = isLang() ? 3 : 2;
					$j = ($special) ? $j - 1 : $j;

					for($i = 0; $i < segments(); $i++) {
						if(segment($j) or segment($j) === 0) {
							$params[$i] = segment($j);
							
							$j++;	
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

	$Controller     = isset($Controller) ? $Controller : getController(NULL, $application);
	$controllerFile = ($applicationController) ? getController($applicationController, $application, TRUE) : getController(NULL, $application, TRUE);

	if(file_exists($controllerFile)) {
		if(isset($method) and isset($params)) { 
			if(method_exists($Controller, $method)) {
				try {
					$reflection = new ReflectionMethod($Controller, $method);
				
					if(!$reflection->isPublic()) {
						throw new RuntimeException("The called method is not public.", 100);
					}
					
					call_user_func_array(array($Controller, $method), $params);
				} catch(RuntimeException $e) {
					getException($e);
				}
			} else {
				call_user_func_array(array($Controller, "index"), $params);
			}
		} elseif(isset($method)) {
			if(method_exists($Controller, $method)) {
				try {
					$Reflection = new ReflectionMethod($Controller, $method);
					
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
				$params = !isset($params) ? array() : $params;

				call_user_func_array(array($Controller, "index"), $params);
			}
		} else {
			$params = !isset($params) ? array() : $params;

			call_user_func_array(array($Controller, "index"), $params);
		}
	}
}

function currentPath($path = NULL) {
	$URL = getURL();
	
	if($path) {
		if($URL === path($path)) {
			return ' class="current"';
		}
	} else {
		if($URL === path()) {
			return ' class="current"';
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
		if($i === (segments() - 1)) {
			$URL .= segment($i); 	
		} else {
			$URL .= segment($i) . "/";
		}
	}
	
	$URL = _webBase . "/$URL";
	
	return $URL;
}

function isController($controller, $application) {
	$file = "www/applications/$application/controllers/controller.$controller.php";

	if(file_exists($file)) {
		return TRUE;	
	}
	
	return FALSE;
}

function getController($applicationController = NULL, $application, $file = FALSE) {
	global $Load;

	if(isController($applicationController, $application)) {
		$controller 	= ucfirst($applicationController) ."_Controller";
		$controllerFile = "www/applications/". strtolower($application) ."/controllers/controller.". strtolower($applicationController). ".php";
		
		$$controller = (!$file) ? $Load->controller($controller) : FALSE;
	} else { 
		$controller 	= ucfirst($application) ."_Controller";
		$controllerFile = "www/applications/". strtolower($application) ."/controllers/controller.". strtolower($application) .".php";
		
		$$controller = (!$file) ? $Load->controller($controller) : FALSE;
	}

	return (!$file) ? $$controller : $controllerFile;
}

function whichApplication() {
	if(file_exists("www/applications/" . segment(0) . "/controllers/controller." . segment(0) . ".php")) {
		return segment(0); 
	} elseif(file_exists("www/applications/". segment(1) ."/controllers/controller.". segment(1) .".php")) {
		return segment(1);
	} elseif(file_exists("www/applications/". segment(0) ."/models/model.". segment(0) .".php")) {
		return segment(0);
	} elseif(file_exists("www/applications/". segment(1) ."/models/model.". segment(1) .".php")) {
		return segment(1);
	} elseif(file_exists("www/applications/". _defaultApplication ."/controllers/controller.". _defaultApplication .".php")) {
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

function path($path = FALSE, $URL = FALSE) {
	if(!$path) {
		if(isLang()) {
			return _webBase . _sh . _webLang;
		} else {
			return _webBase . _sh;
		}	
	} 

	if($URL) {
		return _webURL  . _sh . $path;
	} else {
		return _webBase . _sh . _webLang . _sh . $path;
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

	if(!@file_get_contents("http://" . $domain)) {
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
function redirect($URL = FALSE, $time = FALSE) {
	if(!$time) {		
		if(!$URL) {
			header("location: ". _webBase);
		} elseif(substr($URL, 0, 7) !== "http://" and substr($URL, 0, 8) !== "https://") {
			header("location: ". path($URL));
			
			exit;
		} else {
			header("location: $URL");
			
			exit;
		}
	} elseif(!is_bool($time) and $time > 0) {
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
function segment($segment = 0, $isLang = FALSE) {
	$route   = route();
	$segment = ($isLang) ? $segment + 1 : $segment;

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