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
 * String Helper
 *
 * 
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	helpers
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/helpers/string_helper
 */

 /**
 * String Helper
 *
 * Cleans HTML from a String
 * 
 * @param string $HTML
 * @return string $text
 */ 
function cleanHTML($HTML) {
	$search = array ('@<script[^>]*?>.*?</script>@si',
					 '@<[\/\!]*?[^<>]*?>@si',
					 '@([\r\n])[\s]+@',
					 '@&(quot|#34);@i',
					 '@&(amp|#38);@i',
					 '@&(lt|#60);@i',
					 '@&(gt|#62);@i',
					 '@&(nbsp|#160);@i',
					 '@&(iexcl|#161);@i',
					 '@&(cent|#162);@i',
					 '@&(pound|#163);@i',
					 '@&(copy|#169);@i',
					 '@&#(\d+);@e');
	
	$replace = array('',
					 '',
					 '\1',
					 '"',
					 '&',
					 '<',
					 '>',
					 ' ',
					 chr(161),
					 chr(162),
					 chr(163),
					 chr(169),
					 'chr(\1)');
	
	$text = preg_replace($search, $replace, $HTML);	
	
	return $text;
}

 /**
 * removeSpaces
 *
 * Removes blank spaces
 * 
 * @param string $text
 * @param bool   $trim
 * @return string $text
 */ 
function removeSpaces($text, $trim = FALSE) {
	$text = str_replace("           ", " ", $text);
	$text = str_replace("          ", " ", $text);
	$text = str_replace("         ", " ", $text);
	$text = str_replace("        ", " ", $text);
	$text = str_replace("       ", " ", $text);
	$text = str_replace("      ", " ", $text);
	$text = str_replace("     ", " ", $text);
	$text = str_replace("    ", " ", $text);
	$text = str_replace("   ", " ", $text);
	$text = str_replace("  ", " ", $text);
	
	if($trim === TRUE) {
		return trim($text);
	}
	
	return $text;
}

 /**
 * compress
 *
 * Compresses a string
 * 
 * @param string $text
 * @return string $text
 */ 
function compress($string) {
    $string = str_replace(array("\r\n", "\r", "\n", "\t", "  ", "    ", "    "), "", $string);
        
	return $string;	
}

/**
 * POST
 * 
 * Gets a specific position value from $_POST
 * 
 * @param string $position
 * @param string $coding   = "decode"
 * @return mixed
 */ 
function POST($position = FALSE, $coding = "decode", $filter = TRUE) {
	if(!$position) {
		____($_POST);
	} elseif(isset($_POST[$position]) and is_array($_POST[$position])) {
		return $_POST[$position];
	} elseif(isset($_POST[$position]) and $_POST[$position] === "") {
		return NULL;
	} elseif(isset($_POST[$position])) {
		if($coding === "encrypt") {
			if($filter === TRUE) {
				return encrypt(encode($_POST[$position]));
			} elseif($filter === "escape") {
				return encrypt(filter(encode($_POST[$position]), "escape"));
			} else {
				return encrypt(filter(encode($_POST[$position]), TRUE));
			}
		} elseif($coding === "encode") {
			if($filter === TRUE) {
				return encode($_POST[$position]);
			} elseif($filter === "escape") {
				return filter(encode($_POST[$position]), "escape");
			}  else {
				return filter(encode($_POST[$position]), TRUE);
			}
		} elseif($coding === "decode-encrypt") {
			if($filter === TRUE) {
				return encrypt(filter($_POST[$position], TRUE));
			} elseif($filter === "escape") {
				return encrypt(filter($_POST[$position], "escape"));
			}  else {
				return encrypt($_POST[$position]);
			}		
		} elseif($coding === "decode") {			
			if($filter === TRUE) {
				return filter(decode($_POST[$position]), TRUE);
			} elseif($filter === "escape") {
				return filter(decode($_POST[$position]), "escape");
			}  else {
				return decode($_POST[$position]);
			}
		} else {
			if($filter === TRUE) {
				return filter($_POST[$position], TRUE);
			} elseif($filter === "escape") {
				return filter($_POST[$position], "escape");
			}  else {
				return $_POST[$position];
			}		
		}
	} else {
		return FALSE;
	}
}

/**
 * FILES
 * 
 * Gets a specific position value from $_FILES
 * 
 * @param mixed  $name   = FALSE
 * @param string $coding = NULL
 * @return mixed
 */ 
function FILES($name = FALSE, $position = NULL, $i = NULL) {
	if(!$name) {
		____($_FILES);
	} elseif($position === NULL) {
		return isset($_FILES[$name]) ? $_FILES[$name] : FALSE;
	} elseif($i !== NULL and is_numeric($i)) {
		return isset($_FILES[$name][$position][$i]) ? $_FILES[$name][$position][$i] : FALSE;
	} else {
		return isset($_FILES[$name][$position]) ? $_FILES[$name][$position] : FALSE;
	}
}

/**
 * GET
 * 
 * Gets a specific position from $_GET
 *
 * @param string $position
 * @param string $coding = "decode"
 * @return mixed
 */
function GET($position = FALSE, $coding = "decode", $filter = TRUE) {
	if(!$position) {
		____($_POST);
	} elseif(isset($_GET[$position])) {
		if($coding === "encrypt") {
			if($filter === TRUE) {
				return encrypt(encode($_GET[$position]));
			} else {
				return encrypt(filter(encode($_GET[$position]), TRUE));
			}
		} elseif($coding === "encode") {
			if($filter === TRUE) {
				return encode($_GET[$position]);
			} else {
				return filter(encode($_GET[$position]), TRUE);
			}
		} elseif($coding === "decode-encrypt") {
			if($filter === TRUE) {
				return encrypt(filter(decode($_GET[$position]), TRUE));
			} else {
				return encrypt(decode($_GET[$position]));
			}		
		} else {
			if($filter === TRUE) {
				return filter(decode($_GET[$position]), TRUE);
			} else {
				return decode($_GET[$position]);
			}
		}
	} else {
		return FALSE;
	}
}

function getTotal($count, $singular, $plural) {
	if((int) $count === 0) {
		return $count . " " . __($plural);
	} elseif((int) $count === 1) {
		return $count . " " . __($singular);
	} else {
		return $count . " " . __($plural);
	}
}

/**
 * recoverPOST
 * 
 * Recovers data from $_POST
 *
 * @parama string $position
 * @parama string $value = NULL
 * @return string
 */
function recoverPOST($position, $value = NULL) {
	if(!$value) {
		return (POST($position)) ? htmlentities(POST($position, "clean", FALSE)) : NULL;
	} else {
		if(is_array($value)) {
			foreach($value as $val) {
				$data[] = htmlentities($val);
			}
			
			return $data;
		} else {
			return (POST($position)) ? htmlentities(POST($position, "clean", FALSE)) : htmlentities(decode($value));
		}	
	}
}

/**
 * getFileSize
 * 
 * Gets a specific position from $_GET
 *
 * @param string $position
 * @param string $coding = "decode"
 * @return string $coding = "decode"
 */
function getFileSize($size) {	
	if($size <= 0) {
		return FALSE;		
	} elseif($size < 1048576) {
		return round($size / 1024, 2) . " Kb";
	} else {
		return round($size / 1048576, 2) . " Mb";
	}
}

/**
 * filter
 * 
 * Cleans a string
 *
 * @param string $text
 * @param string $cleanHTML = FALSE
 * @return string $text
 */
function filter($text, $filter = FALSE) {
	if($filter === TRUE) {
		$text = cleanHTML($text);
	} elseif($filter === "escape") {		
		$text = addslashes($text);
		$text = characters($text);
	} else {	
		$text = str_replace("'", "", $text);
		$text = str_replace('"', "", $text);
		$text = str_replace("\\", "", $text);
	}
		
	$text = str_replace("<", "", $text);
	$text = str_replace(">", "", $text);
	$text = str_replace("%27", "", $text);
	$text = str_replace("%22", "", $text);
	$text = str_replace("%20", "", $text);
		
	return $text;
}

function characters($text) {	
	$text = str_replace("ś", "s", $text);	
	$text = str_replace("Ś", "S", $text);	
	$text = str_replace("ẃ", "w", $text);	
	$text = str_replace("Ẃ", "W", $text);	
	$text = str_replace("ŕ", "r", $text);	
	$text = str_replace("Ŕ", "R", $text);	
	$text = str_replace("ý", "y", $text);	
	$text = str_replace("Ý", "Y", $text);	
	$text = str_replace("ṕ", "p", $text);
	$text = str_replace("Ṕ", "P", $text);	
	$text = str_replace("ǵ", "g", $text);	
	$text = str_replace("Ǵ", "G", $text);	
	$text = str_replace("Ĺ", "L", $text);	
	$text = str_replace("ź", "z", $text);	
	$text = str_replace("Ź", "Z", $text);	
	$text = str_replace("ć", "c", $text);	
	$text = str_replace("Ć", "C", $text);	
	$text = str_replace("Ǘ", "u", $text);	
	$text = str_replace("ǘ", "v", $text);	
	$text = str_replace("ń", "n", $text);	
	$text = str_replace("Ń", "N", $text);	
	$text = str_replace("ḿ", "m", $text);	
	$text = str_replace("Ḿ", "M", $text);
	$text = str_replace("¡", "&iexcl;", $text);
	$text = str_replace("¿", "&iquest;", $text);
	
	return $text;
}

function pagebreak($content, $URL = NULL) {
	$content = str_replace("<p><!-- pagebreak --></p>", "<!---->", $content);
	$content = str_replace('<p style="text-align: center;"><!-- pagebreak --></p>', "<!---->", $content);
	$content = str_replace('<p style="text-align: left;"><!-- pagebreak --></p>', "<!---->", $content);
	$content = str_replace('<p style="text-align: right;"><!-- pagebreak --></p>', "<!---->", $content);
	$content = str_replace('<p style="text-align: justify;"><!-- pagebreak --></p>', "<!---->", $content);
	$content = str_replace('<p style="text-align: center;"><span style="color: #ff0000;"><!----></span></p>', "<!---->", $content);
	$content = str_replace('<p style="text-align: center;"><em><!-- pagebreak --></em></p>', "<!---->", $content);
	$content = str_replace('<p style="text-align: center;"><strong><!-- pagebreak --></strong></p>', "<!---->", $content);
	$content = str_replace('<p style="text-align: center;"><span style="text-decoration: underline;"><!-- pagebreak --></span></p>', "<!---->", $content);
	$content = str_replace('<p style="text-align: justify;"><!-- pagebreak --></p>', "<!---->", $content);
	$content = str_replace('<p><!-- pagebreak -->', "<p><!-- pagebreak --></p>\n<p>", $content);
	$content = str_replace("<p><!-- pagebreak --></p>", "<!---->", $content);
	$content = str_replace('<!-- pagebreak -->', "<!---->", $content);		
			
	$parts = explode("<!---->", $content);

	if(count($parts) > 1) {
		return $parts[0] .'<p><a href="'. $URL .'" title="'. __("Read more") .'">&raquo;'. __("Read more") .'...</a></p>';
	}
	
	return $content;		
}

/**
 * encode
 * 
 * Encodes a string and/or a URL
 *
 * @param string $text
 * @param string $URL = FALSE
 * @return string value
 */
function encode($text, $URL = FALSE) {
	return (!$URL) ? utf8_encode($text) : URLencode($text);
}

function decode($text, $URL = FALSE) {
	return (!$URL) ? utf8_decode($text) : URLdecode($text);
}

/**
 * nice
 * 
 * Gets the nice form of a String
 *
 * @param string $title
 * @return string $title
 */
function nice($title) {				
	$title = str_replace("A", "a", $title);	
	$title = str_replace("B", "b", $title);	
	$title = str_replace("C", "c", $title);	
	$title = str_replace("D", "d", $title);	
	$title = str_replace("E", "e", $title);	
	$title = str_replace("F", "f", $title);	
	$title = str_replace("G", "g", $title);	
	$title = str_replace("H", "h", $title);	
	$title = str_replace("I", "i", $title);	
	$title = str_replace("J", "j", $title);	
	$title = str_replace("K", "k", $title);	
	$title = str_replace("L", "l", $title);	
	$title = str_replace("M", "m", $title);	
	$title = str_replace("N", "n", $title);	
	$title = str_replace("Ñ", "n", $title);	
	$title = str_replace("O", "o", $title);	
	$title = str_replace("P", "p", $title);	
	$title = str_replace("Q", "q", $title);	
	$title = str_replace("R", "r", $title);	
	$title = str_replace("S", "s", $title);	
	$title = str_replace("T", "t", $title);	
	$title = str_replace("U", "u", $title);	
	$title = str_replace("V", "v", $title);	
	$title = str_replace("W", "w", $title);	
	$title = str_replace("X", "x", $title);	
	$title = str_replace("Y", "y", $title);	
	$title = str_replace("Z", "z", $title);	
	$title = str_replace("... ", "-", $title);	
	$title = str_replace("##", "", $title);
	$title = str_replace("#", "", $title);
	$title = str_replace("$", "", $title);
	$title = str_replace("%", "", $title);
	$title = str_replace("=", "", $title);
	$title = str_replace("&", "", $title);	
	$title = str_replace("...", "" , $title);	
	$title = str_replace(". ", "-", $title);	
	$title = str_replace("'", "" , $title);	
	$title = str_replace('"', "" , $title);	
	$title = str_replace(".", "-", $title);	
	$title = str_replace("-jpg", ".jpg", $title);	
	$title = str_replace("-jpeg", ".jpeg", $title);	
	$title = str_replace("-gif", ".gif", $title);	
	$title = str_replace("-png", ".png", $title);	
	$title = str_replace("+", "" , $title);	
	$title = str_replace(", ", "-", $title);	
	$title = str_replace(",", "-", $title);	
	$title = str_replace(": ", "-", $title);	
	$title = str_replace(" (", "-", $title);	
	$title = str_replace(") ", "-", $title);	
	$title = str_replace(" - ", "-", $title);
	$title = str_replace("  ", "-", $title);	
	$title = str_replace(" ", "-", $title);		
	$title = str_replace("(", "-", $title);	
	$title = str_replace(")", "-", $title);	
	$title = str_replace("¿", "" , $title);	
	$title = str_replace("?", "" , $title);	
	$title = str_replace("!", "" , $title);	
	$title = str_replace("¡", "" , $title);	
	$title = str_replace("/", "-", $title);	
	$title = str_replace("\\", "", $title);	
	$title = str_replace("&ntilde;", "n", $title);	
	$title = str_replace("&aacute;", "a", $title);	
	$title = str_replace("&eacute;", "e", $title);	
	$title = str_replace("&iacute;", "i", $title);	
	$title = str_replace("&oacute;", "o", $title);	
	$title = str_replace("&uacute;", "u", $title);	
	$title = str_replace("à", "a", $title);
	$title = str_replace("ê", "a", $title);
	$title = str_replace("á", "a", $title);	
	$title = str_replace("é", "e", $title);	
	$title = str_replace("í", "i", $title);	
	$title = str_replace("ó", "o", $title);	
	$title = str_replace("ú", "u", $title);
	$title = str_replace("ñ", "n", $title);	
	$title = str_replace("Á", "a", $title);	
	$title = str_replace("É", "e", $title);	
	$title = str_replace("Í", "i", $title);	
	$title = str_replace("Ó", "o", $title);	
	$title = str_replace("Ú", "u", $title);		
	$title = str_replace("ñ", "n", $title);	
	$title = str_replace("ś", "s", $title);	
	$title = str_replace("Ś", "s", $title);	
	$title = str_replace("ẃ", "w", $title);	
	$title = str_replace("Ẃ", "w", $title);	
	$title = str_replace("ŕ", "r", $title);	
	$title = str_replace("Ŕ", "r", $title);	
	$title = str_replace("ý", "y", $title);	
	$title = str_replace("Ý", "y", $title);	
	$title = str_replace("ṕ", "p", $title);
	$title = str_replace("Ṕ", "p", $title);	
	$title = str_replace("ǵ", "g", $title);	
	$title = str_replace("Ǵ", "g", $title);	
	$title = str_replace("Ĺ", "l", $title);	
	$title = str_replace("ź", "z", $title);	
	$title = str_replace("Ź", "z", $title);	
	$title = str_replace("ć", "c", $title);	
	$title = str_replace("Ć", "c", $title);	
	$title = str_replace("Ǘ", "v", $title);	
	$title = str_replace("ǘ", "v", $title);	
	$title = str_replace("ń", "n", $title);	
	$title = str_replace("Ń", "n", $title);	
	$title = str_replace("ḿ", "m", $title);	
	$title = str_replace("Ḿ", "m", $title);	
	$title = str_replace("“", '"', $title);	
	$title = str_replace("”", '"', $title);
	$title = str_replace("-----------", '', $title);
	$title = str_replace("----------", '', $title);
	$title = str_replace("---------", '', $title);
	$title = str_replace("--------", '', $title);
	$title = str_replace("-------", '', $title);
	$title = str_replace("------", '', $title);
	$title = str_replace("-----", '', $title);
	$title = str_replace("----", '', $title);
	$title = str_replace("---", '', $title);
	$title = str_replace("--", '', $title);
	$title = str_replace("--", '', $title);
	$title = str_replace("|", '', $title);
	$title = str_replace("°", '', $title);
	
	return $title;
}

/**
 * cut
 * 
 * Trims a string
 *
 * @param string $type = "Word"
 * @param string $text
 * @param string $length
 * @param string $nice
 * @param bool $file
 * @param bool   $elipsis 
 * @return string $
 */
function cut($type = "word", $text, $length = 12, $nice = TRUE, $file = FALSE, $elipsis = FALSE) {
	if($type === "text") {
		$elipsis = "...";
		$words   = explode(" ", $text);
				
		if(count($words) > $length) {
			return str_replace("\n", "", implode(" ", array_slice($words, 0, $length)) . $elipsis);
		}
		
		return $text;
	} elseif($type === "word") {
		if($file === TRUE) {
			if(strlen($text) < $length) {
				$max = strlen($text);
			}
			
			if($nice === TRUE) {
				return substr(getNice($text), 0, $length);
			} else {
				return substr($text, 0, $length);			
			}
		} else {
			if(strlen($text) < 13) {
				return $text;
			}
			
			if($elipsis === FALSE) {
				if($nice === TRUE) {
					return substr(getNice($text), 0, $length);
				} else {
					return substr($text, 0, $length);
				}
			} else {
				if($nice === TRUE) {
					return substr(getNice($text), 0, $length) . $elipsis;
				} else {
					return substr($text, 0, $length) . $elipsis;			
				}
			}
		}
	}
}
