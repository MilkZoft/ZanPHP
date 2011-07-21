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
 	
function anchor($text, $URL = NULL, $title = NULL, $external = FALSE, $class = NULL, $onclick = NULL, $onchange = NULL, $onmouseover = NULL) {
	$title  = (is_null($title))  ? removeSpaces(cleanHTML($text)) : $title;
	$class  = (!is_null($class)) ? ' class="' . $class . '"' : NULL;
	$events = NULL;
	
	if(!is_null($onclick)) {
		$events  = ' onclick="' . $onclick . '"';
	}
	
	if(!is_null($onchange)) {
		$events .= ' onchange="' . $onchange . '"';
	}
	
	if(!is_null($onmouseover)) {
		$events .= ' onmouseover="' . $onmouseover . '"'; 
	}
	
	if($URL === FALSE) {
		return '<a title="' . $title . '"' . $class . ' name="' . $title . '"' . $events . '>' . $text . '</a>';
	} elseif($URL === NULL) {
		return '<a name="' . $text . '"></a>';	
	} elseif($external === TRUE) {
		return '<a rel="external" href="' . $URL . '" title="' . $title . '"' . $events . '>' . $text . '</a>';
	} else {
		return '<a href="' . $URL . '" title="' . $title . '"' . $events . '>' . $text . '</a>';
	}
}

function body($open = TRUE) {
	if($open === TRUE) {
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
	
	if(_webCharacters === TRUE) {
		for($i = 0; $i <= $repeat; $i++) {
			$HTML .= $char;
		}
		
		return $HTML;
	}
	
	return NULL;
}

function deleteImg($HTML) {
	return eregi_replace("<img[^<>]*/>", "", $HTML);	
}
	
function div($ID, $type = "id", $style = NULL, $content = NULL) { 
	if($ID === FALSE) {
		return '</div>' 										 . char("\n");
	} elseif($type === TRUE) {
		return '<div id="' . $ID . '">' . $content. '</div>' 	 . char("\n");		
	} elseif($type === FALSE) {
		return '<div class="' . $ID . '">' . $content . '</div>' . char("\n");
	} elseif(strtolower($type) === "id") {
		return '<div id="' . $ID . '">' 						 . char("\n\t");	
	} elseif(strtolower($type) === "id/class") {
		return '<div id="' . $ID . '" class="' . $style . '">'	 . char("\n\t");		
	} elseif(strtolower($type) === "class") {
		return '<div class="' . $ID . '">' 						 . char("\n\t");
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

function head($open = TRUE) {
	if($open === TRUE) {
		return "<head>"	 . char("\n\t");	
	} else {
		return "</head>" . char("\n\t");
	}
}

function HTML($open = TRUE) {
	if($open === TRUE) {
		return '<html xmlns="http://www.w3.org/1999/xhtml" lang="'._webLang.'" xml:lang="'._webLang.'">' . char("\n");
	} else {
		return "</html>";
	}
}
	
function p($text, $class = "left") {
	return char("\n\t") . '<p class="' . $class . '">'. char("\n\t\t") . $text . char("\n\t") . '</p>' . char("\n");
}

function small($text) {
	return '<span class="small">' . $text . '</span>';
}	

function span($class, $value) {
	return '<span class="' . $class . '">' . $value . '</span>';
}

function openUl($ID = NULL, $class = NULL) {
	$ID    = (!is_null($ID))    ? ' id="' . $ID . '"'       : NULL;
	$class = (!is_null($class)) ? ' class="' . $class . '"' : NULL; 
	
	return '<ul' . $ID . $class.'>' . char("\n");
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

function closeUl() {
	return char("\t") . "</ul>" . char("\n");
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
