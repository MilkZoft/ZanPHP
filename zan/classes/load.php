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
 * Access from index.php
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

/**
 * Includes Singleton 
 */
include "singleton.php";

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

	public $ZP;
	
    /**
     * Loads helper autoload, database config and class templates
     *
     * @return void
     */
	public function __construct() {
		$this->helper(array("autoload", "config", "validations"));
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
	public function classes($name, $className = NULL, $params = array(), $application = NULL) {			
		if(file_exists("www/applications/$application/classes/$name.php")) {
			include_once "www/applications/$application/classes/$name.php";	
		} elseif(file_exists("www/classes/$name.php")) {
			include_once "www/classes/$name.php";	
		} else {
			getException("$name class doesn't exists");
		}

		return ($className) ? ZP_Singleton::instance($className, $params) : TRUE;
	}
	
    /**
     * Loads a config file
     * 
     * @param string $name
     * @param bool $application = FALSE
     * @return void
     */
	public function config($name, $application = FALSE) {
		if($application) {
			if(file_exists("www/applications/$application/config/$name.php")) {
				include_once "www/applications/$application/config/$name.php";
			}
		} elseif(file_exists("www/config/$name.php")) {
			include_once "www/config/$name.php";
		} else {
			if(file_exists("www/applications/$name/config/$name.php")) {
				include_once "www/applications/$name/config/$name.php";
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
			if(file_exists("www/applications/$application/controllers/". strtolower($parts[0]) .".php")) {
				$file = "www/applications/$application/controllers/". strtolower($parts[0]) .".php";
			} elseif(count($parts) === 2) {
				$file = "www/applications/". strtolower($parts[0]) ."/controllers/". strtolower($parts[0]) .".php";
			}		
		} else { 
			if(file_exists("www/applications/$application/controllers/". strtolower($parts[0]) .".php")) {
				$file = "www/applications/$application/controllers/". strtolower($parts[0]) .".php";
			} elseif(file_exists("www/applications/$this->application/controllers/". strtolower($parts[0]) .".php")) {
				$file = "www/applications/$this->application/controllers/". strtolower($parts[0]) .".php";
			} else {
				$file = "www/applications/". strtolower($parts[0]) ."/controllers/". strtolower($parts[0]) .".php";
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

	public function driver($driver = NULL, $type = "db") {
		if(file_exists(_corePath ."/drivers/$type/". strtolower($driver) .".php")) {
			$file = _corePath ."/drivers/$type/". strtolower($driver) .".php";	
		} else {
			$file = FALSE;	
		}

		if(file_exists($file)) {	
			include $file;
				
			return ZP_Singleton::instance("ZP_". $driver);
		} else {
			getException("$driver driver does not exists");
		}	
	}
	
	public function exception($exception) {
		$exception = strtolower($exception);

		if(file_exists("www/lib/exceptions/$exception.php")) {
			include_once "www/lib/exceptions/$exception.php";
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
		
		return ($this->$Class) ? call_user_func_array(array($this->$Class, $method), is_array($params) ? $params : array()) : FALSE;
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
					if(file_exists(_corePath . "/helpers/". $helper[$i] .".php")) {
						include_once _corePath . "/helpers/". $helper[$i] .".php";
					} elseif(file_exists("www/helpers/". $helper[$i] .".php")) {
						include_once "www/helpers/". $helper[$i] .".php";
					} else {
						getException($helper[$i] ." helper doesn't exists");		
					}			
				} else {
					if(file_exists("www/applications/$application/helpers/". $helper[$i] .".php")) {
						include_once "www/applications/$application/helpers/". $helper[$i] .".php";
					} else {			
						getException($helper[$i] ." helper doesn't exists");
					}				
				}
			}
		} else { 
			if(is_null($application)) {
				if(file_exists(_corePath ."/helpers/$helper.php")) {
					include_once _corePath ."/helpers/$helper.php";
				} elseif(file_exists("www/helpers/$helper.php")) {
					include_once "www/helpers/$helper.php";
				}  else {			
					getException("$helper helper doesn't exists");
				}
			} else {
				if(file_exists("www/applications/$application/helpers/$helper.php")) {
					include_once "www/applications/$application/helpers/$helper.php";
				} else {			
					getException("$helper helper doesn't exists");
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
					if(file_exists(_corePath ."/hooks/". $hook[$i] .".php")) {
						include_once _corePath ."/hooks/". $hook[$i] .".php";
					} else {			
						getException("$name hook doesn't exists");
					}			
				} else {
					if(file_exists("www/applications/$application/hooks/". $hook[$i] .".php")) {
						include_once "www/applications/$application/hooks/". $hook[$i] .".php";
					} else {			
						getException("$name hook doesn't exists");
					}				
				}
			}
		} else {
			if(is_null($application)) {
				if(file_exists(_corePath ."hooks/$hook.php")) {
					include_once _corePath ."hooks/$hook.php";
				} else {			
					getException("$name hook doesn't exists");
				}
			} else {
				if(file_exists("www/applications/$application/hooks/$hook.php")) {
					include_once "www/applications/$application/hooks/$hook.php";
				} else {			
					getException("$name hook doesn't exists");
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
     * @param string $className
     * @param array  $params
     * @param string $application
     * @return void
     */
	public function library($name, $className = NULL, $params = array(), $application = NULL) {	
		if(file_exists(_corePath ."/libraries/$application/$name.php")) {
			include_once _corePath ."/libraries/$application/$name.php";	
		} elseif(file_exists(_corePath ."/libraries/$className/$name.php")) {
			include_once _corePath ."/libraries/$lib/$name.php";	
		} elseif(file_exists("www/applications/$application/libraries/$name.php")) {
			include_once "www/applications/$application/libraries/$name.php";				
		} else {
			getException("$name library doesn't exists");
		}

		return ($className) ? ZP_Singleton::instance($className, $params) : TRUE;
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
				$file = "www/applications/". strtolower($parts[0]) ."/models/". strtolower($parts[0]) .".php";	
			}		
		} else { 
			if(count($parts) === 2) {
				if(file_exists("www/applications/$this->application/models/". strtolower($parts[0]) .".php")) {
					$file = "www/applications/$this->application/models/". strtolower($parts[0]) .".php";
				} elseif(file_exists("www/applications/$this->application/models/". strtolower($parts[0]) .".php")) {
					$file = "www/applications/$this->application/models/". strtolower($parts[0]) .".php";
				} else {
					$file = "www/applications/". strtolower($parts[0]) ."/models/". strtolower($parts[0]) .".php";
				}
			}
		}

		if(file_exists($file)) { 					
			if(class_exists($model)) { 
				return ZP_Singleton::instance($model);
			}
	
			require $file;
			
			return ZP_Singleton::instance($model);
		}	
		
		return FALSE;
	}
	
    /**
     * Render and output templates
     *
     * @return void
     */
	public function rendering() {
		$numArgs = func_num_args();
		$args    = func_get_args();
		
		if($numArgs > 0) {
			for($i = 0; $i <= $numArgs - 1; $i++) {
				if($this->views[$i]["vars"]) {
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
				if($this->views[$i]["vars"]) {
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
	public function render($name, $vars = NULL) {	
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
		
		if($name !== "include" and get("autoRender")) {
			$this->rendering();
		} elseif($name === "include") {
			$this->rendering();
		}
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
		
		$this->Templates->title($title);
	}
        
	public function vars($vars) {
		$this->Templates->vars($vars);
	}
        
        public function meta($title = NULL, $description = NULL, $keywords = NULL, $language = NULL) {
            $this->Templates = $this->core("Templates");
            
            $this->Templates->meta($title, $description, $keywords, $language);
        }
        
        public function setMeta($tag, $value) {
            $this->Templates = $this->core("Templates");
            
            $this->Templates->setMeta($tag, $value);
        }
	
    /**
     * Loads a view
     *
     * @param string $name
     * @param string $application = NULL
     * @param string $vars        = NULL
     * @return string value / void
     */	
	public function view($name, $vars = NULL, $application = NULL, $return = FALSE) {
		if(is_null($application)) {
			$application = whichApplication();
		} 

		if(!is_null($application)) {
			$view = "www/applications/$application/views/$name.php";
			
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
				ob_start();

				include $view;
				
				if($return) {
					$output = ob_get_contents();

					ob_end_clean();

					return $output;
				}
			} else {
				getException("Error 404: $view view not found");	
			}
		} else {
			return FALSE;
		}
	}
	
}