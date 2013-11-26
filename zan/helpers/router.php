<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

if (!function_exists("execute")) {
	function execute()
	{		
		global $Load, $ZP;
		
		$applicationController = false;
		$match = false;
		$special = false;
		$params = array();

		if (file_exists("www/config/routes.php")) {
			include "www/config/routes.php";
			
			if (is_array($routes)) {
				$application = segment(0, isLang());

				foreach ($routes as $route) {
					$pattern = $route["pattern"]; 
					$match = preg_match($pattern, $application);
					
					if ($match) {
						$application = $route["application"];
						$applicationController = $route["controller"];
						$method = $route["method"];
						$params = $route["params"];
						break;
					}	
				}
			}
		}
		
		if (!$match) {
			if (!segment(0)) {
				$application = _get("defaultApplication");	
			} elseif (segment(0) and !segment(1)) {
				$application = isLang() ? _get("defaultApplication") : segment(0);
			} else { 
				$application = segment(0, isLang());
				$applicationController = segment(1, isLang());

				if (isController($applicationController, $application)) { 
					$Controller = getController($applicationController, $application);
					$controllerFile = getController($applicationController, $application, true);
					$method = segment(2, isLang());

					if (!isMethod($method, $Controller)) {
						if (isMethod("index", $Controller)) {
							$method = "index";
							$special = true;
						} else {
							getException("Method \"$method\" doesn't exists");
						}
					}
				} else { 
					$applicationController = false;
					$Controller = getController(null, $application);
					$controllerFile = getController(null, $application, true);
					$method = segment(1, isLang());

					if (!isMethod($method, $Controller)) {
						if (isMethod("index", $Controller)) {
							$method = "index";
							$special = true;
						} else {
							getException("Method \"$method\" doesn't exists");
						}
					}
				}
			
				if ($applicationController) {
					if (segments() >= 3) {
						$j = isLang() ? 4 : 3;
						$j = ($special) ? $j - 1 : $j; 

						for ($i = 0; $i < segments(); $i++) {
							if (segment($j) or segment($j) === 0) {
								$params[$i] = segment($j);								
								$j++;	
							}
						}
					}			
				} else {
					$count = ($special) ? 1 : 2;

					if (segments() > $count) {
						$j = isLang() ? 3 : 2;
						$j = ($special) ? $j - 1 : $j;

						for ($i = 0; $i < segments(); $i++) {
							if (segment($j) or segment($j) === 0) {
								$params[$i] = segment($j);								
								$j++;	
							}
						}
					}	
				}
			} 
		}

		if (_get("webSituation") !== "Active" and !SESSION("ZanUserID") and $application !== "cpanel") {
			die(_get("webMessage"));
		}
		
		$Load->app($application);

		$controllerFile = ($applicationController) ? getController($applicationController, $application, true) : getController(null, $application, true);

		if (!$controllerFile) {
			getException("The application \"$application\" doesn't exists");
		}

		$Controller = isset($Controller) ? $Controller : getController(null, $application);
		
		if (isset($method) and count($params) > 0) {
			if (isMethod($method, $Controller)) {
				try {
					$Reflection = new ReflectionMethod($Controller, $method);
					
					if (!$Reflection->isPublic()) {
						throw new RuntimeException("The called method is not public.", 100);
					}
						
					call_user_func_array(array($Controller, $method), $params);
				} catch(RuntimeException $e) {
					getException($e);
				}
			} else { 
				if (isController($controllerFile, true)) {
					if (isset($method) and count($params) > 0) {
						if (isMethod($method, $Controller)) {
							try {
								$Reflection = new ReflectionMethod($Controller, $method);
								
								if (!$Reflection->isPublic()) {
									throw new RuntimeException("The called method is not public.", 100);
								}
									
								call_user_func_array(array($Controller, $method), $params);
							} catch(RuntimeException $e) {
								getException($e);
							}
						}
					}
				} else {
					if (method_exists($Controller, "index")) { 
						try {
							$reflection = new ReflectionMethod($Controller, "index");
							
							if (!$reflection->isPublic()) {
								throw new RuntimeException("The called method is not public.", 100);
							} elseif ($Reflection->getNumberOfRequiredParameters() > 0 and count($params) === 0) {							
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
		} elseif (isset($method)) { 
			if (isMethod($method, $Controller)) {
				try {
					$Reflection = new ReflectionMethod($Controller, $method);
						
					if (!$Reflection->isPublic()) {
						throw new RuntimeException("The called method is not public.", 100);
					} elseif ($Reflection->getNumberOfRequiredParameters() > 0 and count($params) === 0) {						
						throw new RuntimeException("The called method need required parameters (". getParameters($Reflection->getParameters()) .").", 200);
					}
		
					$Controller->$method();
				} catch(RuntimeException $e) {
					getException($e);
				}
			} else {
				if (isMethod("index", $Controller)) {
					call_user_func_array(array($Controller, "index"), $params);
				} else {
					getException("Method \"index\" doesn't exists");
				}
			}
		} else { 
			if (isMethod("index", $Controller)) { 
				try {
					$Reflection = new ReflectionMethod($Controller, "index");
						
					if (!$Reflection->isPublic()) {
						throw new RuntimeException("The called method is not public.", 100);
					} elseif ($Reflection->getNumberOfRequiredParameters() > 0 and count($params) === 0) {
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
}

if (!function_exists("getParameters")) {
	function getParameters($params)
	{
		$parameters = null;
				
		if (count($params) > 0) {
			$i = 0;
								
			foreach ($params as $param) {				
				$parameters .= ($i === count($params) - 1) ? '$'. $param->name : '$'. $param->name .", ";	
				$i++;
			}
		}

		return $parameters;
	}
}

if (!function_exists("currentPath")) {
	function currentPath($path = null)
	{
		if ($path) {
			if (getURL() === path($path)) {
				return ' class="current"';
			}
		} else {
			if (getURL() === path()) {
				return ' class="current"';
			}
		}
	}
}

if (!function_exists("getURL")) {
	function getURL()
	{
		$URL = null;

		for ($i = 0; $i <= segments() - 1; $i++) {		
			$URL .= ($i === (segments() - 1)) ? segment($i) : segment($i) ."/";
		}

		$a = path($URL, false, false);
		
		return $a;
	}
}

if (!function_exists("setURL")) {
	function setURL($URL = false)
	{		
		return ($URL) ? SESSION("lastURL", $URL) : SESSION("lastURL", getURL());	
	}
}

if (!function_exists("getController")) {
	function getController($applicationController = null, $application, $file = false)
	{
		global $Load;

		if (isController($applicationController, $application)) {
			$controller = ucfirst($applicationController) ."_Controller";
			$controllerFile = "www/applications/". strtolower($application) ."/controllers/". strtolower($applicationController). ".php";			
			$$controller = (!$file) ? $Load->controller($controller, $application) : false;
		} else { 
			$controller = ucfirst($application) ."_Controller";
			$controllerFile = "www/applications/". strtolower($application) ."/controllers/". strtolower($application) .".php";			
			$$controller = (!$file) ? $Load->controller($controller) : false;
		}

		if ($file) {
			return file_exists($controllerFile) ? $controllerFile : false;
		}

		return $$controller;
	}
}

if (!function_exists("whichApplication")) {
	function whichApplication()
	{
		if (file_exists("www/applications/". segment(0) ."/controllers/". segment(0) .".php")) {
			return segment(0); 
		} elseif (file_exists("www/applications/". segment(1) ."/controllers/". segment(1) .".php")) {
			return segment(1);
		} elseif (file_exists("www/applications/". segment(0) ."/models/". segment(0) .".php")) {
			return segment(0);
		} elseif (file_exists("www/applications/". segment(1) ."/models/". segment(1) .".php")) {
			return segment(1);
		} elseif (file_exists("www/applications/". _get("defaultApplication") ."/controllers/". _get("defaultApplication") .".php")) {
			return _get("defaultApplication");	
		}
		
		return false;
	}
}

if (!function_exists("path")) {
	function path($path = false, $URL = false, $lang = true, $anchor = false)
	{
		$anchor = ($anchor and defined("_anchor")) ? SH . ANCHOR : null;

		if ($path === false) {
			return isLang() ? _get("webBase") ."/". _get("webLang") : _get("webBase");
		} elseif ($path === true) {
			return $lang ? _get("webBase") ."/". _get("webLang") : _get("webBase");
		}

		if ($URL === "zan") {
			return getDomain(CORE_PATH) ."/zan/". $path;
		} elseif (isLang($path)) {
			return _get("webBase") ."/". $path . $anchor;
		}

		if ($lang) {
			if ($lang !== true) {
				$lang = getLang($lang);

				return ($URL) ? _get("webURL") ."/". $path : _get("webBase") ."/". $lang ."/". $path . $anchor;
			}

			return ($URL) ? _get("webURL") ."/". $path : _get("webBase") ."/". _get("webLang") ."/". $path . $anchor;
		} else {
			return ($URL) ? _get("webURL") ."/". $path : _get("webBase") ."/". $path . $anchor;
		}
	}
}

if (!function_exists("getDomain")) {
	function getDomain($path = false)
	{
		if ($path) {
			$URL = str_replace("http://", "", _get("webURL"));
			$parts = explode("/", $URL);
			
			if ($path === "../../zan" and isset($parts[0]) and isset($parts[2])) {
				return "http://". $parts[0] . "/". $parts[1];
			} elseif ($path === "../zan" and isset($parts[2])) {
				return "http://". $parts[0] . "/". $parts[1];
			}

			return ($path === "zan") ? _get("webURL") : "http://". $parts[0];
		}

		return _get("webURL");
	}
}

if (!function_exists("ping")) {
	function ping($domain)
	{		
		return (!@file_get_contents("http://" . str_replace("http://", "", $domain))) ? false : true; 
	}
}

if (!function_exists("redirect")) {
	function redirect($URL = false, $time = false)
	{
		global $ZP;

		if (!$time) {		
			if (!$URL) {
				header("location: ". path());
			} elseif (substr($URL, 0, 7) !== "http://" and substr($URL, 0, 8) !== "https://") {				
				header("location: ". path($URL));				
				exit;
			} else {
				header("location: $URL");				
				exit;
			}
		} elseif (!is_bool($time) and $time > 0) {
			$time = $time * 1000;			
			echo '<script type="text/javascript">function delayedRedirect() { window.location.replace("'. $URL .'"); } setTimeout("delayedRedirect()", '. $time .'); </script>';
		}
	}
}

if (!function_exists("returnTo")) {
	function returnTo($path, $var = "return_to")
	{
		return "?$var=". encode($path, true);
	}
}

if (!function_exists("route")) {
	function route()
	{	
		$URL = explode("/", substr($_SERVER["REQUEST_URI"], 1));
		$paths = explode("/", dirname($_SERVER["SCRIPT_FILENAME"]));
		$path = $paths[count($paths) - 1];

		if (is_array($URL)) {		 
			$URL = array_diff($URL, array(""));
			
			if (!_get("domain")) {
				$vars[] = array_shift($URL);
			}
			
			if (isset($URL[0]) and $URL[0] === $path) {
				$vars[] = array_shift($URL);
			}

			if (!_get("modRewrite") and isset($URL[0])) { 
				if ($URL[0] === basename($_SERVER["SCRIPT_FILENAME"])) { 
					$vars[] = array_shift($URL);
				}
			}
		}
		
		return $URL;
	}
}

if (!function_exists("routePath")) {
	function routePath()
	{
		$flag = false;		
		$rsaquo = " &rsaquo;&rsaquo; ";
		$path = path(whichApplication()) ."/";
		
		if (segments() > 0) {
			for ($i = 0; $i <= segments() - 1; $i++) {
				if (!$flag) {
					if (segments() === 6) {
						$flag = true;						
						$HTML = a(__("Home"), PATH("cpanel")) . $rsaquo;
						$HTML .= a(__(ucfirst(segment(2))), $path . segment(2)) . $rsaquo;
						$HTML .= a(__(ucfirst(segment(3))), $path . segment(2) . SH . segment(3)) . $rsaquo;
						$HTML .= a(__(ucfirst(segment(4))), $path . segment(2) . SH . segment(3) . SH . segment(4)) . $rsaquo;
						$HTML .= a(__(ucfirst(segment(5))), $path . segment(2) . SH . segment(3) . SH . segment(4) . SH . segment(5));	
					} elseif (segments() === 5) {
						$flag = true;						
						$HTML = a(__("Home"), path("cpanel")) . $rsaquo;
						$HTML .= a(__(ucfirst(segment(2))), $path . segment(2)) . $rsaquo;
						$HTML .= a(__(ucfirst(segment(3))), $path . segment(2) . SH . segment(3)) . $rsaquo;
						$HTML .= a(__(ucfirst(segment(4))), $path . segment(2) . SH . segment(3) . SH . segment(4));
					} elseif (segments() === 4) {
						$flag = true;										
						$HTML = a(__("Home"), path("cpanel")) . $rsaquo;
						$HTML .= a(__(ucfirst(segment(1))), $path . "cpanel") . $rsaquo;
						$HTML .= a(__(ucfirst(segment(3))), $path . segment(2) . SH . segment(3));
					} elseif (segments() === 3) {
						$flag = true;												
						$HTML = a(__("Home"), path("cpanel")) . $rsaquo;
						$HTML .= a(__(ucfirst(segment(1))), $path . segment(3));
					} elseif (segments() === 2) {
						$flag = true;						
						$HTML = a(__("Home"), path("cpanel"));
					} else {
						$HTML = a(__("Home"), path("cpanel"));
					}
				}
			}
		}
		
		return $HTML;
	}
}

if (!function_exists("segment")) {
	function segment($segment = 0, $isLang = false)
	{
		$route = route();
		$segment = ($isLang) ? $segment + 1 : $segment;

		if (count($route) > 0) {		
			if (isset($route[$segment]) and strlen($route[$segment]) > 0) {
				if ($route[$segment] === "0") {
					return (int) 0;
				}
					
				return filter($route[$segment], "remove");
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}

if (!function_exists("segments")) {
	function segments()
	{	
		return count(route());
	}
}