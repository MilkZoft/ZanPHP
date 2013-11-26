<?php
if (!defined("ACCESS")) {
    die("Error: You don't have permission to access here...");
}

class ZP_Templates extends ZP_Load
{
	public $themePath;
	public $themeRoute;
	private $CSS = null;
	private $topCSS = array();
	private $bottomCSS = array();
	private $js = null;
	private $topJS = array();
	private $bottomJS = array();
	private $theme = null;
	private $title;
	private $meta;
	private $vars = array();
	private $ignoredSegments = array();

	public function CSS($CSS = null, $application = null, $print = false, $top = false)
	{
		if ($top) {
			$arrayCSS = &$this->topCSS;
		} else {
			$arrayCSS = &$this->bottomCSS;
		}		

		if (file_exists($CSS)) { 
			if ($print) {
				print '<link rel="stylesheet" href="'. _get("webURL") .'/'. $CSS .'" type="text/css" />';
			} else { 
				array_push($arrayCSS, $CSS);
			}
		} 

		if ($CSS === "bootstrap") {
			if ($print) {
				print '<link rel="stylesheet" href="'. path("vendors/css/frameworks/bootstrap/css/bootstrap.min.css", "zan") .'" type="text/css" />';
			} else {
				array_push($arrayCSS, CORE_PATH ."/vendors/css/frameworks/bootstrap/css/bootstrap.min.css");
			}

			$this->js("bootstrap");
		} elseif ($CSS === "prettyphoto") {
			if ($print) {
				print '<link rel="stylesheet" href="'. path("vendors/js/lightbox/prettyphoto/css/prettyPhoto.css", "zan") .'" type="text/css" />';
			} else {
				array_push($arrayCSS, CORE_PATH ."/vendors/js/lightbox/prettyphoto/css/prettyPhoto.css");
			}
		} elseif ($CSS === "codemirror") {
            if ($print) {
                print '<link rel="stylesheet" href="'. path("vendors/js/codemirror/codemirror.css", "zan") .'" type="text/css" />';
            } else {
				array_push($arrayCSS, CORE_PATH ."/vendors/js/codemirror/codemirror.css");
            }
		} elseif ($CSS === "filedrag") {
			if ($print) {
                print '<link rel="stylesheet" href="'. path("vendors/js/files/uploader/styles.css", "zan") .'" type="text/css" />';
            } else {
				array_push($arrayCSS, CORE_PATH ."/vendors/js/files/uploader/styles.css");
            }
		} 

		$file = is_null($application) ? "www/lib/css/$CSS.css" : "www/applications/$application/views/css/$CSS.css";
		
		if (file_exists($file)) {
			if ($print) {
				print '<link rel="stylesheet" href="'. _get("webURL") .'/'. $file .'" type="text/css" />';
			} else {
				array_push($arrayCSS, $file);
			}
		}
	}

	public function exists($template, $view = false)
	{
		if (!$view) {
			if (file_exists("www/lib/themes/$this->theme/$template.php")) {
				return true; 
			} 
		} elseif (file_exists("www/applications/$view/views/$template.php")) {
			return true; 
		} 
		
		return false;
	}

	public function getCSS()
	{
		return $this->getScript("css");
	}

	public function getJs()
	{
		return $this->getScript("js");
	}

	private function getScript($ext)
	{
		if ($ext === "css") {
			$scripts = array_merge($this->topCSS, $this->bottomCSS);
		} elseif ($ext === "js") {
			$scripts = array_merge($this->topJS, $this->bottomJS);
		} else {
			return null;
		}
		
		if (count($scripts) > 0) {
			if (_get("environment") < 3 or !_get("optimization")) {
				array_walk($scripts, create_function('&$val', '$val = "'. _get("webURL") .'/$val";'));
				if ($ext === "css") {
					return '<link rel="stylesheet" href="'. implode('" type="text/css" /><link rel="stylesheet" href="', $scripts) .'" type="text/css" />';
				} else {
					return '<script type="text/javascript" src="'. implode('"></script><script type="text/javascript" src="', $scripts) .'"></script>';
				}
			} else {
				$filename = CACHE_DIR .'/'. $ext .'/'. md5(implode(':', $scripts)) .'.'. $ext;

				if (!is_file($filename)) {
					$contents = "";

					foreach ($scripts as $file) {
						$contents .= @file_get_contents($file) . "\n";
					}

					$contents = compress($contents, $ext);
		        	
		        	file_put_contents($filename, $contents, LOCK_EX);
				}

				if ($ext === "css") {
					return '<link rel="stylesheet" href="'. _get("webURL") .'/'. $filename .'" type="text/css" />';
				} else {
					return '<script type="text/javascript" src="'. _get("webURL") .'/'. $filename .'"></script>';
				}
			}
		}
	}

	public function getThemes($theme)
	{
		$path = "www/lib/themes/";
		$dir = dir($path);
		$options = false;
		$i = 0;
		
		while ($element = $dir->read()) {
			$directory = $path . $element . SH;						
			
			if ($element !== ".." and $element !== "." and is_dir($directory) and $element !== "cpanel") {
				if ($element === $theme) {
					$options[$i]["value"] = $element;
					$options[$i]["option"] = $element;
					$options[$i]["selected"] = true;
				} else {
					$options[$i]["value"] = $element;
					$options[$i]["option"] = $element;
					$options[$i]["selected"] = false;
				}
								
				$i++;
			}
		}	

		$dir->close();
		return $options;
	}

	public function getTitle() 
	{
		return (is_null($this->title)) ? _get("webName") ." - ". _get("webSlogan") : encode($this->title);
	}

	public function getMeta()
	{
		return (is_null($this->meta) ? "" : ltrim($this->meta));
	}

	public function isTheme()
	{
		$this->path = (!is_null($this->theme)) ? "www/lib/themes/$this->theme" : false;	
		$this->directory = @dir($this->path);
		return ($this->directory) ? true : false;
	}

	public function js($js, $application = null, $getJs = false, $top = false)
	{
		if ($top) {
			$arrayJS = &$this->topJS;
		} else {
			$arrayJS = &$this->bottomJS;
		}
		
		if ($js === "prettyphoto") {
			$this->CSS("prettyphoto");

			if ($getJs) {
				return '<script type="text/javascript" src="'. path("vendors/js/lightbox/prettyphoto/js/jquery.prettyphoto.js", "zan") .'"></script>';
			} else {
				array_push($arrayJS, CORE_PATH .'/vendors/js/lightbox/prettyphoto/js/jquery.prettyphoto.js');
			}
		} elseif ($js === "jquery") {
			if ($getJs) {
				return '<script type="text/javascript" src="'. path("vendors/js/jquery/jquery.js", "zan") .'"></script>';
			} else {
				array_push($arrayJS, CORE_PATH .'/vendors/js/jquery/jquery.js');
			}
		} elseif (preg_match('/^jquery\.(.+)\.js$/i', $js, $matches)) {
			$plugin_name = trim($matches[1]);
			
			if (file_exists(CORE_PATH ."/vendors/js/jquery/$plugin_name/")) {
				$this->css(CORE_PATH ."/vendors/js/jquery/$plugin_name/$plugin_name.css");

				if ($getJs) {
					return '<script type="text/javascript" src="'. path("vendors/js/jquery/$plugin_name/$js", "zan") .'"></script>';
				} else {
					array_push($arrayJS, CORE_PATH ."/vendors/js/jquery/$plugin_name/$js");
				}
			} elseif ($getJs) {
				return '<script type="text/javascript" src="'. path("vendors/js/jquery/$js", "zan") .'"></script>';
			} else {
				array_push($arrayJS, CORE_PATH ."/vendors/js/jquery/$js");
			}
		} elseif ($js === "filedrag") {
			$this->CSS("filedrag");

			if ($getJs) {
				return '<script type="text/javascript" src="'. path("vendors/js/files/uploader/filedrag.js", "zan") .'"></script>';
			} else {
				array_push($arrayJS, CORE_PATH .'/vendors/js/files/uploader/filedrag.js');
			}
		} elseif ($js === "ckeditor") {			
			if ($getJs) {
				$js  = '<script type="text/javascript" src="'. path("vendors/js/editors/ckeditor/ckeditor.js", "zan") .'"></script>';

				if ($application === "full") {
					$js .= "<script type=\"text/javascript\">
								CKEDITOR.config.extraPlugins = 'codemirror,insertpre,doksoft_image,doksoft_preview,doksoft_resize';
								CKEDITOR.config.insertpre_class = 'prettyprint';
								CKEDITOR.config.insertpre_style = 'background-color:#F8F8F8;border:1px solid #DDD;padding:10px;';
								CKEDITOR.config.filebrowserImageUploadUrl = '". path("vendors/js/editors/ckeditor/plugins/doksoft_uploader/uploader.php?type=Images", "zan") ."';
								CKEDITOR.config.filebrowserImageThumbsUploadUrl = '". path("vendors/js/editors/ckeditor/plugins/doksoft_uploader/uploader.php?type=Images&makeThumb=true", "zan") ."';
								CKEDITOR.config.filebrowserImageResizeUploadUrl = '". path("vendors/js/editors/ckeditor/plugins/doksoft_uploader/uploader.php?type=Images&resize=true", "zan") ."';

								CKEDITOR.replace('editor', {
									toolbar: [
										{name:'group1', items:['Bold','Italic','Underline','StrikeThrough','PasteFromWord']},
										{name:'group2', items:['Format']},
										{name:'group3', items:['Outdent','Indent','NumberedList','BulletedList','Blockquote','PageBreak']},
						   				{name:'group4', items:['Image','Link','Unlink','InsertPre','Source','doksoft_image', 'doksoft_preview', 'doksoft_resize']}
									]									
								});
							</script>";
				} else {
					$js .= "<script type=\"text/javascript\">
								CKEDITOR.config.insertpre_style = 'background-color:#F8F8F8;border:1px solid #DDD;padding:10px;';
								CKEDITOR.config.insertpre_class = 'prettyprint';

								CKEDITOR.replace('editor', {
									toolbar: [
										{name:'group1', items:['Bold','Italic','Underline','StrikeThrough','PasteFromWord']},
										{name:'group2', items:['Outdent','Indent','NumberedList','BulletedList','Blockquote']},
						   				{name:'group3', items:['Image','Link','Unlink','InsertPre']}  
									]
									
								});
							</script>";
				}

				return $js;
			} else {
				array_push($arrayJS, CORE_PATH .'/vendors/js/editors/ckeditor/ckeditor.js');
			}
		} elseif ($js === "lesscss") {
			if ($getJs) {
				return '<script type="text/javascript" src="'. path("vendors/js/less/less.js", "zan") .'"></script>';
			} else {
				array_push($arrayJS, CORE_PATH .'/vendors/js/less/less.js');
			}
		} elseif ($js === "angular") {
			if ($getJs) {
				return '<script type="text/javascript" src="'. path("vendors/js/angular/angular-1.0.1.min.js", "zan") .'"></script>';
			} else {
				array_push($arrayJS, CORE_PATH .'/vendors/js/angular/angular-1.0.1.min.js');
			}
		} elseif ($js === "bootstrap") {
			if ($getJs) {
				return '<script type="text/javascript" src="'. path("vendors/css/frameworks/bootstrap/js/bootstrap.min.js", "zan") .'"></script>';
			} else {
				array_push($arrayJS, CORE_PATH .'/vendors/css/frameworks/bootstrap/js/bootstrap.min.js');
			}
		} elseif ($js === "codemirror") {
			if ($getJs) {
				$js = '<script type="text/javascript" src="'. path("vendors/js/codemirror/codemirror.js", "zan") .'"></script>';
				if (is_null($application)) {
					$js .= '<script type="text/javascript" src="'. path("vendors/js/codemirror/util/loadmode.js", "zan") .'"></script>';
				} elseif ($application === "php") {
					$js .= '<script type="text/javascript" src="'. path("vendors/js/codemirror/mode/htmlmixed.js", "zan") .'"></script>';
					$js .= '<script type="text/javascript" src="'. path("vendors/js/codemirror/mode/xml.js", "zan") .'"></script>';
					$js .= '<script type="text/javascript" src="'. path("vendors/js/codemirror/mode/javascript.js", "zan") .'"></script>';
					$js .= '<script type="text/javascript" src="'. path("vendors/js/codemirror/mode/css.js", "zan") .'"></script>';
					$js .= '<script type="text/javascript" src="'. path("vendors/js/codemirror/mode/clike.js", "zan") .'"></script>';
					$js .= '<script type="text/javascript" src="'. path("vendors/js/codemirror/mode/php.js", "zan") .'"></script>';
				}
				return $js;
			} else {
				array_push($arrayJS, CORE_PATH .'/vendors/js/codemirror/codemirror.js');
				if (is_null($application)) {
					array_push($arrayJS, CORE_PATH .'/vendors/js/codemirror/util/loadmode.js');
				} elseif ($application === "php") {
					array_push($arrayJS, CORE_PATH .'/vendors/js/codemirror/mode/htmlmixed.js');
					array_push($arrayJS, CORE_PATH .'/vendors/js/codemirror/mode/xml.js');
					array_push($arrayJS, CORE_PATH .'/vendors/js/codemirror/mode/javascript.js');
					array_push($arrayJS, CORE_PATH .'/vendors/js/codemirror/mode/css.js');
					array_push($arrayJS, CORE_PATH .'/vendors/js/codemirror/mode/clike.js');
					array_push($arrayJS, CORE_PATH .'/vendors/js/codemirror/mode/php.js');
				}
			}
		} elseif (file_exists($js)) {
			if ($getJs) {
				return '<script type="text/javascript" src="'. _get("webURL") .'/'. $js .'"></script>';
			} else {
				array_push($arrayJS, $js);
			}
		} elseif (file_exists(path($js, "zan"))) {
			if ($getJs) {
				return '<script type="text/javascript" src="'. path($js, "zan") .'"></script>';
			} else {
				array_push($arrayJS, CORE_PATH .'/'. $js);
			}
		} elseif (file_exists("www/applications/$application/views/js/$js")) {
			if ($getJs) {
				$filename = "www/applications/$application/views/js/$js";
				return '<script type="text/javascript" src="'. _get("webURL") .'/'. $filename .'"></script>';
			} else {
				array_push($arrayJS, "www/applications/$application/views/js/$js");
			}
		} elseif (file_exists("www/applications/$application/views/js/$js.js")) {
			if ($getJs) {
				$filename = "www/applications/$application/views/js/$js.js";
				return '<script type="text/javascript" src="'. _get("webURL") .'/'. $filename .'"></script>';
			} else {
				array_push($arrayJS, "www/applications/$application/views/js/$js.js");
			}
		} else {
			return false;
		}
	}

	public function load($template, $direct = false)
	{
		if (is_array($this->vars)) {
			$key = array_keys($this->vars);
			$size = sizeof($key);			

			for ($i = 0; $i < $size; $i++) {
				$$key[$i] = $this->vars[$key[$i]];
			}
		}
		
		if ($direct) { 
			if (is_array($template)) {
				$count = count($template);

				if ($count === 1) {
					include $template[0];
				} elseif ($count === 2) {
					include $template[0];
					include $template[1];
				} elseif ($count === 3) {
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
				if (!file_exists($template)) {
					getException("Error 404: Theme Not Found: ". $template);
				}		

				include $template;
			}
		} else { 
			$_name = $template;		
			$template = "www/lib/themes/$this->theme/$_name.php"; 
			$minTemplate = "www/lib/themes/$this->theme/min/$_name.php";

			if (_get("environment") > 2 and file_exists($minTemplate)) {
				$template = $minTemplate;
			}

			if (!file_exists($template)) {
				getException("Error 404: Theme Not Found: ". $template);									
			}

			include $template;	
		}						
	}

	public function theme($theme = null)
	{
		$this->theme = (is_null($theme)) ? _get("webTheme") : $theme;
		$this->themeRoute = "www/lib/themes/$this->theme";
		$this->themePath = _get("webURL") . "/$this->themeRoute";
		
		if (!$this->isTheme()) {
			die("You need to create a valid theme");
		}
	}

	public function themeCSS($theme = null, $min = true)
	{	
		$style = ($min) ? "style.min.css" : "style.css";
		return '<link rel="stylesheet" href="'. $this->themePath .'/css/'. $style .'" type="text/css">';					
	}

	public function title($title = null) {
		$this->helper("string");

		if (!is_null($title)) {
			$title = stripslashes($title) ." - ". _get("webName");
		}

		$this->title = is_null($title) ? _get("webName") ." - ". _get("webSlogan") : $title;
        $this->meta("title", $this->title);
	}

    public function meta($tag, $value)
    {
        switch ($tag) {
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

                if (strlen($value) > 250) {
                    $abstract = stripslashes(substr($value, 0, strrpos(substr($value, 0, 100), " ")));
                    $value = stripslashes(substr($value, 0, strrpos(substr($value, 0, 250), " ")));
                } else {
                	$abstract = $value;
                }
                
                $this->meta .= "<meta name=\"abstract\" content=\"". $abstract ."\" />";
            default:
                $this->meta .= "<meta name=\"$tag\" content=\"$value\" />";
            	break;   
        }
    }

	public function vars($vars)
	{
		$this->vars = $vars;
	}
}