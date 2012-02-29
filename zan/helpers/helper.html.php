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
			$attrs .= ' '. strtolower($attribute) .'="'. $value .'"';
		}
	}
	
	if(is_null($URL)) {
		return '<a name="'. $text .'"></a>';	
	} elseif(!$URL) {
		return '<a'. $attrs .'>'. $text .'</a>';
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
	
	for($i = 0; $i < $jumps; $i++) {
		$br .= "<br />" . char("\n");
	}
	
	return $br;
}

function char($char, $repeat = 1) {
	$HTML = NULL;
	
	for($i = 0; $i <= $repeat; $i++) {
		$HTML .= $char;
	}
		
	return $HTML;
}

function deleteImg($HTML) {
	return preg_replace("/<img[^<>]*/>/", "", $HTML);	
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

function img($src, $attributes = NULL) {
	$attrs = NULL;

	if(is_array($attributes)) {
		foreach($attributes as $attribute => $value) {
			$attrs .= ' '. $attribute .'="'. $value .'"';
		}	
	}

	return '<img src="'. $src .'"'. $attrs .' />';
}
	
function li($list, $open = NULL) {
	$HTML = NULL;
	
	if(isMultiArray($list)) {		
		foreach($list as $li) {
			$class = (isset($li["class"])) ? ' class="'. $li["class"] .'"' : NULL;
			
			if(strlen($li["item"]) > 1) {
				$HTML .= char("\t", 2) .'<li'. $class .'>'. $li["item"] .'</li>'. char("\n");			
			}
		}
	} elseif(is_array($list)) {
		for($i = 0; $i <= count($list) - 1; $i++) {
			$HTML .= char("\t", 2) .'<li>'. $list[$i] .'</li>'. char("\n");
		}
	} elseif($list and $open) {
		$HTML .= "\t\t <li>". $list;
	} elseif($open === FALSE) {
		$HTML .= "</li>". "\n";
	} else {
		$HTML .= "\t\t". '<li>'. $list .'</li>'. "\n";
	}
			
	return $HTML;
}

function loadCSS($CSS) {
	return '<link rel="stylesheet" href="'. _webURL . "/" . $CSS .'" type="text/css" media="all" />';
}

function loadScript($js, $application = NULL, $external = FALSE) {
	if(file_exists($js)) {		
		return '<script type="text/javascript" src="'. _webURL . "/" . $js .'"></script>';
	} if($external) {
		return '<script type="text/javascript" src="'. $js .'"></script>';
	} else {
		if(isset($application)) {
			$file = "www/applications/$application/views/js/$js.js";
			
			if(file_exists($file)) {
				return '<script type="text/javascript" src="'. _webURL . "/" . $file .'"></script>';
			}
		}
	}
}

function openUl($ID = NULL, $class = NULL) {
	$ID    = (!is_null($ID))    ? ' id="'. $ID .'"'       : NULL;
	$class = (!is_null($class)) ? ' class="'. $class .'"' : NULL; 
	
	return '<ul'. $ID . $class .'>' . char("\n");
}

function closeUl() {
	return '</ul>';
}

function p($text, $class = "left") {
	if(is_string($text)) {
		return char("\n\t") . '<p class="'. $class .'">'. char("\n\t\t") . $text . char("\n\t") . '</p>' . char("\n");
	} elseif($text === TRUE) {
		return '<p class="'. $class .'">';
	} else {
		return '</p>';
	}
}

function paginate($count, $end, $start, $URL, $anchor = "#top") {
	$pageNav 	  = NULL;
	$pagePrevious = NULL;
	$pageFirst    = NULL;
	$pageLast     = NULL;
	$pageNext     = NULL;
	
	if($count > $end) {				
		$rest = $count % $end;	
					
		if($rest === 0) {
			$pages = $count / $end;
		} else {
			$pages = (($count - $rest) / $end) + 1;
		}

		if($pages > 10) {	
			$currentPage = ($start / $end) + 1;
			
			if($start === 0) {
				$firstPage = 0;
				$lastPage  = 10;
			} elseif($currentPage >= 5 and $currentPage <= ($pages - 5)) {					
				$firstPage = $currentPage - 5;
				$lastPage  = $currentPage + 5;					
			} elseif($currentPage < 5) {					
				$firstPage = 0;
				$lastPage  = $currentPage + 5 + (5 - $currentPage);					
			} else {					
				$firstPage = $currentPage - 5 - (($currentPage + 5) - $pages);
				$lastPage	= $pages;					
			}								
		} else {			
			$firstPage = 0;
			$lastPage  = $pages;			
		}
			
		for($i = $firstPage; $i < $lastPage; $i++) {
			$pge  = $i + 1;
			$next = $i * $end;		
					
			if($start == $next) {				
				$pageNav .= '<span class="current">'. $pge .'</span> ';					
			} else {				
				$pageNav .= '<span class="bold"><a href="'. $URL . $pge . "/" . $anchor .'" title="'. $pge .'">'. $pge .'</a></span> ';
			}
		}
	
		if($start == 0) { 			
			$currentPage = 1; 			
		} else { 			
			$currentPage = ($start / $end) + 1; 			
		}
	
		if($currentPage < $pages) {			
			$pageNext = '<a href="'. $URL . ($currentPage + 1) . "/" . $anchor .'" title="'. __(_("Next")) .'">'. __(_("Next")) .'</a> ';
		}
	
		if($start > 0) {
			$pagePrevious = '<a href="'. $URL . ($currentPage - 1) . "/" . $anchor .'" title="'. __(_("Previous")) .'">'. __(_("Previous")) .'</a> ';
		}			
	}		
		
	return '<div id="pagination">'. $pageFirst . $pagePrevious . $pageNav . $pageNext . $pageLast .'</div>';
}

function small($text) {
	return '<span class="small">'. $text .'</span>';
}	

function span($class, $value, $ID = FALSE) {
	if($ID) {
		$class = !is_null($class) ? ' class="'. $class .'"' : NULL;
		
		return '<span id="'. $ID .'"'. $class .'>'. $value .'</span>';	
	}

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
