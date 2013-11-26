<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

if (!function_exists("a")) {
	function a($text, $URL = null, $external = false, $attributes = false)
	{
		$attrs = null;
		
		if (is_array($attributes)) {
			foreach ($attributes as $attribute => $value) {
				$attrs .= ' '. strtolower($attribute) .'="'. $value .'"';
			}
		}
		
		if (is_null($URL)) {
			return '<a name="'. $text .'"></a>';	
		} elseif (!$URL) {
			return '<a'. $attrs .'>'. $text .'</a>';
		} elseif ($external) {
			return '<a target="_blank" href="'. $URL .'"'. $attrs .'>'. $text .'</a>';
		} else {
			return '<a href="'. $URL .'"'. $attrs .'>'. $text .'</a>';
		}
	}
}

if (!function_exists("body")) {
	function body($open = true)
	{
		return ($open) ? "<body>" : "</body>";
	}
}

if (!function_exists("bold")) {	
	function bold($text, $br = true)
	{
		$HTML = '<span class="Bold">'. $text .'</span>'; 				
		return ($br === true) ? $HTML .'<br />' : $HTML;				
	}
}

if (!function_exists("br")) {
	function br($jumps = 1)
	{
		$br = null;
		
		for ($i = 0; $i < $jumps; $i++) {
			$br .= "<br />";
		}
		
		return $br;
	}
}

if (!function_exists("char")) {
	function char($char, $repeat = 1)
	{
		$HTML = null;
		
		for ($i = 0; $i <= $repeat; $i++) {
			$HTML .= $char;
		}
			
		return $HTML;
	}
}

if (!function_exists("deleteImg")) {
	function deleteImg($HTML)
	{
		return preg_replace("/<img[^<>]*/>/", "", $HTML);	
	}
}

if (!function_exists("div")) {	
	function div($ID, $type = "id", $style = null, $content = null)
	{ 
		if (!$ID) {
			return '</div>';
		} elseif (!$type) {
			return '<div class="'. $ID .'">'. $content .'</div>';
		} elseif ($type === "id") {
			return '<div id="'. $ID .'">';	
		} elseif ($type === "id/class") {
			return '<div id="'. $ID .'" class="'. $style .'">';		
		} elseif ($type === "class") {
			return '<div class="'. $ID .'">';
		} elseif ($type) {
			return '<div id="'. $ID .'">'. $content .'</div>';		
		} 
	}
}

if (!function_exists("docType")) {
	function docType($type = "HTML5")
	{		
		return ($type === "HTML5") ? '<!DOCTYPE html>' : '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xHTML1/DTD/xHTML1-strict.dtd">';
	}
}

if (!function_exists("getHTMLDecode")) {
	function getHTMLDecode($HTML)
	{
		return html_entity_decode($HTML);
	}
}

if (!function_exists("getFavicon")) {
	function getFavicon()
	{
		return '<link rel="shortcut icon" href="'. path("www/lib/images/favicon.ico", true) .'" />';
	}
}

if (!function_exists("h1")) {
	function h1($text)
	{
		return "<h1>$text</h1>";
	}
}

if (!function_exists("h2")) {
	function h2($text)
	{
		return "<h2>$text</h2>";
	}
}

if (!function_exists("h3")) {
	function h3($text)
	{
		return "<h3>$text</h3>";
	}
}

if (!function_exists("head")) {
	function head($open = true)
	{		
		return ($open) ? "<head>" : "</head>";	
	}
}

if (!function_exists("HTML")) {
	function HTML($open = true)
	{
		return ($open) ? '<html lang="'. _get("webLang") .'" xml:lang="'. _get("webLang") .'">' : '</html>';		
	}
}

if (!function_exists("img")) {
	function img($src, $attributes = null)
	{
		$attrs = null;

		if (is_array($attributes)) {
			foreach ($attributes as $attribute => $value) {
				$attrs .= ' '. $attribute .'="'. $value .'"';
			}	
		}

		return '<img src="'. $src .'"'. $attrs .' />';
	}
}

if (!function_exists("li")) {	
	function li($list, $open = null)
	{
		$HTML = null;
		
		if (isMultiArray($list)) {		
			foreach ($list as $li) {
				$class = (isset($li["class"])) ? ' class="'. $li["class"] .'"' : null;
				
				if (isset($li["item"]) and strlen($li["item"]) > 1) {
					$HTML .= '<li'. $class .'>'. $li["item"] .'</li>';			
				}
			}
		} elseif (is_array($list)) {
			for ($i = 0; $i <= count($list) - 1; $i++) {
				$HTML .= char("\t", 2) .'<li>'. $list[$i] .'</li>';
			}
		} elseif ($list and $open) {
			$HTML .= "<li>". $list;
		} elseif ($open === false) {
			$HTML .= "</li>";
		} else {
			$HTML .= '<li>'. $list .'</li>';
		}
				
		return $HTML;
	}
}

if (!function_exists("loadCSS")) {
	function loadCSS($CSS)
	{
		return '<link rel="stylesheet" href="'. _get("webURL") ."/". $CSS .'" type="text/css" media="all" />';
	}
}

if (!function_exists("loadScript")) {
	function loadScript($js, $application = null, $external = false)
	{
		if (file_exists($js)) {		
			return '<script type="text/javascript" src="'. _get("webURL") ."/". $js .'"></script>';
		} if ($external) {
			return '<script type="text/javascript" src="'. $js .'"></script>';
		} else {
			if (isset($application)) {				
				if (file_exists($file)) {
					return '<script type="text/javascript" src="'. _get("webURL") .'/www/applications/'. $application .'/views/js/'. $js .'.js"></script>';
				}
			}
		}
	}
}

if (!function_exists("openUl")) {
	function openUl($ID = null, $class = null)
	{
		$ID = (!is_null($ID)) ? ' id="'. $ID .'"': null;
		$class = (!is_null($class)) ? ' class="'. $class .'"' : null; 
		
		return '<ul'. $ID . $class .'>' ;
	}
}

if (!function_exists("closeUl")) {
	function closeUl()
	{
		return '</ul>';
	}
}

if (!function_exists("p")) {
	function p($text, $class = null)
	{
		if (is_string($text)) {
			if (is_null($class)) {
				return '<p>'. $text .'</p>';
			} else {
				return '<p class="'. $class .'">'. $text .'</p>' ;
			}
		} elseif ($text === true) {
			if (is_null($class)) {
				return '<p>';
			} else {
				return '<p class="'. $class .'">';
			}
		} else {
			return '</p>';
		}
	}
}

if (!function_exists("small")) {
	function small($text)
	{
		return '<span class="small">'. $text .'</span>';
	}
}

if (!function_exists("span")) {
	function span($class, $value, $ID = false)
	{
		if ($ID) {
			return '<span id="'. $ID .'"'. (!is_null($class) ? ' class="'. $class .'"' : null) .'>'. $value .'</span>';	
		}

		return '<span class="'. $class .'">'. $value .'</span>';
	}
}

if (!function_exists("ul")) {
	function ul($list, $ID = null, $class = null)
	{
		$ID = (!is_null($ID)) ? ' id="'.$ID.'"' : null;
		$class = (!is_null($class)) ? ' class="'. $class .'"' : null;
		
		$HTML = '<ul'. $ID . $class .'>';

		if (isMultiArray($list)) {
			foreach ($list as $li) {
				$class = (isset($li["class"])) ? ' class="'. $li["class"] .'"' : null;				
				$HTML .= '<li' . $class . '>'. $li["item"] .'</li>' ;
			}
		} elseif (is_array($list)) {
			for ($i = 0; $i <= count($list) - 1; $i++) {
				$HTML .= '<li>'. $list[$i] .'</li>';
			}
		}

		return $HTML .'</ul>' ;		
	}
}

if (!function_exists("htmlTag")) {
	function htmlTag($tag = null, $attributes = true, $content = null)
	{
	    if (is_null($tag)) {
	    	return null;
	    }

	    if ($attributes === true) {
	        return "<$tag>";
	    } elseif ($attributes === false) {
	        return "</$tag>";
	    } elseif (is_array($attributes)) {
	        $HTML = "<$tag";
	        
	        foreach ($attributes as $attribute => $value) {
	            $HTML .= " $attribute = \"$value\"";
	        }
	        
	        $HTML .= ">";
	        
	        if (! is_null($content)) {
	            $HTML .= "$content</$tag>";
	        } elseif ($content === false) {
	        	$HTML .= "</$tag>";
	        }
	        
	        return $HTML;
	    } else {
	        return "<$tag>$attributes</$tag>";
	    }
	}
}

if (!function_exists("image")) {
	function image($source, $class = null, $ID = false, $attributes = false)
	{
		$class = !is_null($class) ? ' class="'. $class .'"' : null;
		$attrs = '';

		if (is_array($attributes)) {
			foreach ($attributes as $attribute => $value) {
				if ($attribute !== "p") {
	            	$attrs .= " $attribute = \"$value\"";
				} else {
					$$attribute = $value;
				}
	        }
		}
		
		$HTML = ($ID) ? '<img id="'. $ID .'"'. $class .' src="'. $source .'" />' : '<img'. $class .' src="'. $source .'" />';		
		return (isset($p) and $p === true) ? p($HTML) : $HTML;		
	}
}