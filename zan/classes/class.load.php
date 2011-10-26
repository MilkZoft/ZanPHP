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
 * Access from index.php
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

/**
 * Includes Singleton 
 */
include "class.singleton.php";

/**
 * ZanPHP Load Class
 *
 * This class is used to load models, views, controllers, classes, libraries, helpers as well as interact directly with templates
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	classes
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/classes/load_class
 */
class ZP_Load {

	/**
	 * 
	 * 
	 * 
	 */
	public $application = FALSE;
	
	/**
	 * Contains an Instance (object) of the Templates Class
	 * 
	 * @object public $Templates
	 */
	public $Templates;
	
	/**
	 * Contains the array of views
	 * 
	 * @var private $views = array()
	 */
	private $views = array();
	
    /**
     * Loads helper autoload, database config and class templates
     *
     * @return void
     */
	public function __construct() {
		$helpers = array("autoload", "router", "validations");
		
		$this->helper($helpers);
		
		$this->config("cache");
		$this->config("exceptions");
		$this->config("languages");
	}
	
    /**
     * 
     *
     * @param string 
     * @return 
     */
	public function app($application) {
		$this->application = $application;
		
		return $application;	
	}
	
	/**
     * Loads an application class
     * 
     * @param string $class = NULL
     * @param string $application = NULL
     * @return object value
     */
	public function classes($class = NULL, $application = NULL) {
		if(file_exists(_www . _sh . _applications . _sh . $this->application . _sh . _classes . _sh . _class . _dot . strtolower($class) . _PHP)) {
			$file = _www . _sh . _applications . _sh . $this->application . _sh . _classes . _sh . _class . _dot . strtolower($class) . _PHP;	
		} elseif(file_exists(_www . _sh . _classes . _sh . _class . _dot . strtolower($class) . _PHP)) {
			$file = _www . _sh . _classes . _sh . _class . _dot . strtolower($class) . _PHP;
		} elseif(file_exists(_www . _sh . _applications . _sh . $application . _sh . _classes . _sh . _class . _dot . strtolower($class) . _PHP)) {
			$file = _www . _sh . _applications . _sh . $application . _sh . _classes . _sh . _class . _dot . strtolower($class) . _PHP;
		} else {
			$file = FALSE;	
		}
	
		$this->Cache = $this->core("Cache");
		
		if(file_exists($file)) {							
			if(class_exists($class)) {
				if($this->Cache->get($class, "classes")) {
					return $this->Cache->get($class, "classes");	
				} else {
					return ZP_Singleton::instance($class);
				}
			}
			
			if($this->Cache->get($class, "classes")) {
				return $this->Cache->get($class, "classes");	
			} else {
				include $file;
			}
			
			if(_cacheStatus) {
				$$class = ZP_Singleton::instance($class);
				
				$this->Cache->save($$class, $class, "classes");	
			}

			return ZP_Singleton::instance($class);
		} else {
			die("$class class does not exists");
		}
	}
	
    /**
     * Loads a config file
     * 
     * @param string $name
     * @param bool $application = FALSE
     * @return void
     */
	public function config($name) {
		if(file_exists(_www . _sh . _config . _sh . _config . _dot . $name . _PHP)) {
			include_once _www . _sh . _config . _sh . _config . _dot . $name . _PHP;
		} else {
			if(file_exists(_www . _sh . _applications . _sh . $name . _sh . _config . _sh . _config . _dot . $name . _PHP)) {
				include_once _www . _sh . _applications . _sh . $name . _sh . _config . _sh . _config . _dot . $name . _PHP;
			} else {
				die("$name config doesn't exists");
			}
		}
	}
	
    /**
     * Loads a controller
     * 
     * @param string $name
     * @return object value
     */
	public function controller($controller, $application = NULL) {
		$parts = explode("_", $controller);
	
		if(!$this->application) { 
			if(file_exists(_www . _sh . _applications . _sh . $application . _sh . _controllers . _sh . _controller . _dot . strtolower($parts[0]) . _PHP)) {
				$file = _www . _sh . _applications . _sh . $application . _sh . _controllers . _sh . _controller . _dot . strtolower($parts[0]) . _PHP;
			} elseif(count($parts) === 2) {
				$file = _www . _sh . _applications . _sh . strtolower($parts[0]) . _sh . _controllers . _sh . _controller . _dot . strtolower($parts[0]) . _PHP;
			}		
		} else {
			if(file_exists(_www . _sh . _applications . _sh . $application . _sh . _controllers . _sh . _controller . _dot . strtolower($parts[0]) . _PHP)) {
				$file = _www . _sh . _applications . _sh . $application . _sh . _controllers . _sh . _controller . _dot . strtolower($parts[0]) . _PHP;
			} elseif(file_exists(_www . _sh . _applications . _sh . $this->application . _sh . _controllers . _sh . _controller . _dot . strtolower($parts[0]) . _PHP)) {
				$file = _www . _sh . _applications . _sh . $this->application . _sh . _controllers . _sh . _controller . _dot . strtolower($parts[0]) . _PHP;
			} else {
				$file = _www . _sh . _applications . _sh . strtolower($parts[0]) . _sh . _controllers . _sh . _controller . _dot . strtolower($parts[0]) . _PHP;
			}
		}
		
		if(file_exists($file)) {							
			if(class_exists($controller)) {
				return ZP_Singleton::instance($controller);
			}
		
			include $file;
			
			return ZP_Singleton::instance($controller);
		}	
		
		return FALSE;
	}
	
    /**
     * Loads a core class
     * 
     * @param string $class
     * @return object value
     */
	public function core($core) {
		return ZP_Singleton::instance("ZP_$core");		
	}
	
    /**
     * Sets a CSS file from an specific application
     * 
     * @param string $CSS = NULL
     * @param string $application = NULL
     * @param bool $print = FALSE
     * @return void
     */
	public function CSS($CSS = NULL, $application = NULL, $print = FALSE) {
		$this->Templates = $this->core("Templates");
	
		$this->Templates->CSS($CSS, $application, $print);
	}

	public function driver($driver = NULL) {
		if(file_exists(_corePath . _sh . _drivers . _sh . _driver . _dot . strtolower($driver) . _PHP)) {
			$file = _corePath . _sh . _drivers . _sh . _driver . _dot . strtolower($driver) . _PHP;	
		} else {
			$file = FALSE;	
		}
		
		$this->Cache = $this->core("Cache");
		
		if(file_exists($file)) {							
			if(class_exists($driver)) {
				if($this->Cache->get($driver, "drivers")) {
					return $this->Cache->get($driver, "drivers");	
				} else {
					return ZP_Singleton::instance("ZP_". $driver);
				}
			}
			
			if($this->Cache->get($driver, "drivers")) {
				return $this->Cache->get($driver, "drivers");	
			} else {
				include $file;
			}
			
			if(_cacheStatus) {
				$$driver= ZP_Singleton::instance("ZP_". $driver);
				
				$this->Cache->save($$driver, $driver, "drivers");	
			}

			return ZP_Singleton::instance("ZP_". $driver);
		} else {
			die("$driver driver does not exists");
		}
	}
	
	public function exception($exception) {
		if(file_exists(_www . _sh . _lib . _sh . _exceptions . _sh . _exception . _dot . strtolower($exception) . _PHP)) {
			include_once _www . _sh . _lib . _sh .  _exceptions . _sh . _exception . _dot . strtolower($exception) . _PHP;
		} else {
			return FALSE;
		}
	}

	public function execute($Class, $method, $params = array(), $type = "controller") {
		if($type === "controller") {
			$this->$Class = $this->controller($Class);	
		} elseif($type === "model") {
			$this->$Class = $this->model($Class);
		}

		call_user_func_array(array($this->$Class, $method), $params);
	}
	
    /**
     * Loads a footer template
     *
     * @return void
     */
	private function footer() {
		if($this->Templates->exists("footer")) {
			if(count($this->views) > 0) {
				for($i = 0; $i <= count($this->views) - 1; $i++) {
					if($this->views[$i]["vars"] !== FALSE) {
						$this->Templates->vars($this->views[$i]["vars"]);
					}
				}
			}
			
			$this->Templates->load("footer");				
		}
	}
	
    /**
     * Load header template
     *
     * @return void
     */
	private function header() {
		if($this->Templates->exists("header")) {
			if(count($this->views) > 0) {
				for($i = 0; $i <= count($this->views) - 1; $i++) {
					if($this->views[$i]["vars"] !== FALSE) {
						$this->Templates->vars($this->views[$i]["vars"]);
					}
				}
			}
			
			$this->Templates->load("header");
		}		
	}
	
    /**
     * Loads a helper or multiple helper files
     * 
     * @param mixed  $helper
     * @param string $application
     * @return void
     */	
	public function helper($helper, $application = NULL) {
		if(is_array($helper)) { 
			for($i = 0; $i <= count($helper) - 1; $i++) {
				if($application === NULL) {
					if(file_exists(_corePath . _sh . _helpers . _sh . _helper . _dot . $helper[$i] . _PHP)) {
						include_once _corePath . _sh . _helpers . _sh . _helper . _dot . $helper[$i] . _PHP;
					} elseif(file_exists(_www . _sh . _helpers . _sh . _helper . _dot . $helper[$i] . _PHP)) {
						include_once _www . _sh . _helpers . _sh . _helper . _dot . $helper[$i] . _PHP;
					} else {		
						die("$helper[$i] helper doesn't exists");
					}			
				} else {
					if(file_exists(_www . _sh . _applications . _sh . $application . _sh . _helpers . _sh . _helper . _dot . $helper[$i] . _PHP)) {
						include_once _www . _sh . _applications . _sh . $application . _sh . _helpers . _sh . _helper . _dot . $helper[$i] . _PHP;
					} else {			
						die("$helper[$i] helper doesn't exists");
					}				
				}
			}
		} else { 
			if(is_null($application)) {
				if(file_exists(_corePath . _sh . _helpers . _sh . _helper . _dot . $helper . _PHP)) {
					include_once _corePath . _sh . _helpers . _sh . _helper . _dot . $helper . _PHP;
				} elseif(file_exists(_www . _sh . _helpers . _sh . _helper . _dot . $helper . _PHP)) {
						include_once _www . _sh . _helpers . _sh . _helper . _dot . $helper . _PHP;
				}  else {			
					die("$name helper doesn't exists");
				}
			} else {
				if(file_exists(_www . _sh . _applications . _sh . $application . _sh . _helpers . _sh . _helper . _dot . $helper . _PHP)) {
					include_once _www . _sh . _applications . _sh . $application . _sh . _helpers . _sh . _helper . _dot . $helper . _PHP;
				} else {			
					die("$name helper doesn't exists");
				}			
			}
		}
	}
	
    /**
     * Loads a hook or multiple hook files
     * 
     * @param string $hook
     * @param string $application = NULL
     * @return void
     */	
	public function hook($hook, $application = NULL) {
		if(is_array($hook)) {
			for($i = 0; $i <= count($hook) - 1; $i++) {
				if(is_null($application)) {
					if(file_exists(_corePath . _sh . _hooks . _sh . _hook . _dot . $hook[$i] . _PHP)) {
						include_once _corePath . _sh . _hooks . _sh . _hook . _dot . $hook[$i] . _PHP;
					} else {			
						die("$name hook doesn't exists");
					}			
				} else {
					if(file_exists(_www . _sh . _applications . _sh . $application . _sh . _hooks . _sh . _hook . _dot . $hook[$i] . _PHP)) {
						include_once _www . _sh . _applications . _sh . $application . _sh . _hooks . _sh . _hook . _dot . $hook[$i] . _PHP;
					} else {			
						die("$name hook doesn't exists");
					}				
				}
			}
		} else {
			if(is_null($application)) {
				if(file_exists(_corePath . _sh . _hooks . _sh . _hook . _dot . $hook . _PHP)) {
					include_once _corePath . _sh . _hooks . _sh . _hook . _dot . $hook . _PHP;
				} else {			
					die("$name hook doesn't exists");
				}
			} else {
				if(file_exists(_www . _sh . _applications . _sh . $application . _sh . _hooks . _sh . _hook . _dot . $hook . _PHP)) {
					include_once _www . _sh . _applications . _sh . $application . _sh . _hooks . _sh . _hook . _dot . $hook . _PHP;
				} else {			
					die("$name hook doesn't exists");
				}			
			}
		}
	}
	
	/**
     * Sets a JS file from an specific application
     * 
     * @param string $script
     * @param string $application = NULL
     * @param bool   $extra       = NULL
     * @param bool   $getJs       = FALSE
     * @return void
     */
	public function js($script, $application = NULL, $extra = NULL, $getJs = FALSE) {
		$this->Templates = $this->core("Templates");
		
		$this->Templates->js($script, $application, $extra, $getJs);
	}
	
    /**
     * Load languages files
     * 
     * @param string $language
     * @return void
     */
	public function language($language) {
		if(file_exists(_www . _sh . _lib . _sh . _languages . _sh . _language . _dot . strtolower($language) . _PHP)) {
			include_once _www . _sh . _lib . _sh .  _languages . _sh . _language . _dot . strtolower($language) . _PHP;
		} else {
			return FALSE;
		}
	}
	
    /**
     * Loads a left template
     *
     * @return void
     */
	private function left() {
		if($this->Templates->exists("left")) {
			if(count($this->views) > 0) {
				for($i = 0; $i <= count($this->views) - 1; $i++) {
					if($this->views[$i]["vars"] !== FALSE) {
						$this->Templates->vars($this->views[$i]["vars"]);
					}
				}
			}
			
			$this->Templates->load("left");
		}
	}
	
    /**
     * Loads a library file
     * 
     * @param string $name
     * @param string $library
     * @return void
     */
	public function library($name, $library = NULL, $application = NULL) {	
		$lib = str_replace("library.", "", $name);
		
		if(isset($name) and $library !== NULL) {
			if(file_exists(_corePath . _sh . _libraries . _sh . $library . _sh . $name . _PHP)) {
				include_once _corePath . _sh . _libraries . _sh . $library . _sh . $name . _PHP;				
			} else {
				die("$name library doesn't exists");
			}
		} elseif(isset($name) and !is_null($application)) {
	
			if(file_exists(_www . _sh . _applications . _sh . $application . _sh . _libraries . _sh . _library . "." . $name . _PHP)) {
				include_once _www . _sh . _applications . _sh . $application . _sh . _libraries . _sh . _library . "." . $name . _PHP;				
			} else {
				die("$name library doesn't exists");
			}
		} else {
			if(file_exists(_corePath . _sh . _libraries . _sh . $lib . _sh . strtolower($name) . _PHP)) {
				include_once _corePath . _sh . _libraries . _sh . $lib . _sh . strtolower($name) . _PHP;										
			} else {
				die("$name library doesn't exists");
			}			
		}
	}

    /**
     * Loads a model file
     *
     * @param string $name
     * @return object value
     */
	public function model($model) {
		$parts = explode("_", $model);

		if(!$this->application) {
			if(count($parts) === 2) {
				$file = _www . _sh . _applications . _sh . strtolower($parts[0]) . _sh . _models . _sh . _model . _dot . strtolower($parts[0]) . _PHP;	
			}		
		} else {
			if(count($parts) === 2) {
				if(file_exists(_www . _sh . _applications . _sh . $this->application . _sh . _models . _sh . _model . _dot . strtolower($parts[0]) . _PHP)) {
					$file = _www . _sh . _applications . _sh . $this->application . _sh . _models . _sh . _model . _dot . strtolower($parts[0]) . _PHP;
				} else {
					$file = _www . _sh . _applications . _sh . strtolower($parts[0]) . _sh . _models . _sh . _model . _dot . strtolower($parts[0]) . _PHP;
				}
			}
		}
	
		if(file_exists($file)) {							
			if(class_exists($model)) { 
				return ZP_Singleton::instance($model);
			}
			
			include $file;
							
			return ZP_Singleton::instance($model);
		}	
		
		return FALSE;
	}
	
    /**
     * Render and output templates
     *
     * @return void
     */
	public function render() {
		$numArgs = func_num_args();
		$args    = func_get_args();
		
		if($numArgs > 0) {
			for($i = 0; $i <= $numArgs - 1; $i++) {
				if($this->views[$i]["vars"] !== FALSE) {
					$this->Templates->vars($this->views[$i]["vars"]);
				}
				
				if(isset($args[$i]) and $args[$i] === "header") {
					$this->header();
				}
				
				if($args[$i] !== "header" and $args[$i] !== "left" and $args[$i] !== "right" and $args[$i] !== "footer") {
					if($this->Templates->exists($args[$i])) {
						$this->Templates->load($args[$i]);
						
						continue;
					} 
				}
				
				if(isset($args[$i]) and $args[$i] === "left") {
					$this->left();
				} 
				
				if(count($this->views) > 0) {
					for($i = 0; $i <= count($this->views) - 1; $i++) {						
						$this->Templates->load($this->views[$i]["name"]);						
					}
				}
				
				if(isset($args[$i]) and $args[$i] === "right") {
					$this->right();
				} 
				
				if(isset($args[$i]) and $args[$i] === "footer") {
					$this->footer();
				}
			}
		} else {
			for($i = 0; $i <= count($this->views) - 1; $i++) {
				if($this->views[$i]["vars"] !== FALSE) {
					$this->Templates->vars($this->views[$i]["vars"]);
				}
			}
			
			$this->header();
			$this->left();
			
			if(count($this->views) > 0) {
				for($i = 0; $i <= count($this->views) - 1; $i++) {
					$this->Templates->load($this->views[$i]["name"]);					
				}
			}
			
			$this->right();
			$this->footer();
		}
	}
	
    /**
     * Loads a right template
     *
     * @return void
     */
	private function right() {
		if($this->Templates->exists("right")) {
			if(count($this->views) > 0) {
				for($i = 0; $i <= count($this->views) - 1; $i++) {
					if($this->views[$i]["vars"] !== FALSE) {
						$this->Templates->vars($this->views[$i]["vars"]);
					}
				}
			}
			
			$this->Templates->load("right");
		}
	}	
	
    /**
     * Loads templates
     *
     * @param string $name
     * @param string $vars
     * @return string value / void
     */	
	public function template($name, $vars = NULL) {			
		if(is_array($vars)) {
			if(count($this->views) === 0) {
				$this->views[0]["name"] = $name;
				$this->views[0]["vars"] = $vars;
			} else {
				$i = count($this->views);
					
				$this->views[$i]["name"] = $name;
				$this->views[$i]["vars"] = $vars;				
			}
		} else {
			$i = count($this->views);
				
			$this->views[$i]["name"] = $name;
			$this->views[$i]["vars"] = FALSE;		
		}

		$this->render();
	}
	
    /**
     * Set the current theme
     *
     * @return void
     */
	public function theme($theme) {
		$this->Templates = $this->core("Templates");
		
		$this->Templates->theme($theme);
	}
	
    /**
     * Set title for header template
     *
     * @param string $title = NULL
     * @return void
     */
	public function title($title = NULL) {
		$this->Templates = $this->core("Templates");
		
		$this->Templates->title(__($title));
	}
	
    /**
     * Loads a view
     *
     * @param string $name
     * @param string $application = NULL
     * @param string $vars        = NULL
     * @return string value / void
     */	
	public function view($name, $vars = NULL, $application = NULL) {
		if(is_null($application)) {
			$application = whichApplication();
		} 

		if(!is_null($application)) {
			$view 	 = _www . _sh . _applications . _sh . $application . _sh . _views . _sh . _view . _dot . $name . _PHP;
			$cacheID = cacheSession($name);
			
			if(is_array($vars)) {
				$key  = array_keys($vars);
				$size = sizeof($key);			
			
				for($i = 0; $i < $size; $i++) {
					$$key[$i] = $vars[$key[$i]];
				}
			} elseif($vars) {
				return $view;
			}
				
			if(file_exists($view)) {
				$this->Cache = $this->core("Cache");	

				if($this->Cache->get($cacheID, "views")) {
					print $this->Cache->get($cacheID, "views");	
				} else {
					include $view;
				}
				
				if(_cacheStatus) {
					$output = ob_get_contents();

					ob_end_clean();
					
					$this->Cache->save($output, $cacheID, "views");
					
					print $output;	
				} 
				
			} else {
				die("Error 404: $view view not found");	
			}
		} else {
			return FALSE;
		}
	}
	
}
