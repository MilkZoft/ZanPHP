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
	 * @var private $title = _webName
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
		$helpers = array("array", "browser", "debugging", "forms", "html", "scripts", "validations");
		
		$this->helper($helpers);
		
		$this->config("cache");
		$this->config("templates");
	}
	
    /**
     * Set the CSS style
     *
     * @return void
     */	
	public function CSS($CSS = NULL, $application = NULL, $print = FALSE) {
		if(is_null($application)) {
			$file = _www . _sh . _lib . _sh . _CSS . _sh . $CSS . _dot . _CSS;
		} else {
			$file = _www . _sh . _applications . _sh . $application . _sh . _views . _sh . _CSS . _sh . $CSS . _dot . _CSS;
		}
		
		if(is_null($this->CSS)) {
			if($print) {
				print '<link rel="stylesheet" href="' . _webURL . _sh . _www . _sh . _lib . _sh . _CSS . '/default.css" type="text/css">';
			} else {
				$this->CSS = '<link rel="stylesheet" href="' . _webURL . _sh . _www . _sh . _lib . _sh . _CSS . '/default.css" type="text/css">';
			}			
		}
		
		if(file_exists($file)) {
			if($print) {
				print '<link rel="stylesheet" href="' . _webURL . _sh . $file . '" type="text/css">' . "\n";
			} else {
				$this->CSS .= '<link rel="stylesheet" href="' . _webURL . _sh . $file . '" type="text/css">' . "\n";
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
			if(file_exists(_www . _sh . _lib . _sh . _themes . _sh . $this->theme . _sh . $template . _PHP)) {
				return TRUE; 
			} 
		} elseif(file_exists(_www . _sh . _applications . _sh . $view . _sh . _views . _sh . _view . _dot . $template . _PHP)) {
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
		$path    = _www . _sh . _lib . _sh . _themes . _sh;
		$dir	 = dir($path);
		$options = FALSE;
		
		$i = 0;
		
		while($element = $dir->read()) {
			$directory = $path . $element . _sh;						
			
			if($element !== ".." and $element !== "." and is_dir($directory) and $element !== _cpanel) {
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
		return (is_null($this->title)) ? _webName : $this->title;
	}
	
    /**
     * Verify if a theme exists
     *
     * @return boolean value
     */
	public function isTheme() {
		if(!is_null($this->theme)) {
			$this->path = _www . _sh . _lib . _sh . _themes . _sh . $this->theme;
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
		$this->Cache = (_cacheStatus) ? $this->core("Cache") : FALSE;
		
		if(is_array($this->vars)) {
			$key  = array_keys($this->vars);
			$size = sizeof($key);			
		
			for($i = 0; $i < $size; $i++) {
				$$key[$i] = $this->vars[$key[$i]];
			}
		}
		
		if($direct) { 
			if(is_array($template)) {
				if(count($template) === 1) {
					$buffer1 = ob_get_clean();
					
					if(_cacheStatus) { 								 
						$cache = $this->Cache->get(cacheSession($template[0]), "templates");
						
						if($cache) {
							print $buffer1;
							print $cache;

							return TRUE;
						} else {
							ob_start();
							
							include $template[0];
							
							$buffer2 = ob_get_contents();
							
							@ob_end_clean();
						
							$this->Cache->save($buffer2, cacheSession($template[0]), "templates");	
							
							print $buffer1;
							print $buffer2;
						}
					} else {
						print $buffer1;
						
						include $template[0];
					}
				} elseif(count($template) === 2) {
					$buffer1 = ob_get_clean();
					
					if(_cacheStatus) { 								 
						$cache1 = $this->Cache->get(cacheSession($template[0]), "templates");
						$cache2 = $this->Cache->get(cacheSession($template[1]), "templates");
						
						if($cache1 and $cache2) {
							print $buffer1;
							print $cache1;
							print $cache2;
						} else {
							ob_start();
							
							include $template[0];
							
							$buffer2 = ob_get_clean();

							ob_start();

							include $template[1];

							$buffer3 = ob_get_contents();
							
							@ob_end_clean();
						
							$this->Cache->save($buffer2, cacheSession($template[0]), "templates");
							$this->Cache->save($buffer3, cacheSession($template[1]), "templates");	
							
							print $buffer1;
							print $buffer2;
							print $buffer3;
						}
					} else {
						print $buffer1;
						
						include $template[0];
						include $template[1];
					}
				} elseif(count($template) === 3) {
					$buffer1 = ob_get_clean();
					
					if(_cacheStatus) { 								 
						$cache1 = $this->Cache->get(cacheSession($template[0]), "templates");
						$cache2 = $this->Cache->get(cacheSession($template[1]), "templates");
						$cache3 = $this->Cache->get(cacheSession($template[2]), "templates");
						
						if($cache1 and $cache2 and $cache3) {
							print $buffer1;
							print $cache1;
							print $cache2;
							print $cache3;
						} else {
							ob_start();
							
							include $template[0];
							
							$buffer2 = ob_get_clean();

							ob_start();

							include $template[1];

							$buffer3 = ob_get_clean();

							ob_start();

							include $template[2];

							$buffer4 = ob_get_contents();
							
							@ob_end_clean();
						
							$this->Cache->save($buffer2, cacheSession($template[0]), "templates");
							$this->Cache->save($buffer3, cacheSession($template[1]), "templates");
							$this->Cache->save($buffer4, cacheSession($template[2]), "templates");	
							
							print $buffer1;
							print $buffer2;
							print $buffer3;
							print $buffer4;
						}
					} else {
						print $buffer1;
						
						include $template[0];
						include $template[1];
						include $template[2];
					}
				} elseif(count($template) === 4) {
					$buffer1 = ob_get_clean();
					
					if(_cacheStatus) { 								 
						$cache1 = $this->Cache->get(cacheSession($template[0]), "templates");
						$cache2 = $this->Cache->get(cacheSession($template[1]), "templates");
						$cache3 = $this->Cache->get(cacheSession($template[2]), "templates");
						$cache4 = $this->Cache->get(cacheSession($template[3]), "templates");
						
						if($cache1 and $cache2 and $cache3 and $cache4) {
							print $buffer1;

							print $cache1;
							print $cache2;
							print $cache3;
							print $cache4;
						} else {
							ob_start();
							
							include $template[0];
							
							$buffer2 = ob_get_clean();

							ob_start();

							include $template[1];

							$buffer3 = ob_get_clean();

							ob_start();

							include $template[2];

							$buffer4 = ob_get_clean();

							ob_start();

							include $template[3];

							$buffer5 = ob_get_contents();
							
							@ob_end_clean();
						
							$this->Cache->save($buffer2, cacheSession($template[0]), "templates");
							$this->Cache->save($buffer3, cacheSession($template[1]), "templates");
							$this->Cache->save($buffer4, cacheSession($template[2]), "templates");
							$this->Cache->save($buffer5, cacheSession($template[3]), "templates");
							
							print $buffer1;
							print $buffer2;
							print $buffer3;
							print $buffer4;
						}
					} else {
						print $buffer1;
						
						include $template[0];
						include $template[1];
						include $template[2];
						include $template[3];
					}
				}
			} else {
				if(!file_exists($template)) {
					die("Error 404: Theme Not Found: " . $template);
				}		
				
				$buffer1 = ob_get_clean();
									
				if(_cacheStatus) { 								 
					$cache = $this->Cache->get(cacheSession($template), "templates");
					
					if($cache) {
						print $buffer1;
						print $cache;

						return TRUE;
					} else {
						ob_start();
						
						include $template;
						
						$buffer2 = ob_get_contents();
						
						@ob_end_clean();
					
						$this->Cache->save($buffer2, cacheSession($template), "templates");	
						
						print $buffer1;
						print $buffer2;
					}
				} else {
					print $buffer1;
					
					include $template;
				}
			}
		} else { 
			$template = _www . _sh . _lib . _sh . _themes . _sh . $this->theme . _sh . $template . _PHP;
			
			if(!file_exists($template)) {
				die("Error 404: Theme Not Found: " . $template);									
			}
			
			#@ob_clean();
			
			include $template; 
			
			if(_cacheStatus) {
				$output = @ob_get_contents();

				@ob_end_clean();
				
				$this->Cache->save($output, cacheSession($template), "templates");
				
				print $output;
			} else {
				return TRUE;
			}	
		}						
	}
	
    /**
     * Set the current theme
     *
     * @return void
     */
	public function theme($theme) {
		$this->theme     = $theme;
		$this->themePath = _webURL . _sh . _www . _sh . _lib . _sh . _themes . _sh . $this->theme;
		
		if(!$this->isTheme()) {
			die("You need to create a valid theme");
		}
	}
	
    /**
     * 
     *
     * 
     */
	public function themeCSS($theme = _webTheme) {
		$file    = "www/lib/themes/". $theme . "/css/style.css";
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
		if(is_null($title)) {
			$this->title = _webName;
		} 
		
		$this->title = _webName ." - ". $title;
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
