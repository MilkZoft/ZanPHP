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
 */
 
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

/**
 * ZanPHP Templates Class
 *
 * This class is responsible for controlling design templates
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	classes
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/classes/templates_class
 */
class ZP_Templates extends ZP_Load {
	/**
	 * Contains the CSS style from an specific application
	 * 
	 * @var private $CSS = NULL
	 */
	private $CSS = NULL;
	
	/**
	 * 
	 * 
	 * 
	 */
	private $js = NULL;
	
	/**
	 * Contains the name of the current theme
	 * 
	 * @var private $theme = NULL
	 */
	private $theme = NULL;
	
	/**
	 * Contains the path of the theme
	 * 
	 * @var public $themePath
	 */
	public $themePath;
	
	/**
	 * Contains the title for the header template
	 * 
	 * @var private $title = get("webNam"]e
	 */
	private $title;
	
	/**
	 * Contains the array of vars
	 * 
	 * @var public $vars = array()
	 */
	private $vars = array();
	
    /**
     * Load helpers: array, browser, debugging, forms, html and web
     *
     * @return void
     */
	public function __construct() {
		$helpers = array("config", "array", "browser", "debugging", "forms", "html", "scripts", "validations");
		
		$this->helper($helpers);
	}
	
    /**
     * Set the CSS style
     *
     * @return void
     */	
	public function CSS($CSS = NULL, $application = NULL, $print = FALSE) {
		if(file_exists($CSS)) { 
			if($print) {
				print '<link rel="stylesheet" href="'. get("webURL") . _sh . $CSS .'" type="text/css" />' . "\n";
			} else { 
				$this->CSS .= '<link rel="stylesheet" href="'. get("webURL") . _sh . $CSS .'" type="text/css" />' . "\n";
			}
		} 

		if($CSS === "bootstrap") {
			if(is_null($this->CSS)) {
				if($print) {
					print '<link rel="stylesheet" href="'. get("webURL") .'/www/lib/css/frameworks/bootstrap/bootstrap.min.css" type="text/css" />' . "\n";
				} else {
					$this->CSS  = '<link rel="stylesheet" href="'. get("webURL") .'/www/lib/css/frameworks/bootstrap/bootstrap.min.css" type="text/css" />' . "\n";
				}
			} else {
				if($print) {
					print '<link rel="stylesheet" href="'. get("webURL") .'/www/lib/css/frameworks/bootstrap/bootstrap.min.css" type="text/css" />' . "\n";
				} else {
					$this->CSS .= '<link rel="stylesheet" href="'. get("webURL") .'/www/lib/css/frameworks/bootstrap/bootstrap.min.css" type="text/css" />' . "\n";
				}	
			}
		}

		if(is_null($application)) {
			$file = "www/lib/css/$CSS.css";
		} else {
			$file = "www/applications/$application/views/css/$CSS.css";
		}
		
		if(is_null($this->CSS)) {
			if($print) {
				print '<link rel="stylesheet" href="'. get("webURL") .'/www/lib/css/default.css" type="text/css" />' . "\n";
			} else {
				$this->CSS = '<link rel="stylesheet" href="'. get("webURL") .'/www/lib/css/default.css" type="text/css" />' . "\n";
			}			
		}
		
		if(file_exists($file)) {
			if($print) {
				print '<link rel="stylesheet" href="'. get("webURL") .'/'. $file .'" type="text/css" />' . "\n";
			} else {
				$this->CSS .= '<link rel="stylesheet" href="'. get("webURL") .'/'. $file .'" type="text/css" />' . "\n";
			}
		}
	}
	
    /**
     * Verify if a template exists
     *
     * @return boolean value
     */
	public function exists($template, $view = FALSE) {
		if(!$view) {
			if(file_exists("www/lib/themes/$this->theme/$template.php")) {
				return TRUE; 
			} 
		} elseif(file_exists("www/applications/$view/views/view.$template.php")) {
			return TRUE; 
		} 
		
		return FALSE;
	}
	
    /**
     * Get the CSS style
     *
     * @return void
     */
	public function getCSS() {
		return $this->CSS;
	}
	
    /**
     * Get the Js
     *
     * @return void
     */
	public function getJs() {
		return $this->js;
	}
	
    /**
     * Gets the list of available designs
     *
     * @return array value
     */	
	public function getThemes($theme) {
		$path    = "www/lib/themes/";
		$dir	 = dir($path);
		$options = FALSE;
		
		$i = 0;
		
		while($element = $dir->read()) {
			$directory = $path . $element . _sh;						
			
			if($element !== ".." and $element !== "." and is_dir($directory) and $element !== "cpanel") {
				if($element === $theme) {
					$options[$i]["value"]    = $element;
					$options[$i]["option"]   = $element;
					$options[$i]["selected"] = TRUE;
				} else {
					$options[$i]["value"]    = $element;
					$options[$i]["option"]   = $element;
					$options[$i]["selected"] = FALSE;
				}
								
				$i++;
			}
		}	
			
		$dir->close();		
		
		return $options;
	}
	
    /**
     * Get the header title
     *
     * @return void
     */
	public function getTitle() {
		return (is_null($this->title)) ? get("webName") : __(_($this->title));
	}
	
    /**
     * Verify if a theme exists
     *
     * @return boolean value
     */
	public function isTheme() {
		if(!is_null($this->theme)) {
			$this->path = "www/lib/themes/$this->theme";
		} else {
			$this->path = FALSE;
		}
		
		$this->directory = @dir($this->path);
		
		if($this->directory) {
			return TRUE;
		}
		
		return FALSE;
	}
	
    /**
     * 
     *
     * 
     */
	public function js($js, $application = NULL, $extra = NULL, $getJs = FALSE) {
		if($getJs) {
			return getScript($js, $application, $extra, $getJs);	
		} 
		
		if(substr_count($js, "http") >= 1 or substr_count($js, "https") >= 1) {
			$this->js .= getScript($js, $application, $extra, $getJs, TRUE);
		} else {
			$this->js .= getScript($js, $application, $extra, $getJs);
		}
	}
	
    /**
     * Load template
     *
     * @return void
     */
	public function load($template, $direct = FALSE) {			
		if(is_array($this->vars)) {
			$key  = array_keys($this->vars);
			$size = sizeof($key);			
		
			for($i = 0; $i < $size; $i++) {
				$$key[$i] = $this->vars[$key[$i]];
			}
		}
		
		if($direct) { 
			if(is_array($template)) {
				for($i = 0; $i <= count($template) - 1; $i++) {
					include $template[$i];
				}
			} else {
				if(!file_exists($template)) {
					getException("Error 404: Theme Not Found: " . $template);
				}		
				
				include $template;
			}
		} else { 
			$template = "www/lib/themes/$this->theme/$template.php";
		
			if(!file_exists($template)) {
				getException("Error 404: Theme Not Found: " . $template);									
			}
			
			include $template;	
		}						
	}
	
    /**
     * Set the current theme
     *
     * @return void
     */
	public function theme($theme = NULL) {
		$this->theme = (is_null($theme)) ? get("webTheme") : $theme;
		
		$this->themePath = get("webURL") ."/www/lib/themes/$this->theme";
		
		if(!$this->isTheme()) {
			die("You need to create a valid theme");
		}
	}
	
    /**
     * 
     *
     * 
     */
	public function themeCSS($theme = NULL) {
		$theme 	 = is_null($theme) ? get("webTheme") : $theme; 
		$file    = "www/lib/themes/". $theme ."/css/style.css";
		$browser = browser();
		
		if($browser === "Internet Explorer") {
			$style = "www/lib/themes/". $theme ."/css/ie.style.css";

			if(file_exists($style)) {
				return '<link rel="stylesheet" href="'. $this->themePath .'/css/ie.style.css" type="text/css">';
			} 
			
			return '<link rel="stylesheet" href="'. $this->themePath .'/css/style.css" type="text/css">';	
		} else {			
			return '<link rel="stylesheet" href="'. $this->themePath .'/css/style.css" type="text/css">';			
		}
	}
	
    /**
     * Set header title
     *
     * @return void
     */
	public function title($title = NULL) {
		$this->title = is_null($title) ? get("webName") : get("webName") ." - ". $title;
	}
	
    /**
     * Set vars
     *
     * @return void
     */
	public function vars($vars) {
		$this->vars = $vars;
	}
	
}