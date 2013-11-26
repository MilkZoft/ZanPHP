<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

include "singleton.php";

class ZP_Load
{
	public $application = false;
	public $Templates;
	private $views = array();
	public $ZP;

	public function __construct()
	{
		$this->helper(array("autoload", "browser", "config", "validations"));
	}

	public function app($application)
	{
		return $this->application = $application;
	}
	
	public function classes($name, $className = null, $params = array(), $application = null)
	{
		$name = strtolower($name);

		if (file_exists("www/applications/$application/classes/$name.php")) {
			include_once "www/applications/$application/classes/$name.php";
		} elseif (file_exists("www/classes/$name.php")) {
			include_once "www/classes/$name.php";
		} else {
			getException("$name class doesn't exists");
		}

		return ($className) ? ZP_Singleton::instance($className, $params) : true;
	}

	public function config($name, $application = false)
	{
		if ($application) {
			if (file_exists("www/applications/$application/config/$name.php")) {
				include_once "www/applications/$application/config/$name.php";
			}
		} elseif (file_exists("www/config/$name.php")) {
			include_once "www/config/$name.php";
		} else {
			if (file_exists("www/applications/$name/config/$name.php")) {
				include_once "www/applications/$name/config/$name.php";
			}
		}
	}

	public function controller($controller, $application = null)
	{
		$parts = explode("_", $controller);
	
		if (!$this->application) {
			if (file_exists("www/applications/$application/controllers/". strtolower($parts[0]) .".php")) {
				$file = "www/applications/$application/controllers/". strtolower($parts[0]) .".php";
			} elseif (count($parts) === 2) {
				$file = "www/applications/". strtolower($parts[0]) ."/controllers/". strtolower($parts[0]) .".php";
			}
		} else {
			if (file_exists("www/applications/$application/controllers/". strtolower($parts[0]) .".php")) {
				$file = "www/applications/$application/controllers/". strtolower($parts[0]) .".php";
			} elseif (file_exists("www/applications/$this->application/controllers/". strtolower($parts[0]) .".php")) {
				$file = "www/applications/$this->application/controllers/". strtolower($parts[0]) .".php";
			} else {
				$file = "www/applications/". strtolower($parts[0]) ."/controllers/". strtolower($parts[0]) .".php";
			}
		}
		
		if (file_exists($file)) {
			if (class_exists($controller)) {
				return ZP_Singleton::instance($controller);
			}
		
			include $file;
			return ZP_Singleton::instance($controller);
		}	
		
		return false;
	}

	public function core($core)
	{
		return ZP_Singleton::instance("ZP_$core");
	}

	public function CSS($CSS = null, $application = null, $print = false, $top = false)
	{
		$this->Templates = $this->core("Templates");
		$this->Templates->CSS($CSS, $application, $print, $top);
	}

	public function driver($driver = null, $type = "db")
	{
		if (file_exists(CORE_PATH ."/drivers/$type/". strtolower($driver) .".php")) {
			$file = CORE_PATH ."/drivers/$type/". strtolower($driver) .".php";
		} else {
			$file = false;	
		}

		if (file_exists($file)) {
			include $file;	
			return ZP_Singleton::instance("ZP_". $driver);
		} else {
			getException("$driver driver does not exists");
		}	
	}

	public function db($type = "db")
	{ 
		if (strtolower($type) === "db") {
			return $this->core("Db");
		} elseif (strtolower($type) === "mongodb" or strtolower($type) === "mongo") {
			return (DB_NOSQL_ACTIVE) ? $this->core("MongoDB") : false;
		} elseif (strtolower($type) === "couchdb" or strtolower($type) === "couch") {
			return (DB_NOSQL_ACTIVE) ? $this->core("CouchDB") : false;
		} elseif (strtolower($type) === "cassandra") {
			return (DB_NOSQL_ACTIVE) ? $this->core("Cassandra") : false;
		}
	}

	public function exception($exception)
	{
		$exception = strtolower($exception);

		if (file_exists("www/lib/exceptions/$exception.php")) {
			include_once "www/lib/exceptions/$exception.php";
		} else {
			return false;
		}
	}

	public function execute($Class, $method, $params = array(), $type = "controller")
	{
		if ($type === "controller") {
			$this->$Class = $this->controller($Class);
		} elseif ($type === "model") {
			$this->$Class = $this->model($Class);
		}
		
		return ($this->$Class) ? call_user_func_array(array($this->$Class, $method), is_array($params) ? $params : array()) : false;
	}

	private function footer()
	{
		if ($this->Templates->exists("footer")) {
			if (count($this->views) > 0) {
				for ($i = 0; $i <= count($this->views) - 1; $i++) {
					if ($this->views[$i]["vars"] !== false) {
						$this->Templates->vars($this->views[$i]["vars"]);
					}
				}
			}

			$this->Templates->load("footer");
		}
	}

	private function header()
	{
		if ($this->Templates->exists("header")) {
			if (count($this->views) > 0) {
				for ($i = 0; $i <= count($this->views) - 1; $i++) {
					if ($this->views[$i]["vars"] !== false) {
						$this->Templates->vars($this->views[$i]["vars"]);
					}
				}
			}
			
			$this->Templates->load("header");
		}		
	}
		
	public function helper($helper, $application = null)
	{
		if (is_array($helper)) { 
			for ($i = 0; $i <= count($helper) - 1; $i++) {
				if ($application === null) {
					if (file_exists(CORE_PATH ."/helpers/". $helper[$i] .".php")) {
						include_once CORE_PATH ."/helpers/". $helper[$i] .".php";
					} elseif (file_exists("www/helpers/". $helper[$i] .".php")) {
						include_once "www/helpers/". $helper[$i] .".php";
					} else {
						getException($helper[$i] ." helper doesn't exists");
					}
				} else {
					if (file_exists("www/applications/$application/helpers/". $helper[$i] .".php")) {
						include_once "www/applications/$application/helpers/". $helper[$i] .".php";
					} else {			
						getException($helper[$i] ." helper doesn't exists");
					}				
				}
			}
		} else { 
			if (is_null($application)) {
				if (file_exists(CORE_PATH ."/helpers/$helper.php")) {
					include_once CORE_PATH ."/helpers/$helper.php";
				} elseif (file_exists("www/helpers/$helper.php")) {
					include_once "www/helpers/$helper.php";
				}  else {
					getException("$helper helper doesn't exists");
				}
			} else {
				if (file_exists("www/applications/$application/helpers/$helper.php")) {
					include_once "www/applications/$application/helpers/$helper.php";
				} else {			
					getException("$helper helper doesn't exists");
				}	
			}
		}
	}

	public function hook($hook, $application = null)
	{
		if (is_array($hook)) {
			for ($i = 0; $i <= count($hook) - 1; $i++) {
				if (is_null($application)) {
					if (file_exists(CORE_PATH ."/hooks/". $hook[$i] .".php")) {
						include_once CORE_PATH ."/hooks/". $hook[$i] .".php";
					} else {			
						getException("$name hook doesn't exists");
					}			
				} else {
					if (file_exists("www/applications/$application/hooks/". $hook[$i] .".php")) {
						include_once "www/applications/$application/hooks/". $hook[$i] .".php";
					} else {			
						getException("$name hook doesn't exists");
					}				
				}
			}
		} else {
			if (is_null($application)) {
				if (file_exists(CORE_PATH ."hooks/$hook.php")) {
					include_once CORE_PATH ."hooks/$hook.php";
				} else {			
					getException("$name hook doesn't exists");
				}
			} else {
				if (file_exists("www/applications/$application/hooks/$hook.php")) {
					include_once "www/applications/$application/hooks/$hook.php";
				} else {			
					getException("$name hook doesn't exists");
				}			
			}
		}
	}

	public function js($script, $application = null, $getJs = false, $top = false)
	{
		$this->Templates = $this->core("Templates");	
		return $this->Templates->js($script, $application, $getJs, $top);
	}

	private function left()
	{
		if ($this->Templates->exists("left")) {
			if (count($this->views) > 0) {
				for ($i = 0; $i <= count($this->views) - 1; $i++) {
					if ($this->views[$i]["vars"] !== false) {
						$this->Templates->vars($this->views[$i]["vars"]);
					}
				}
			}
			
			$this->Templates->load("left");
		}
	}

	public function library($name, $className = null, $params = array(), $application = null)
	{	
		if (file_exists(CORE_PATH ."/libraries/$application/$name.php")) {
			include_once CORE_PATH ."/libraries/$application/$name.php";
		} elseif (file_exists(CORE_PATH ."/libraries/$className/$name.php")) {
			include_once CORE_PATH ."/libraries/$className/$name.php";
		} elseif (file_exists(CORE_PATH ."/libraries/$name/$name.php")) {
			include_once CORE_PATH ."/libraries/$name/$name.php";
		} elseif (file_exists("www/applications/$application/libraries/$name.php")) {
			include_once "www/applications/$application/libraries/$name.php";
		} else {
			getException("$name library doesn't exists");
		}

		return ($className) ? ZP_Singleton::instance($className, $params) : true;
	}

	public function model($model) {
		$parts = explode("_", $model);

		if (!$this->application) {
			if (count($parts) === 2) {
				$file = "www/applications/". strtolower($parts[0]) ."/models/". strtolower($parts[0]) .".php";
			}
		} else { 
			if (count($parts) === 2) {
				if (file_exists("www/applications/$this->application/models/". strtolower($parts[0]) .".php")) {
					$file = "www/applications/$this->application/models/". strtolower($parts[0]) .".php";
				} elseif (file_exists("www/applications/$this->application/models/". strtolower($parts[0]) .".php")) {
					$file = "www/applications/$this->application/models/". strtolower($parts[0]) .".php";
				} else {
					$file = "www/applications/". strtolower($parts[0]) ."/models/". strtolower($parts[0]) .".php";
				}
			}
		}

		if (file_exists($file)) {
			if (class_exists($model)) { 
				return ZP_Singleton::instance($model);
			}
	
			require $file;
			return ZP_Singleton::instance($model);
		}	
		
		return false;
	}

	public function rendering() 
	{ 
		$numArgs = func_num_args();
		$args = func_get_args();

		if ($numArgs > 0) {
			for ($i = 0; $i <= $numArgs - 1; $i++) {
				if ($this->views[$i]["vars"]) {
					$this->Templates->vars($this->views[$i]["vars"]);
				}

				if (isset($args[$i]) and $args[$i] === "header") {
					$this->header();
				}

				if ($args[$i] !== "header" and $args[$i] !== "left" and $args[$i] !== "right" and $args[$i] !== "footer") {
					if ($this->Templates->exists($args[$i])) {
						$this->Templates->load($args[$i]);
						continue;
					} 
				}
				
				if (isset($args[$i]) and $args[$i] === "left") {
					$this->left();
				} 
				
				if (count($this->views) > 0) {
					for ($i = 0; $i <= count($this->views) - 1; $i++) {
						$this->Templates->load($this->views[$i]["name"]);
					}
				}
				
				if (isset($args[$i]) and $args[$i] === "right") {
					$this->right();
				} 
				
				if (isset($args[$i]) and $args[$i] === "footer") {
					$this->footer();
				}
			}
		} else { 
			for ($i = 0; $i <= count($this->views) - 1; $i++) {
				if ($this->views[$i]["vars"]) {
					$this->Templates->vars($this->views[$i]["vars"]);
				}
			}

			$this->header();
			$this->left();

			if (count($this->views) > 0) {
				for ($i = 0; $i <= count($this->views) - 1; $i++) {
					$this->Templates->load($this->views[$i]["name"]);
				}
			}
			
			$this->right();
			$this->footer();
		}
	}

	private function right()
	{
		if ($this->Templates->exists("right")) {
			if (count($this->views) > 0) {
				for ($i = 0; $i <= count($this->views) - 1; $i++) {
					if ($this->views[$i]["vars"] !== false) {
						$this->Templates->vars($this->views[$i]["vars"]);
					}
				}
			}
			
			$this->Templates->load("right");
		}
	}	

	public function render($name, $vars = null)
	{
		if (is_array($vars)) { 
			if (count($this->views) === 0) {
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
			$this->views[$i]["vars"] = false;
		}
		
		if ($name !== "include" and _get("autoRender")) {
			$this->rendering();
		}
	}

	public function theme($theme)
	{
		$this->Templates = $this->core("Templates");
		$this->Templates->theme($theme);
	}

	public function title($title = null)
	{
		$this->Templates = $this->core("Templates");
		$this->Templates->title($title);
	}
        
	public function vars($vars)
	{
		$this->Templates->vars($vars);
	}

    public function meta($tag, $value) 
    {
        $this->Templates = $this->core("Templates");        
        $this->Templates->meta($tag, $value);
    }

	public function view($name, $vars = null, $application = null, $return = false)
	{
		if (is_null($application)) {
			$application = whichApplication();
		} 

		if (!is_null($application) and is_string($application) and is_string($name)) {
			$theme = _get("webTheme");

			if (file_exists("www/lib/themes/$theme/views/$application/$name.php")) { 
				$view 	 = "www/lib/themes/$theme/views/$application/$name.php";
				$minView = "www/lib/themes/$theme/views/$application/min/$name.php";
			} else { 
				$view 	 = "www/applications/$application/views/$name.php";
				$minView = "www/applications/$application/views/min/$name.php";
			}

			if (_get("environment") > 2 and file_exists($minView)) {
				$view = $minView;
			}
			
			if (is_array($vars)) {
				$key = array_keys($vars);
				$size = sizeof($key);
			
				for ($i = 0; $i < $size; $i++) {
					$$key[$i] = $vars[$key[$i]];
				}
			} elseif ($vars) {
				return $view;
			}

			if (file_exists($view)) {
				ob_start();
				include $view;
				
				if ($return) {
					$output = ob_get_contents();
					ob_clean();
					return $output;
				}
			} else {
				getException("Error 404: $view view not found");
			}
		} else {
			return false;
		}
	}
}