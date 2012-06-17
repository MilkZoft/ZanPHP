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
	global $Load, $ZP;
	
	$applicationController = FALSE;

	$match   = FALSE;
	$special = FALSE;
	$params  = array();

	if(file_exists("www/config/routes.php")) {
		include "www/config/routes.php";
		
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
			$application = get("defaultApplication");	
		} elseif(segment(0) and !segment(1)) {
			$application = isLang() ? get("defaultApplication") : segment(0);
		} else { 
			$application 		   = segment(0, isLang());
			$applicationController = segment(1, isLang());

			if(isController($applicationController, $application)) { 
				$Controller     = getController($applicationController, $application);
				$controllerFile = getController($applicationController, $application, TRUE);
				$method 		= segment(2, isLang());

				if(!isMethod($method, $Controller)) {
					if(isMethod("index", $Controller)) {
						$method  = "index";
						$special = TRUE;
					} else {
						getException("Method \"$method\" doesn't exists");
					}
				}
			} else { 
				$applicationController = FALSE;
				$Controller     	   = getController(NULL, $application);
				$controllerFile 	   = getController(NULL, $application, TRUE);
				$method 			   = segment(1, isLang());

				if(!isMethod($method, $Controller)) {
					if(isMethod("index", $Controller)) {
						$method  = "index";
						$special = TRUE;
					} else {
						getException("Method \"$method\" doesn't exists");
					}
				}
			}
		
			if($applicationController) {
				if(segments() >= 3) {
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

	if(get("webSituation") !== "Active" and !SESSION("ZanUserID") and $application !== "cpanel") {
		die(get("webMessage"));
	}
	
	$Load->app($application);

	$controllerFile = ($applicationController) ? getController($applicationController, $application, TRUE) : getController(NULL, $application, TRUE);

	if(!$controllerFile) {
		getException("The application \"$application\" doesn't exists");
	}

	$Controller = isset($Controller) ? $Controller : getController(NULL, $application);
	
	if(isset($method) and count($params) > 0) {
		if(isMethod($method, $Controller)) {
			try {
				$Reflection = new ReflectionMethod($Controller, $method);
				
				if(!$Reflection->isPublic()) {
					throw new RuntimeException("The called method is not public.", 100);
				}
					
				call_user_func_array(array($Controller, $method), $params);
			} catch(RuntimeException $e) {
				getException($e);
			}
		} else { 
			if(isController($controllerFile, TRUE)) {
				if(isset($method) and count($params) > 0) {
					if(isMethod($method, $Controller)) {
						try {
							$Reflection = new ReflectionMethod($Controller, $method);
							
							if(!$Reflection->isPublic()) {
								throw new RuntimeException("The called method is not public.", 100);
							}
								
							call_user_func_array(array($Controller, $method), $params);
						} catch(RuntimeException $e) {
							getException($e);
						}
					}
				}
			} else {
				if(method_exists($Controller, "index")) {
					try {
						$reflection = new ReflectionMethod($Controller, "index");
						
						if(!$reflection->isPublic()) {
							throw new RuntimeException("The called method is not public.", 100);
						} elseif($Reflection->getNumberOfRequiredParameters() > 0 and count($params) === 0) {							
							throw new RuntimeException("The called method need required parameters (". getParameters($Reflection->getParameters()) .").", 200);
						}
							
						call_user_func_array(array($Controller, "index"), $params);
					} catch(RuntimeException $e) {
						getException($e);
					}
				} else {
					getException("Method index doesn't exists");
				}
			}
		}
	} elseif(isset($method)) { 
		if(isMethod($method, $Controller)) {
			try {
				$Reflection = new ReflectionMethod($Controller, $method);
					
				if(!$Reflection->isPublic()) {
					throw new RuntimeException("The called method is not public.", 100);
				} elseif($Reflection->getNumberOfRequiredParameters() > 0 and count($params) === 0) {						
					throw new RuntimeException("The called method need required parameters (". getParameters($Reflection->getParameters()) .").", 200);
				}
	
				$Controller->$method();
			} catch(RuntimeException $e) {
				getException($e);
			}
		} else {
			if(isMethod("index", $Controller)) {
				call_user_func_array(array($Controller, "index"), $params);
			} else {
				getException("Method \"index\" doesn't exists");
			}
		}
	} else { 
		if(isMethod("index", $Controller)) { 
			try {
				$Reflection = new ReflectionMethod($Controller, "index");
					
				if(!$Reflection->isPublic()) {
					throw new RuntimeException("The called method is not public.", 100);
				} elseif($Reflection->getNumberOfRequiredParameters() > 0 and count($params) === 0) {
					throw new RuntimeException("The called method need required parameters (". getParameters($Reflection->getParameters()) .").", 200);
				}
	
				call_user_func_array(array($Controller, "index"), $params);
			} catch(RuntimeException $e) {
				getException($e);
			}
		} else {
			getException("Method \"index\" doesn't exists");
		}
	}	
}

function getParameters($params) {
	$parameters = NULL;
			
	if(count($params) > 0) {
		$i = 0;
							
		foreach($params as $param) {
			if($i === count($params) - 1) {
				$parameters .= '$'. $param->name;
			} else {
				$parameters .= '$'. $param->name .", ";	
			}
				
			$i++;
		}
	}

	return $parameters;
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
	global $ZP;

	$URL = NULL;

	for($i = 0; $i <= segments() - 1; $i++) {
		if($i === (segments() - 1)) {
			$URL .= segment($i); 	
		} else {
			$URL .= segment($i) ."/";
		}
	}
	
	$URL = get("webBase") ."/$URL";
	
	return $URL;
}

function getController($applicationController = NULL, $application, $file = FALSE) {
	global $Load;

	if(isController($applicationController, $application)) {
		$controller 	= ucfirst($applicationController) ."_Controller";
		$controllerFile = "www/applications/". strtolower($application) ."/controllers/". strtolower($applicationController). ".php";
		
		$$controller = (!$file) ? $Load->controller($controller, $application) : FALSE;
	} else { 
		$controller 	= ucfirst($application) ."_Controller";
		$controllerFile = "www/applications/". strtolower($application) ."/controllers/". strtolower($application) .".php";
		
		$$controller = (!$file) ? $Load->controller($controller) : FALSE;
	}

	if($file) {
		return file_exists($controllerFile) ? $controllerFile : FALSE;
	}

	return $$controller;
}

function whichApplication() {
	if(file_exists("www/applications/" . segment(0) . "/controllers/" . segment(0) . ".php")) {
		return segment(0); 
	} elseif(file_exists("www/applications/". segment(1) ."/controllers/". segment(1) .".php")) {
		return segment(1);
	} elseif(file_exists("www/applications/". segment(0) ."/models/". segment(0) .".php")) {
		return segment(0);
	} elseif(file_exists("www/applications/". segment(1) ."/models/". segment(1) .".php")) {
		return segment(1);
	} elseif(file_exists("www/applications/". get("defaultApplication") ."/controllers/". get("defaultApplication") .".php")) {
		return get("defaultApplication");	
	}
	
	return FALSE;
}

function path($path = FALSE, $URL = FALSE, $lang = TRUE) {
	if(!$path) {
		return isLang() ? get("webBase") . _sh . get("webLang") : get("webBase");
	} 

	if($URL === "zan") {
		return getDomain(_corePath) . _sh . "zan" . _sh . $path;
	} elseif(isLang($path)) {
		return get("webBase") . _sh . $path;
	}

	if($lang) {
		return ($URL) ? get("webURL") . _sh . $path : get("webBase") . _sh . get("webLang") . _sh . $path;
	} else {
		return ($URL) ? get("webURL") . _sh . $path : get("webBase") . _sh . $path;
	}
}

function getDomain($path = FALSE) {
	if($path) {
		$URL   = str_replace("http://", "", get("webURL"));
		$parts = explode("/", $URL);
		
		if($path === "../../zan" and isset($parts[0]) and isset($parts[2])) {
			return "http://". $parts[0] . "/". $parts[1];
		} elseif($path === "../zan" and isset($parts[2])) {
			return "http://". $parts[0] . "/". $parts[1];
		}

		return ($path === "zan") ? get("webURL") : "http://". $parts[0];
	}

	return get("webURL");
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
	global $ZP;

	if(!$time) {		
		if(!$URL) {
			header("location: ". path());
		} elseif(substr($URL, 0, 7) !== "http://" and substr($URL, 0, 8) !== "https://") {
			header("location: ". path($URL));
			
			exit;
		} else {
			header("location: $URL");
			
			exit;
		}
	} elseif(!is_bool($time) and $time > 0) {
		$time = $time * 1000;
		
		echo '
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
 * Returns an Array from $_SERVER["REQUEST_URI") exploding each position with slashes
 * 
 * @return array		
 */
function route() {	
	$URL   = explode("/", substr($_SERVER["REQUEST_URI"], 1));
	$paths = explode("/", dirname($_SERVER["SCRIPT_FILENAME"]));
	$path  = $paths[count($paths) - 1];

	if(is_array($URL)) {		 
		$URL = array_diff($URL, array(""));
		
		if(!get("domain")) {
			$vars[] = array_shift($URL);
		}
		
		if(isset($URL[0]) and $URL[0] === $path) {
			$vars[] = array_shift($URL);
		}

		if(!get("modRewrite") and isset($URL[0])) { 
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