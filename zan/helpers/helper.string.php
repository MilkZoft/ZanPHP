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

function bbCode($HTML) {
   	$a = array( 
		"/\[Video: (.*?)\]/is"
   	); 

   	$b = array(
   		"<iframe width=\"560\" height=\"315\" src=\"$1\" frameborder=\"0\" allowfullscreen></iframe>"  
   	);

   	$HTML = preg_replace($a, $b, $HTML);
	$HTML = str_replace("http://www.youtube.com/watch?v=", "http://www.youtube.com/embed/", $HTML);
	$HTML = str_replace("&amp;feature=related", "", $HTML);
	$HTML = str_replace("&amp;feature=player_embedded", "", $HTML);
	$HTML = str_replace("&amp;feature=fvwrel", "", $HTML);

	return $HTML;
}

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
function cut($text, $length = 12, $type = "text", $slug = FALSE, $file = FALSE, $elipsis = FALSE) {
	if($type === "text") {
		$elipsis = "...";
		$words   = explode(" ", $text);
				
		if(count($words) > $length) {
			return str_replace("\n", "", implode(" ", array_slice($words, 0, $length)) . $elipsis);
		}
		
		return $text;
	} elseif($type === "word") {
		if($file) {
			if(strlen($text) < $length) {
				$max = strlen($text);
			}
			
			if($slug) {
				return substr(slug($text), 0, $length);
			} else {
				return substr($text, 0, $length);			
			}
		} else {
			if(strlen($text) < 13) {
				return $text;
			}
			
			if(!$elipsis) {
				if($slug) {
					return substr(slug($text), 0, $length);
				} else {
					return substr($text, 0, $length);
				}
			} else {
				if($slug) {
					return substr(slug($text), 0, $length) . $elipsis;
				} else {
					return substr($text, 0, $length) . $elipsis;			
				}
			}
		}
	}
}

function decode($text, $URL = FALSE) {
	if(is_string($text)) {
		return (!$URL) ? utf8_decode($text) : urldecode($text);	
	}

	return $text;
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
	return (!$URL) ? utf8_encode($text) : urlencode($text);
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
	$text = str_replace("indexphp", "index.php", $text);
		
	return $text;
}

function getBetween($content, $start, $end) {
    $array = explode($start, $content);

    if(isset($array[1])) {
        $array = explode($end, $array[1]);

        return $array[0];
    }

    return NULL;
}

function getTotal($count, $singular, $plural) {
	if((int) $count === 0) {
		return (int) $count ." ". __($plural);
	} elseif((int) $count === 1) {
		return (int) $count ." ". __($singular);
	} else {
		return (int) $count ." ". __($plural);
	}
}

function gravatar($email) {  
   	return img("http://www.gravatar.com/avatar/". md5($email) ."");
}

function parseCSV($file) {
	$fh = fopen($file, "r");
	
	while($line = fgetcsv($fh, 1000, ",")) {
	    print $line[1];
	}
}

function randomString($length = 6){  
    $consonant = array("b", "c", "d", "f", "g", "h", "j", "k", "l", "m", "n", "p", "r", "s", "t", "v", "w", "x", "y", "z");  
    $vocal	   = array("a", "e", "i", "o", "u");  
    $string    = NULL;  
    
    srand((double) microtime() * 1000000);  
    
    $max = $length / 2;  

    for($i = 1; $i <= $max; $i++) {  
    	$string .= $consonant[rand(0, 19)];  
    	$string .= $vocal[rand(0, 4)];  
    }  

    return $string;  
} 

function repeat($string, $times = 2) {
	$HTML = NULL;
	
	for($i = 0; $i <= $times; $i++) {
		$HTML .= $string;
	}

	return $HTML;
}

/**
 * nice
 * 
 * Gets the nice form of a String
 *
 * @param string $title
 * @return string $title
 */
function slug($string) {		
	$characters = array("Á" => "A", "Ç" => "c", "É" => "e", "Í" => "i", "Ñ" => "n", "Ó" => "o", "Ú" => "u", 
						"á" => "a", "ç" => "c", "é" => "e", "í" => "i", "ñ" => "n", "ó" => "o", "ú" => "u",
						"à" => "a", "è" => "e", "ì" => "i", "ò" => "o", "ù" => "u"
	);
	
	$string = strtr($string, $characters); 
	$string = strtolower(trim($string));
	$string = preg_replace("/[^a-z0-9-]/", "-", $string);
	$string = preg_replace("/-+/", "-", $string);
	
	if(substr($string, strlen($string) - 1, strlen($string)) === "-") {
		$string = substr($string, 0, strlen($string) - 1);
	}
	
	return $string;
}

function pageBreak($content, $URL = NULL) {
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
 * POST
 * 
 * Gets a specific position value from $_POST
 * 
 * @param string $position
 * @param string $coding   = "decode"
 * @return mixed
 */ 
function POST($position = FALSE, $coding = "decode", $filter = "escape") {
	if($coding === "clean") {
		return $_POST[$position];
	} elseif($position === TRUE) {		
		return $_POST;
	} elseif(!$position) {
		____($_POST);
	} elseif(isset($_POST[$position]) and is_array($_POST[$position])) {
		$POST = $_POST[$position];
	} elseif(isset($_POST[$position]) and $_POST[$position] === "") {
		return NULL;
	} elseif(isset($_POST[$position])) {
		if($coding === "b64") {
			$POST = base64_decode($_POST[$position]);
		} elseif($coding === "unserialize") {
			$POST = unserialize(base64_decode($_POST[$position]));
		} elseif($coding === "encrypt") {
			if($filter === TRUE) {
				$POST = encrypt(encode($_POST[$position]));
			} elseif($filter === "escape") {
				$POST = encrypt(filter(encode($_POST[$position]), "escape"));
			} else {
				$POST = encrypt(filter(encode($_POST[$position]), TRUE));
			}
		} elseif($coding === "encode") {
			if($filter === TRUE) {
				$POST = encode($_POST[$position]);
			} elseif($filter === "escape") {
				$POST = filter(encode($_POST[$position]), "escape");
			}  else {
				$POST = filter(encode($_POST[$position]), TRUE);
			}
		} elseif($coding === "decode-encrypt") {
			if($filter === TRUE) {
				$POST = encrypt(filter($_POST[$position], TRUE));
			} elseif($filter === "escape") {
				$POST = encrypt(filter($_POST[$position], "escape"));
			}  else {
				$POST = encrypt($_POST[$position]);
			}		
		} elseif($coding === "decode") {			
			if($filter === TRUE) {
				$POST = filter(decode($_POST[$position]), TRUE);
			} elseif($filter === "escape") {
				$POST = filter(decode($_POST[$position]), "escape");
			} else {
				$data = decode($_POST[$position]);
				$data = str_replace("'", "\'", $data);
				
				$POST = $data;
			}
		} else {
			if($filter === TRUE) {
				$POST = filter($_POST[$position], TRUE);
			} elseif($filter === "escape") {
				$POST = filter($_POST[$position], "escape");
			}  else {
				$POST = $_POST[$position];
			}		
		}
	} else {
		return FALSE;
	}
	
	if(isInjection($POST)) {
		return FALSE;
	} else {
		return $POST;
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
		if(is_array(POST($position))) {
			return POST($position);
		} else {
			return (POST($position)) ? htmlentities(POST($position, "decode", FALSE)) : NULL;
		}
	} else {
		if(is_array($value)) {
			foreach($value as $val) {
				$data[] = htmlentities($val);
			}
			
			return $data;
		} else {
			return (POST($position)) ? htmlentities(POST($position, "decode", FALSE)) : htmlentities(decode($value));
		}	
	}
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
	$text = preg_replace("/\s+/", " ", $text);
	
	if($trim) {
		return trim($text);
	}
	
	return $text;
}
