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
	 * Contains the meta tags for the header template
	 * 
	 * @var private $meta = get("tagsMeta"]
	 */
	private $meta;
	
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
					print '<link rel="stylesheet" href="'. get("webURL") .'/zan/vendors/css/frameworks/bootstrap/bootstrap.min.css" type="text/css" />' . "\n";
				} else {
					$this->CSS = '<link rel="stylesheet" href="'. get("webURL") .'/zan/vendors/css/frameworks/bootstrap/bootstrap.min.css" type="text/css" />' . "\n";
				}
			} else {
				if($print) {
					print '<link rel="stylesheet" href="'. get("webURL") .'/zan/vendors/css/frameworks/bootstrap/bootstrap.min.css" type="text/css" />' . "\n";
				} else {
					$this->CSS .= '<link rel="stylesheet" href="'. get("webURL") .'/zan/vendors/css/frameworks/bootstrap/bootstrap.min.css" type="text/css" />' . "\n";
				}	
			}
		} elseif($CSS === "prettyphoto") {
			if(is_null($this->CSS)) {
				if($print) {
					print '<link rel="stylesheet" href="'. path("vendors/js/lightbox/prettyphoto/css/prettyPhoto.css", "zan") .'" type="text/css" />' . "\n";
				} else {
					$this->CSS = '<link rel="stylesheet" href="'. path("vendors/js/lightbox/prettyphoto/css/prettyPhoto.css", "zan") .'" type="text/css" />' . "\n";
				}
			} else {
				if($print) {
					print '<link rel="stylesheet" href="'. path("vendors/js/lightbox/prettyphoto/css/prettyPhoto.css", "zan") .'" type="text/css" />' . "\n";
				} else {
					$this->CSS .= '<link rel="stylesheet" href="'. path("vendors/js/lightbox/prettyphoto/css/prettyPhoto.css", "zan") .'" type="text/css" />' . "\n";
				}	
			}
		} elseif($CSS === "codemirror") {
            if ($print) {
                print '<link rel="stylesheet" href="'. path("vendors/js/codemirror/codemirror.css", "zan") .'" type="text/css" />' . "\n";
            } else {
                if (is_null($this->CSS)) {
                    $this->CSS = '<link rel="stylesheet" href="'. path("vendors/js/codemirror/codemirror.css", "zan") .'" type="text/css" />' . "\n";
                } else {
                    $this->CSS .= '<link rel="stylesheet" href="'. path("vendors/js/codemirror/codemirror.css", "zan") .'" type="text/css" />' . "\n";
                }
            }
		} elseif($CSS === "redactorjs") {
            if ($print) {
                print '<link rel="stylesheet" href="'. path("vendors/js/editors/redactorjs/css/redactor.css", "zan") .'" type="text/css" />' . "\n";
            } else {
                if (is_null($this->CSS)) {
                    $this->CSS = '<link rel="stylesheet" href="'. path("vendors/js/editors/redactorjs/css/redactor.css", "zan") .'" type="text/css" />' . "\n";
                } else {
                    $this->CSS .= '<link rel="stylesheet" href="'. path("vendors/js/editors/redactorjs/css/redactor.css", "zan") .'" type="text/css" />' . "\n";
                }
            }			
		}

		$file = is_null($application) ? "www/lib/css/$CSS.css" : "www/applications/$application/views/css/$CSS.css";
				
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
		} elseif(file_exists("www/applications/$view/views/$template.php")) {
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
		return (is_null($this->title)) ? get("webName") ." - ". get("webSlogan") : encode($this->title);
	}
        
     /**
     * Get the meta tags
     *
     * @return void
     */
	public function getMeta() {
		return (is_null($this->meta) ? "" : ltrim($this->meta));
	}
        
    /**
     * Verify if a theme exists
     *
     * @return boolean value
     */
	public function isTheme() {
		$this->path = (!is_null($this->theme)) ? "www/lib/themes/$this->theme" : FALSE;
		
		$this->directory = @dir($this->path);
		
		return ($this->directory) ? TRUE : FALSE;
	}
	
    /**
     * 
     *
     * 
     */
	public function js($js, $application = NULL, $getJs = FALSE) {
		if($js == "prettyphoto") {
			$js = '<script type="text/javascript" src="'. path("vendors/js/lightbox/prettyphoto/js/jquery.prettyphoto.js", "zan") .'"></script>';

			$this->CSS("prettyphoto");
		} elseif($js === "jquery") {
			$js = '<script type="text/javascript" src="'. path("vendors/js/jquery/jquery.js", "zan") .'"></script>';
		} elseif (preg_match("/^jquery\\..+\\.js$/i", $js)){ # Plugin jQuery
			$js = '<script type="text/javascript" src="'. path("vendors/js/jquery/$js", "zan") .'"></script>';
                } elseif($js === "redactorjs") {
			$js = '<script type="text/javascript" src="'. path("vendors/js/editors/redactorjs/redactor.min.js", "zan") .'"></script>';

			$this->CSS("redactorjs");
		} elseif($js === "lesscss") {
			$js = '<script type="text/javascript" src="'. path("vendors/js/less/less.js", "zan") .'"></script>';
		} elseif($js === "angular") {
			$js = '<script type="text/javascript" src="'. path("vendors/js/angular/angular-1.0.1.min.js", "zan") .'"></script>';
		} elseif($js === "codemirror") {
			$js = '<script type="text/javascript" src="'. path("vendors/js/codemirror/codemirror.js", "zan") .'"></script>';
			$js .= '<script type="text/javascript" src="'. path("vendors/js/codemirror/util/loadmode.js", "zan") .'"></script>';
                        $this->CSS("codemirror", NULL, TRUE);
		} elseif(file_exists($js)) {
			$js = '<script type="text/javascript" src="'. path($js, TRUE) .'"></script>';
		} elseif(file_exists(path($js, "zan"))) {
			$js = '<script type="text/javascript" src="'. path($js, "zan") .'"></script>';
		} elseif(file_exists("www/applications/$application/views/js/$js")) {
			$js = '<script type="text/javascript" src="'. get("webURL") .'/www/applications/' . $application . '/views/js/' . $js . '"></script>';
		} elseif(file_exists("www/applications/$application/views/js/$js.js")) {
			$js = '<script type="text/javascript" src="'. get("webURL") .'/www/applications/' . $application . '/views/js/' . $js . '.js"></script>';
		} else {
			return FALSE;
		}

		if($getJs) {
			return $js;
		} else {
			$this->js .= $js;
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
				$count = count($template);

				if($count === 1) {
					include $template[0];
				} elseif($count === 2) {
					include $template[0];
					include $template[1];
				} elseif($count === 3) {
					include $template[0];
					include $template[1];
					include $template[2];
				} else {
					include $template[0];
					include $template[1];
					include $template[2];
					include $template[3];
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
		$this->helper("browser");

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
		$this->title = is_null($title) ? get("webName") ." - ". get("webSlogan") : stripslashes($title) ." - ". get("webName");
        
        $this->setMeta("title", $this->title);
	}
        
    /**
     * Set header meta tag
     *
     * @return void
     */           
    public function setMeta($tag, $value) {
        switch ($tag) {
            case "title":
                $value = encode(stripslashes($value));

                $this->meta .= "\t<meta name=\"$tag\" content=\"$value\" />\n";
            break;
            
            case "language":
                $this->meta .= "\t<meta http-equiv=\"content-language\" content=\"$value\" />\n";
            break;
            
            case "description":
                $value = preg_replace("/\r\n+/", " ", strip_tags($value));
                
                if(strlen($value) > 250) {
                    $abstract = stripslashes(substr($value, 0, strrpos(substr($value, 0, 100), " ")));
                    $value    = stripslashes(substr($value, 0, strrpos(substr($value, 0, 250), " ")));
                } else {
                	$abstract = $value;
                }
                
                $this->meta .= "\t<meta name=\"abstract\" content=\"" . $abstract . "\" />\n";
            
            default:
                $this->meta .= "\t<meta name=\"$tag\" content=\"$value\" />\n";
            break;   
        }
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