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
	 * Contains the array of vars
	 * 
	 * @var public $vars = array()
	 */
	private $vars = array();
	
	/**
	 * Contains the title for the header template
	 * 
	 * @var private $title = _webName
	 */
	private $title;
	
	/**
	 * Contains the CSS style from an specific application
	 * 
	 * @var private $CSS = NULL
	 */
	private $CSS = NULL;
	
	private $js = NULL;
	
    /**
     * Load helpers: array, browser, debugging, forms, html and web
     *
     * @return void
     */
	public function __construct() {
		$helpers = array("array", "browser", "debugging", "forms", "html", "web");
		
		$this->helper($helpers);
	}	
	
    /**
     * Set the current theme
     *
     * @return void
     */
	public function theme($theme) {
		$this->theme     = $theme;
		$this->themePath = _webURL . _sh . _lib . _sh . _themes . _sh . $this->theme;
		
		if($this->isTheme() === FALSE) {
			die("You need to create a valid theme");
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
	
    /**
     * Get the CSS style
     *
     * @return void
     */
	public function getCSS() {
		return $this->CSS;
	}

    /**
     * Set the CSS style
     *
     * @return void
     */	
	public function CSS($CSS = NULL, $application = NULL, $print = FALSE) {
		if(is_null($application)) {
			$file = _lib . _sh . _CSS . _sh . $CSS . _dot . _CSS;
		} else {
			$file = _applications . _sh . $application . _sh . _views . _sh . _CSS . _sh . $CSS . _dot . _CSS;
		}
		
		if(is_null($this->CSS)) {
			if($print) {
				print '<link rel="stylesheet" href="' . _webURL . _sh . _lib . _sh . _CSS . '/default.css" type="text/css">';
			} else {
				$this->CSS = '<link rel="stylesheet" href="' . _webURL . _sh . _lib . _sh . _CSS . '/default.css" type="text/css">';
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
	
	public function themeCSS($theme = _webTheme) {
		$file    = "lib/themes/". $theme . "/css/style.css";
		$browser = browser();
		
		if($browser === "Internet Explorer") {
			$style = "lib/themes/". $theme . "/css/ie.style.css";

			if(file_exists($style)) {
				return '<link rel="stylesheet" href="'. $this->themePath .'/css/ie.style.css" type="text/css">';
			} else {
				return '<link rel="stylesheet" href="'. $this->themePath .'/css/style.css" type="text/css">';	
			}		
		} else {			
			return '<link rel="stylesheet" href="'. $this->themePath .'/css/style.css" type="text/css">';			
		}
	}
	
	public function getJs() {
		return $this->js;
	}
	
	public function js($js, $application = NULL, $extra = NULL, $getJS = FALSE) {
		$HTML = NULL;
		
		if(file_exists($js)) {		
			return '<script type="text/javascript" src="'. _webURL . _sh . $js .'"></script>';
		} else {
			if(isset($application)) {
				$file = _applications . _sh . $application . _sh . _views . _sh . _js . _sh . $js . _dot . _js;
				
				if(file_exists($file)) {
					if($getJS) {
						return '<script type="text/javascript" src="'. _webURL . _sh . $file .'"></script>';
					} else {						
						$this->js .= '<script type="text/javascript" src="'. _webURL . _sh . $file .'"></script>';
					}
				}
			} else {
				if($js === "jquery") {
					$HTML  = '	<script type="text/javascript" src="'. _webURL .'/lib/scripts/js/jquery.js"></script>';
				} elseif($js === "nivo-slider") {
					$HTML .= '	<script type="text/javascript" src="'. _webURL .'/lib/scripts/js/nivo-slider/nivo-slider.js"></script>
								<link rel="stylesheet" href="'. _webURL .'/lib/scripts/js/nivo-slider/themes/default/default.css" type="text/css" media="screen" />
								<link rel="stylesheet" href="'. _webURL .'/lib/scripts/js/nivo-slider/nivo-slider.css" type="text/css" media="screen" />
								<script type="text/javascript">
									$(window).load(function() {
										$(\'#slider\').nivoSlider();
									});	
								</script>													
					';
				} elseif($js === "checkbox") {
					$HTML  = '<script type="text/javascript">';
					$HTML .= '	function checkAll(idForm) {';
					$HTML .= '		for(i = 0; i < document.getElementById(idForm).elements.length; i++) {';
					$HTML .= '			if(document.getElementById(idForm).elements[i].type == "checkbox") {';
					$HTML .= '				document.getElementById(idForm).elements[i].checked = true;';
					$HTML .= '			}';
					$HTML .= '		}';
					$HTML .= '	}';
					$HTML .= '	function unCheckAll(idForm) {';
					$HTML .= '		for(i = 0; i < document.getElementById(idForm).elements.length; i++) {';
					$HTML .= '			if(document.getElementById(idForm).elements[i].type == "checkbox") {';
					$HTML .= '				document.getElementById(idForm).elements[i].checked = false;';
					$HTML .= '			}';
					$HTML .= '		}';
					$HTML .= '		}';
					$HTML .= '</script>';					
				} elseif($js === "external") {
					$HTML  = '<script type="text/javascript">';
					$HTML .= '$(document).ready(function() { 
								$(function() {
									$(\'a[rel*=external]\').click(function() {
										window.open(this.href);
										return false;
									});
								});
							});';
					$HTML .= '</script>';				
					$HTML .= '<noscript><p class="NoDisplay">'.__("Disable Javascript").'</p></noscript>';					
				} elseif($js === "insert-html") {
					$HTML  = '	<script type="text/javascript">';
					$HTML .= '		function insertHTML(content) {';
					$HTML .= '			parent.tinyMCE.execCommand(\'mceInsertContent\', false, content);';
					$HTML .= '		}';
					$HTML .= '</script>';
					$HTML .= '<noscript><p class="no-display">'. __("Disable Javascript") .'</p></noscript>';
				} elseif($js === "show-element") {
					$HTML  = '	<script type="text/javascript">';
					$HTML .= '		function showElement(obj) {';
					$HTML .= '			if(obj.className == "no-display") {';
					$HTML .= '				obj.className = "display";';
					$HTML .= '			} else {';
					$HTML .= '				obj.className = "no-display";';
					$HTML .= '			}';
					$HTML .= '		}';
					$HTML .= '	</script>';
				} elseif($js === "tiny-mce") {
					if($extra !== "basic") {
						$HTML  = '	<script type="text/javascript" src="'. _webURL .'/lib/scripts/js/tiny_mce/tiny_mce.js"></script>';
						$HTML .= '	<script type="text/javascript">';		
						$HTML .= '		tinyMCE.init({';
						$HTML .= '			mode : "exact",';
						$HTML .= '			elements : "editor",';
						$HTML .= '			theme : "advanced",';
						$HTML .= '			skin : "o2k7",';
						$HTML .= '			cleanup: true,';
						$HTML .= '			plugins : "advcode,safari,pagebreak,style,advhr,advimage,advlink,emotions,preview,media,fullscreen,template,inlinepopups,advimage,media,paste",';              
						$HTML .= '			theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,|,link,unlink,|,image,advcode,|,forecolor,|,charmap,|,pastetext,pasteword,pastetext,fullscreen,pagebreak,preview",';
						$HTML .= '			theme_advanced_buttons2 : "",';
						$HTML .= '			theme_advanced_buttons3 : "",';
						$HTML .= '			theme_advanced_toolbar_location : "top",';
						$HTML .= '			theme_advanced_toolbar_align : "left",';
						$HTML .= '			theme_advanced_statusbar_location : "bottom",';
						$HTML .= '			theme_advanced_resizing : false,';
						$HTML .= '			convert_urls : false,';                    
						$HTML .= '			content_CSS : "css/content.css",';               
						$HTML .= '			external_link_list_url : "lists/link_list.js",';
						$HTML .= '			external_image_list_url : "lists/image_list.js",';
						$HTML .= '			media_external_list_url : "lists/media_list.js"';
						$HTML .= '		});';
						$HTML .= '	</script>';	
					} else {
						$HTML  = '	<script type="text/javascript" src="'. _webURL .'/lib/scripts/js/tiny_mce/tiny_mce.js"></script>';
						$HTML .= '	<script type="text/javascript">';		
						$HTML .= '		tinyMCE.init({';
						$HTML .= '			mode : "exact",';
						$HTML .= '			elements : "editor",';
						$HTML .= '			theme : "simple",';
						$HTML .= '			editor_selector : "mceSimple"';
						$HTML .= '		});';
						$HTML .= '	</script>';	
					}
				} elseif($js === "upload") {
					$iPx   = (POST("iPx"))            ? POST("iPx")                                                  : 'i';
					$iPath = (POST("iPath"))          ? POST("iPath")                                                : 'lib/files/images/uploaded/';
					$iPath = (POST($iPx . "Dirbase")) ? POST($iPx . "Dirbase")                                       : $iPath;
					$iPath = (POST($iPx . "Make"))    ? POST($iPx . "Dir") . nice(POST($iPx . "Dirname")) . _sh 	 : $iPath;				
						
					$dPx   = (POST("dPx"))   ? POST("dPx")   : "d";
					$dPath = (POST("dPath")) ? POST("dPath") : "lib/files/documents/uploaded/";
					
					$dPath = (POST($dPx . "Dirbase")) ? POST($dPx . "Dirbase")                                       : $dPath;
					$dPath = (POST($dPx . "Make"))    ? POST($dPx . "Dir") . nice(POST($dPx . "Dirname")) . _sh 	 : $dPath;
					
					$application = ucfirst(segment(2));
					?>
						<script type="text/javascript">
						<!-- 
							function uploadResponse(state, file) {
								var path, insert, ok, error, form, message; 
								
								path = '<?php print _webURL . _sh . $iPath;?>' + file;
								HTML = '\'<img src=\\\'' + path + '\\\' alt=\\\'' + file + '\\\' />\'';
								insert = '<li><input name="iLibrary[]" type="checkbox" value="' + path + '" /><span class="small">00<' + '/span>';
								insert = insert + '<a href="' + path + '" rel="external" title="<?php print __("Preview"); ?>"><span class="tiny-image tiny-search">&nbsp;&nbsp;&nbsp;&nbsp;</span><' + '/a>';
								insert = insert + '<a class="pointer" onclick="javascript:insertHTML(' + HTML + ');" title="<?php print __("Insert image"); ?>"><span class="tiny-image tiny-add">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;';
								insert = insert + '<span class="bold">' + file + '<' + '/span><' + '/a><' + '/li>';						
								
								if(state == 1) {
									message = '<?php print __("The file size exceed the permited limit"); ?>';
								}
								
								if(state == 2) {
									message = '<?php print __("An error has ocurred"); ?>';
								}
								
								if(state == 3) {
									message = '<?php print __("The file type is not permited"); ?>';
								}
								
								if(state == 4) {
									message = '<?php print __("A problem occurred when trying to upload file"); ?>';
								}
								
								if(state == 5) {
									message = '<?php print __("The file already exists"); ?>';
								}
								
								if(state == 6) {
									message = '<?php print __("Successfully uploaded file"); ?>';
									document.getElementById('i-add-upload').innerHTML = insert + document.getElementById('i-add-upload').innerHTML;
								}
								
								document.getElementById('i-upload-message').innerHTML = message;
							}												
							
							function uploadDocumentsResponse(dState, dFile, dIcon, dAlt) {
								var dPath, dInsert, dOk, dError, dForm, dMessage, dHTML;
								
								dPath = '<?php print _webURL . _sh . $dPath; ?>' + dFile;					
								dHTML = '\'<a href=\\\'' + dPath + '\\\' title=\\\'' + dFile + '\\\'><img src=\\\'' + dIcon + '\\\' alt=\\\'' + dAlt + '\\\' /></a>\'';
								
								dInsert = '<li><input name="dLibrary[]" type="checkbox" value="' + dPath + '" />';
								dInsert = dInsert + '<span class="small">00<' + '/span><a href="' + dPath + '" title="<?php print __("Download file"); ?>">';
								dInsert = dInsert + '<span class="tiny-image tiny-file">&nbsp;&nbsp;&nbsp;&nbsp;</span><' + '/a>';
								dInsert = dInsert + '<a class="pointer" onclick="javascript:insertHTML(' + dHTML + ');" title="<?php print __("Insert file"); ?>">';
								dInsert = dInsert + '<span class="tiny-image tiny-add">&nbsp;&nbsp;&nbsp;&nbsp;</span>';
								dInsert = dInsert + '<span class="bold">' + dFile + '<' + '/span><' + '/a><' + '/li>';								
						
								if(dState == 1) {
									message = '<?php print __("The file size exceed the permited limit"); ?>';
								}
								
								if(dState == 2) {
									message = '<?php print __("An error has ocurred"); ?>';
								}
								
								if(dState == 3) {
									message = '<?php print __("The file type is not permited"); ?>';
								}
								
								if(dState == 4) {
									message = '<?php print __("A problem occurred when trying to upload file"); ?>';
								}
								
								if(dState == 5) {
									message = '<?php print __("The file already exists"); ?>';
								}								
								
								if(dState == 6) {
									message = '<?php print __("Successfully uploaded file"); ?>';
									document.getElementById('d-add-upload').innerHTML = dInsert + document.getElementById('d-add-upload').innerHTML;
								}
								
								document.getElementById('d-upload-message').innerHTML = message;
							}
						 -->
						</script>
						
						<noscript><p class="no-display"><?php print __("Disable Javascript"); ?></p></noscript>
					
					<?php
					return NULL;				
				}
				
				$this->js .= $HTML;
			}
		}
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
     * Set header title
     *
     * @return void
     */
	public function title($title = NULL) {
		if(is_null($title)) {
			$this->title = _webName;
		} else {
			$this->title = _webName . " - " . $title;
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
		
		if($direct === TRUE) {
			if(is_array($template)) {
				if(count($template) === 1) {
					include $template[0];
				} elseif(count($template) === 2) {
					include $template[0];
					include $template[1];
				} elseif(count($template) === 3) {
					include $template[0];
					include $template[1];
					include $template[2];
				} elseif(count($template) === 4) {
					include $template[0];
					include $template[1];
					include $template[2];
					include $template[3];
				} elseif(count($template) === 5) {
					include $template[0];
					include $template[1];
					include $template[2];
					include $template[3];
					include $template[4];
				}
			} else {
				if(!file_exists($template)) {
					die("Error 404: Theme Not Found: " . $template);
				}		
				
				include $template;
			}
		} else { 
			$this->template = _lib . _sh . _themes . _sh . $this->theme . _sh . $template . _PHP;
			
			if(!file_exists($this->template)) {
				die("Error 404: Theme Not Found: " . $this->template);									
			}
			
			include $this->template; 
			
			return TRUE;
		}									
	}	
	
    /**
     * Verify if a template exists
     *
     * @return boolean value
     */
	public function exists($template, $view = FALSE) {
		if($view === FALSE) {
			if(file_exists(_lib . _sh . _themes . _sh . $this->theme . _sh . $template . _PHP)) {
				return TRUE; 
			} else {
				return FALSE;
			}
		} elseif(file_exists(_applications . _sh . $view . _sh . _views . _sh . _view . _dot . $template . _PHP)) {
			return TRUE; 
		} else {
			return FALSE;
		}
	}
	
    /**
     * Verify if a theme exists
     *
     * @return boolean value
     */
	public function isTheme() {
		if($this->theme !== NULL) {
			$this->path = _lib . _sh . _themes . _sh . $this->theme;
		} else {
			$this->path = FALSE;
		}
		
		$this->directory = @dir($this->path);
		
		if($this->directory !== FALSE) {
			return TRUE;
		}
		
		return FALSE;
	}

    /**
     * Gets the list of available designs
     *
     * @return array value
     */	
	public function getThemes($theme) {
		$path    = _lib . _sh . _themes . _sh;
		$dir	 = dir($path);
		$options = FALSE;
		
		$i = 0;
		
		while($element = $dir->read()) {
			$directory = $path . $element . _sh;						
			
			if($element !== ".." and $element !== "." and is_dir($directory) and $element !== _cpanel) {
				if($element === $theme) {
					$options[$i] = '<option value="'. $element .'" selected="selected">'. $element .'</option>';
				} else {
					$options[$i] = '<option value="'. $element .'">'. $element .'</option>';
				}
								
				$i++;
			}
		}	
			
		$dir->close();		
		
		return $options;
	}
}
