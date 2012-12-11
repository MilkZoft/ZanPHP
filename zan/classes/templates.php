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

	private $topCSS    = array();
	private $bottomCSS = array();
	
	/**
	 * 
	 * 
	 * 
	 */
	private $js = NULL;

	private $topJS 	  = array();
	private $bottomJS = array();
	
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
	 * Contains the route of the theme
	 * 
	 * @var public $themeRoute
	 */
	public $themeRoute;
	
	/**
	 * Contains the title for the header template
	 * 
	 * @var private $title = _get("webNam"]e
	 */
	private $title;
        
        /**
	 * Contains the meta tags for the header template
	 * 
	 * @var private $meta = _get("tagsMeta"]
	 */
	private $meta;
	
	/**
	 * Contains the array of vars
	 * 
	 * @var public $vars = array()
	 */
	private $vars = array();
	
	/**
	 * Contains the ignored segments
	 * 
	 * @var private $ignoredSegments = array()
	 */
	private $ignoredSegments = array();
	
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
	public function CSS($CSS = NULL, $application = NULL, $print = FALSE, $top = FALSE) {
		if($top) {
			$arrayCSS = &$this->topCSS;
		} else {
			$arrayCSS = &$this->bottomCSS;
		}

		if(file_exists($CSS)) { 
			if($print) {
				print '<link rel="stylesheet" href="'. _get("webURL") .'/'. $CSS .'" type="text/css" />';
			} else { 
				array_push($arrayCSS, $CSS);
			}
		} 

		if($CSS === "bootstrap") {
			if($print) {
				print '<link rel="stylesheet" href="'. _get("webURL") .'/zan/vendors/css/frameworks/bootstrap/css/bootstrap.min.css" type="text/css" />';
			} else {
				array_push($arrayCSS, _corePath ."/vendors/css/frameworks/bootstrap/css/bootstrap.min.css");
			}

			$this->js("bootstrap");
		} elseif($CSS === "prettyphoto") {
			if($print) {
				print '<link rel="stylesheet" href="'. path("vendors/js/lightbox/prettyphoto/css/prettyPhoto.css", "zan") .'" type="text/css" />';
			} else {
				array_push($arrayCSS, _corePath ."/vendors/js/lightbox/prettyphoto/css/prettyPhoto.css");
			}
		} elseif($CSS === "codemirror") {
            if ($print) {
                print '<link rel="stylesheet" href="'. path("vendors/js/codemirror/codemirror.css", "zan") .'" type="text/css" />';
            } else {
				array_push($arrayCSS, _corePath ."/vendors/js/codemirror/codemirror.css");
            }
		} elseif($CSS === "redactorjs") {
            if ($print) {
                print '<link rel="stylesheet" href="'. path("vendors/js/editors/redactorjs/css/redactor.css", "zan") .'" type="text/css" />';
            } else {
				array_push($arrayCSS, _corePath ."/vendors/js/editors/redactorjs/css/redactor.css");
            }			
		} elseif($CSS === "markitup") {
            if ($print) {
                print '<link rel="stylesheet" href="'. path("vendors/js/editors/markitup/skins/markitup/style.min.css", "zan") .'" type="text/css" />';
                print '<link rel="stylesheet" href="'. path("vendors/js/editors/markitup/sets/html/style.css", "zan") .'" type="text/css" />';
            } else {
				array_push($arrayCSS, _corePath ."/vendors/js/editors/markitup/skins/markitup/style.min.css", _corePath ."/vendors/js/editors/markitup/sets/html/style.css");
            }
		}

		$file = is_null($application) ? "www/lib/css/$CSS.css" : "www/applications/$application/views/css/$CSS.css";
		
		if(file_exists($file)) {
			if($print) {
				print '<link rel="stylesheet" href="'. _get("webURL") .'/'. $file .'" type="text/css" />';
			} else {
				array_push($arrayCSS, $file);
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
		return $this->getScript("css");
	}
	
    /**
     * Get the Js
     *
     * @return void
     */
	public function getJs() {
		return $this->getScript("js");
	}

	/**
	 * Get the Js or Css files
	 *
	 * @return string/void
	 */
	private function getScript($ext) {
		if($ext === "css") {
			$scripts = array_merge($this->topCSS, $this->bottomCSS);
		} elseif($ext === "js") {
			$scripts = array_merge($this->topJS, $this->bottomJS);
		} else {
			return NULL;
		}
		
		if(count($scripts) > 0) {
			if(_get("environment") < 3) {
				array_walk($scripts, create_function('&$val', '$val = "'. _get("webURL") .'/$val";'));
				if($ext === "css") {
					return '<link rel="stylesheet" href="'. implode('" type="text/css" /><link rel="stylesheet" href="', $scripts) .'" type="text/css" />';
				} else {
					return '<script type="text/javascript" src="'. implode('"></script><script type="text/javascript" src="', $scripts) .'"></script>';
				}
			} else {
				$filename = _cacheDir .'/'. $ext .'/'. md5(implode(':', $scripts)) .'.'. $ext;

				if(!is_file($filename)) {
					$contents = "";

					foreach ($scripts as $file) $contents .= @file_get_contents($file) . "\n";

					$contents = compress($contents, $ext);

		        	file_put_contents($filename, $contents, LOCK_EX);
				}

				if($ext === "css") {
					return '<link rel="stylesheet" href="'. _get("webURL") .'/'. $filename .'" type="text/css" />';
				} else {
					return '<script type="text/javascript" src="'. _get("webURL") .'/'. $filename .'"></script>';
				}
			}
		}
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
		return (is_null($this->title)) ? _get("webName") ." - ". _get("webSlogan") : encode($this->title);
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
	public function js($js, $application = NULL, $getJs = FALSE, $top = FALSE) {
		if($top) {
			$arrayJS = &$this->topJS;
		} else {
			$arrayJS = &$this->bottomJS;
		}

		if($js == "prettyphoto") {
			$this->CSS("prettyphoto");

			if($getJs) {
				return '<script type="text/javascript" src="'. path("vendors/js/lightbox/prettyphoto/js/jquery.prettyphoto.js", "zan") .'"></script>';
			} else {
				array_push($arrayJS, _corePath .'/vendors/js/lightbox/prettyphoto/js/jquery.prettyphoto.js');
			}

		} elseif($js === "jquery") {
			if($getJs) {
				return '<script type="text/javascript" src="'. path("vendors/js/jquery/jquery.js", "zan") .'"></script>';
			} else {
				array_push($arrayJS, _corePath .'/vendors/js/jquery/jquery.js');
			}
		} elseif (preg_match('/^jquery\.(.+)\.js$/i', $js, $matches)){ # Plugin jQuery
			$plugin_name = trim($matches[1]);
			
			if(file_exists(_corePath . "/vendors/js/jquery/$plugin_name/")) {
				$this->css(_corePath . "/vendors/js/jquery/$plugin_name/$plugin_name.css");

				if($getJs) {
					return '<script type="text/javascript" src="'. path("vendors/js/jquery/$plugin_name/$js", "zan") .'"></script>';
				} else {
					array_push($arrayJS, _corePath ."/vendors/js/jquery/$plugin_name/$js");
				}
			} elseif($getJs) {
				return '<script type="text/javascript" src="'. path("vendors/js/jquery/$js", "zan") .'"></script>';
			} else {
				array_push($arrayJS, _corePath ."/vendors/js/jquery/$js");
			}
        } elseif($js === "redactorjs") {
			$this->CSS("redactorjs");

			if($getJs) {
				$js = '<script type="text/javascript" src="'. path("vendors/js/editors/redactorjs/redactor.js", "zan") .'"></script>';
				$js .= '<script type="text/javascript" src="'. path("vendors/js/editors/redactorjs/scripts/set.js", "zan") .'"></script>';
				if(_get("webLang") !== "en") {
					$js .= '<script type="text/javascript" src="'. path("vendors/js/editors/redactorjs/langs/". _get("webLang") .".js", "zan") .'"></script>';
				}
				return $js;
			} else {
				array_push($arrayJS, _corePath .'/vendors/js/editors/redactorjs/redactor.js', _corePath .'/vendors/js/editors/redactorjs/scripts/set.js');
				if(_get("webLang") !== "en") array_push($arrayJS, _corePath .'/vendors/js/editors/redactorjs/langs/'. _get("webLang") .'.js');
			}
		} elseif($js === "markitup") {
			$this->CSS("markitup");

			if($getJs) {
				$js = '<script type="text/javascript" src="'. path("vendors/js/editors/markitup/jquery.markitup.js", "zan") .'"></script>';
				$js .= '<script type="text/javascript" src="'. path("vendors/js/editors/markitup/sets/html/set.js", "zan") .'"></script>';
				return $js;
			} else {
				array_push($arrayJS, _corePath .'/vendors/js/editors/markitup/jquery.markitup.js', _corePath .'/vendors/js/editors/markitup/sets/html/set.js');
			}
		} elseif($js === "tinymce") {
			if($getJs) {
				return '<script type="text/javascript" src="'. path("vendors/js/editors/tinymce/tiny_mce.js", "zan") .'"></script>';
			} else {
				array_push($arrayJS, _corePath .'/vendors/js/editors/tinymce/tiny_mce.js');
			}
		} elseif($js === "switch-editor") {
			if($getJs) {
				return '<script type="text/javascript" src="'. path("vendors/js/editors/switch.js", "zan") .'"></script>';
			} else {
				array_push($arrayJS, _corePath .'/vendors/js/editors/switch.js');
			}
		} elseif($js === "lesscss") {
			if($getJs) {
				return '<script type="text/javascript" src="'. path("vendors/js/less/less.js", "zan") .'"></script>';
			} else {
				array_push($arrayJS, _corePath .'/vendors/js/less/less.js');
			}
		} elseif($js === "angular") {
			if($getJs) {
				return '<script type="text/javascript" src="'. path("vendors/js/angular/angular-1.0.1.min.js", "zan") .'"></script>';
			} else {
				array_push($arrayJS, _corePath .'/vendors/js/angular/angular-1.0.1.min.js');
			}
		} elseif($js === "bootstrap") {
			if($getJs) {
				return '<script type="text/javascript" src="'. path("vendors/css/frameworks/bootstrap/js/bootstrap.min.js", "zan") .'"></script>';
			} else {
				array_push($arrayJS, _corePath .'/vendors/css/frameworks/bootstrap/js/bootstrap.min.js');
			}
		} elseif($js === "codemirror") {
			if($getJs) {
				$js = '<script type="text/javascript" src="'. path("vendors/js/codemirror/codemirror.js", "zan") .'"></script>';
				$js .= '<script type="text/javascript" src="'. path("vendors/js/codemirror/util/loadmode.js", "zan") .'"></script>';
				return $js;
			} else {
				array_push($arrayJS, _corePath .'/vendors/js/codemirror/codemirror.js', _corePath .'/vendors/js/codemirror/util/loadmode.js');
			}
		} elseif(file_exists($js)) {
			if($getJs) {
				return '<script type="text/javascript" src="'. _get("webURL") .'/'. $js .'"></script>';
			} else {
				array_push($arrayJS, $js);
			}
		} elseif(file_exists(path($js, "zan"))) {
			if($getJs) {
				return '<script type="text/javascript" src="'. path($js, "zan") .'"></script>';
			} else {
				array_push($arrayJS, _corePath .'/'. $js);
			}
		} elseif(file_exists("www/applications/$application/views/js/$js")) {
			if($getJs) {
				$filename = "www/applications/$application/views/js/$js";
				return '<script type="text/javascript" src="'. _get("webURL") .'/'. $filename .'"></script>';
			} else {
				array_push($arrayJS, "www/applications/$application/views/js/$js");
			}
		} elseif(file_exists("www/applications/$application/views/js/$js.js")) {
			if($getJs) {
				$filename = "www/applications/$application/views/js/$js.js";
				return '<script type="text/javascript" src="'. _get("webURL") .'/'. $filename .'"></script>';
			} else {
				array_push($arrayJS, "www/applications/$application/views/js/$js.js");
			}
		} else {
			return FALSE;
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
					getException("Error 404: Theme Not Found: ". $template);
				}		
				
				include $template;
			}
		} else { 
			$_name = $template;		

			$template    = "www/lib/themes/$this->theme/$_name.php"; 
			$minTemplate = "www/lib/themes/$this->theme/min/$_name.php";

			if(_get("environment") > 2 and file_exists($minTemplate)) {
				$template = $minTemplate;
			}

			if(!file_exists($template)) {
				getException("Error 404: Theme Not Found: ". $template);									
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
		$this->theme = (is_null($theme)) ? _get("webTheme") : $theme;

		$this->themeRoute = "www/lib/themes/$this->theme";
		
		$this->themePath = _get("webURL") . "/$this->themeRoute";
		
		if(!$this->isTheme()) {
			die("You need to create a valid theme");
		}
	}
	
    /**
     * 
     *
     * 
     */
	public function themeCSS($theme = NULL, $min = TRUE) {
		$style = ($min) ? "style.min.css" : "style.css";
		
		return '<link rel="stylesheet" href="'. $this->themePath .'/css/'. $style .'" type="text/css">';					
	}
	
    /**
     * Set header title
     *
     * @return void
     */
	public function title($title = NULL) {
		$this->helper("string");

		if(!is_null($title)) {
			$title = stripslashes($title) ." - ". _get("webName");
		}

		$this->title = is_null($title) ? _get("webName") ." - ". _get("webSlogan") : $title;
        
        $this->meta("title", $this->title);
	}
        
    /**
     * Set header meta tag
     *
     * @return void
     */           
    public function meta($tag, $value) {
        switch($tag) {
            case "title":
                $value = stripslashes($value);

                $this->meta .= "<meta name=\"$tag\" content=\"$value\" />";
            break;
            
            case "language":
                $this->meta .= "<meta http-equiv=\"content-language\" content=\"$value\" />";
            break;
            
            case "description":
                $value = preg_replace("/\r\n+/", " ", strip_tags($value));
                $value = str_replace('"', "", $value);

                if(strlen($value) > 250) {
                    $abstract = stripslashes(substr($value, 0, strrpos(substr($value, 0, 100), " ")));
                    $value    = stripslashes(substr($value, 0, strrpos(substr($value, 0, 250), " ")));
                } else {
                	$abstract = $value;
                }
                
                $this->meta .= "<meta name=\"abstract\" content=\"". $abstract ."\" />";
            
            default:
                $this->meta .= "<meta name=\"$tag\" content=\"$value\" />";
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