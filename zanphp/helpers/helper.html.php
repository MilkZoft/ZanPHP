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
 * HTML Helper
 *
 * 
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	helpers
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/helpers/html_helper
 */
 	
function a($text, $URL = NULL, $external = FALSE, $attributes = FALSE) {
	$attrs = NULL;
	
	if(is_array($attributes)) {
		foreach($attributes as $attribute => $value) {
			$attrs .= ' '. strtolower($attribute) .'="'. encode($value) .'"';
		}
	}
		 
	if(!$URL) {
		return '<a'. $attrs .'>'. $text .'</a>';
	} elseif(is_null($URL)) {
		return '<a name="'. $text .'"></a>';	
	} elseif($external) {
		return '<a target="_blank" href="'. $URL .'"'. $attrs .'>'. $text .'</a>';
	} else {
		return '<a href="'. $URL .'"'. $attrs .'>'. $text .'</a>';
	}
}

function body($open = TRUE) {
	if($open) {
		return "<body>" . char("\n");
	} else {
		return "</body>";
	}
}
	
function bold($text, $br = TRUE) {
	$HTML = '<span class="Bold">' . $text . '</span>'; 
	
	if($br === TRUE) {
		$HTML .= '<br />';
	}
	
	return $HTML;
}

function br($jumps = 1) {
	$br = NULL;
	
	for($i = 0; $i <= $jumps; $i++) {
		$br .= "<br />" . char("\n");
	}
	
	return $br;
}

function char($char, $repeat = 1) {
	$HTML = NULL;
	
	if(_webCharacters) {
		for($i = 0; $i <= $repeat; $i++) {
			$HTML .= $char;
		}
		
		return $HTML;
	}
	
	return NULL;
}

function closeUl() {
	return char("\t") . "</ul>" . char("\n");
}

function deleteImg($HTML) {
	return eregi_replace("<img[^<>]*/>", "", $HTML);	
}
	
function div($ID, $type = "id", $style = NULL, $content = NULL) { 
	if(!$ID) {
		return '</div>' 									 . char("\n");
	} elseif(!$type) {
		return '<div class="'. $ID .'">'. $content .'</div>' . char("\n");
	} elseif($type === "id") {
		return '<div id="'. $ID .'">' 						 . char("\n\t");	
	} elseif($type === "id/class") {
		return '<div id="'. $ID .'" class="'. $style .'">'	 . char("\n\t");		
	} elseif($type === "class") {
		return '<div class="'. $ID .'">' 					 . char("\n\t");
	} elseif($type) {
		return '<div id="'. $ID .'">'. $content .'</div>' 	 . char("\n");		
	} 
}

function docType($type = "XHTML 1.0 Strict") {
	if($type === "HTML 5") {
		return '<!DOCTYPE HTML>' . char("\n");
	} elseif($type === "XHTML 1.0 Strict") {
		return '<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xHTML1/DTD/xHTML1-strict.dtd">' . char("\n");
	}
}

function getHTMLDecode($HTML) {
	return html_entity_decode($HTML);
}

function getScript($js, $application = NULL, $extra = NULL, $getJs = FALSE) {
	if(file_exists($js)) {		
		return loadScript($js);
	} else {
		if(isset($application)) {
			return loadScript($js, $application);
		} else {
			if($js === "jquery") {
				return loadScript("lib/scripts/js/jquery.js");
			} elseif($js === "external") {
				$HTML = '	<script type="text/javascript">
								$(document).ready(function() { 
									$(function() {
										$(\'a[rel*=external]\').click(function() {
											window.open(this.href);
											return false;
										});
									});
								});
							</script>				
							
							<noscript><p class="NoDisplay">'. __("Disable Javascript") .'</p></noscript>';					
			} elseif($js === "show-element") {
				$HTML  = '	<script type="text/javascript">
								function showElement(obj) {
									if(obj.className == "no-display") {
										obj.className = "display";
									} else {
										obj.className = "no-display";
									}
								}
							</script>';
			} elseif($js === "tiny-mce") {
				$HTML  = loadScript("lib/scripts/js/tiny_mce/tiny_mce.js");
				$HTML .= '<script type="text/javascript">';
				
				if($extra !== "basic") {
					$HTML .= '			
									tinyMCE.init({
										mode : "exact",
										elements : "editor",
										theme : "advanced",
										skin : "o2k7",
										cleanup: true,
										plugins : "advcode,safari,pagebreak,style,advhr,advimage,advlink,emotions,preview,media,fullscreen,template,inlinepopups,advimage,media,paste",              
										theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,|,link,unlink,|,image,advcode,|,forecolor,|,charmap,|,pastetext,pasteword,pastetext,fullscreen,pagebreak,preview",
										theme_advanced_buttons2 : "",
										theme_advanced_buttons3 : "",
										theme_advanced_toolbar_location : "top",
										theme_advanced_toolbar_align : "left",
										theme_advanced_statusbar_location : "bottom",
										theme_advanced_resizing : false,
										convert_urls : false,                    
										content_CSS : "css/content.css",               
										external_link_list_url : "lists/link_list.js",
										external_image_list_url : "lists/image_list.js",
										media_external_list_url : "lists/media_list.js"
									});
							';	
				} else {
					$HTML .= '		
									tinyMCE.init({
										mode : "exact",
										elements : "editor",
										theme : "simple",
										editor_selector : "mceSimple"
									});
							';	
				}				
				
				$HTML .= '	function insertHTML(content) {
								parent.tinyMCE.execCommand(\'mceInsertContent\', false, content);
							}
						</script>';
			}
			
			return $HTML;
		}
	}
}

function h1($text) {
	return char("\t") . "<h1>$text</h1>" . char("\n");
}

function h2($text) {
	return char("\t") . "<h2>$text</h2>" . char("\n");
}

function h3($text) {
	return char("\t") . "<h3>$text</h3>" . char("\n");
}

function head($open = TRUE) {
	if($open) {
		return "<head>"	 . char("\n\t");	
	} else {
		return "</head>" . char("\n\t");
	}
}

function HTML($open = TRUE) {
	if($open) {
		return '<html xmlns="http://www.w3.org/1999/xhtml" lang="'._webLang.'" xml:lang="'._webLang.'">' . char("\n");
	} else {
		return "</html>";
	}
}

function img($src, $alt = NULL, $class = "no-border", $attributes = NULL) {
	if(is_null($alt)) {
		return '<img src="' . $src . '" ' . $attributes . ' />';
	} elseif(!is_null($alt) and !is_null($class)) {
		return '<img src="' . $src . '" alt="' . $alt . '" title="' . $alt . '" class="' . $class . '" ' . $attributes . ' />';
	} elseif(!is_null($alt)) {
		return '<img src="' . $src . '" alt="' . $alt . '" title="' . $alt . '" ' . $attributes . ' />';
	} elseif(!is_null($class)) {
		return '<img src="' . $src . '" class="' . $class . '" ' . $attributes . ' />';
	}
}
	
function li($list) {
	$HTML = NULL;
	
	if(isMultiArray($list)) {		
		foreach($list as $li) {
			$class = (isset($li["class"])) ? ' class="' . $li["class"] . '"' : NULL;
			
			if(strlen($li["item"]) > 1) {
				$HTML .= char("\t", 2) . '<li' . $class . '>' . $li["item"] . '</li>' . char("\n");			
			}
		}
	} elseif(is_array($list)) {
		for($i = 0; $i <= count($list) - 1; $i++) {
			$HTML .= char("\t", 2) . '<li>' . $list[$i] . '</li>' . char("\n");
		}
	} else {
		$HTML .= char("\t", 2) . '<li>' . $list . '</li>' . char("\n");
	}
			
	return $HTML;
}

function loadCSS($CSS) {
	return '<link rel="stylesheet" href="'. _webURL . _sh . $CSS .'" type="text/css" media="all" />';
}

function loadScript($js, $application = NULL) {
	if(file_exists($js)) {		
		return '<script type="text/javascript" src="'. _webURL . _sh . $js .'"></script>';
	} else {
		if(isset($application)) {
			$file = _applications . _sh . $application . _sh . _views . _sh . _js . _sh . $js . _dot . _js;
			
			if(file_exists($file)) {
				return '<script type="text/javascript" src="'. _webURL . _sh . $file .'"></script>';
			}
		}
	}
}

function openUl($ID = NULL, $class = NULL) {
	$ID    = (!is_null($ID))    ? ' id="'. $ID .'"'       : NULL;
	$class = (!is_null($class)) ? ' class="'. $class .'"' : NULL; 
	
	return '<ul'. $ID . $class .'>' . char("\n");
}

function p($text, $class = "left") {
	return char("\n\t") . '<p class="'. $class .'">'. char("\n\t\t") . $text . char("\n\t") . '</p>' . char("\n");
}

function small($text) {
	return '<span class="small">'. $text .'</span>';
}	

function span($class, $value) {
	return '<span class="'. $class .'">'. $value .'</span>';
}

function ul($list, $ID = NULL, $class = NULL) {
	$ID    = (!is_null($ID))    ? ' id="'.$ID.'"'       : NULL;
	$class = (!is_null($class)) ? ' class="'.$class.'"' : NULL;
	
	$HTML = '<ul' . $ID . $class . '>' . char("\t");
		if(isMultiArray($list)) {
			foreach($list as $li) {
				$class = (isset($li["class"])) ? ' class="'.$li["class"].'"' : NULL;
				
				$HTML .= char("\t", 2) . '<li' . $class . '>' . $li["item"] . '</li>' . char("\n");
			}
		} elseif(is_array($list)) {
			for($i = 0; $i <= count($list) - 1; $i++) {
				$HTML .= char("\t", 2) . '<li>' . $list[$i] . '</li>' . char("\n");
			}
		}
	$HTML .= char("\t") . '</ul>' . char("\n");
	
	return $HTML;
}