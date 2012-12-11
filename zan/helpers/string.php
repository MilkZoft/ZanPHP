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
function BBCode($text) {
	$text = trim($text);

	if(!function_exists("escape")) {
		function escape($s) {
			global $text;

			$code = $s[1];
			$code = str_replace("[", "&#91;", $code);
			$code = str_replace("]", "&#93;", $code);
			
			return getCode($code);
		}	
	}

	$text = preg_replace_callback('/\[code\](.*?)\[\/code\]/ms', "escape", $text);
	
	$in = array(
		'/\[b\](.*?)\[\/b\]/ms',	
		'/\[i\](.*?)\[\/i\]/ms',
		'/\[u\](.*?)\[\/u\]/ms',
		'/\[img\](.*?)\[\/img\]/ms',
		'/\[video\](.*?)\[\/video\]/ms',
		'/\[email\](.*?)\[\/email\]/ms',
		'/\[url\="?(.*?)"?\](.*?)\[\/url\]/ms',
		'/\[size\="?(.*?)"?\](.*?)\[\/size\]/ms',
		'/\[color\="?(.*?)"?\](.*?)\[\/color\]/ms',
		'/\[quote](.*?)\[\/quote\]/ms',
		'/\[list\=(.*?)\](.*?)\[\/list\]/ms',
		'/\[list\](.*?)\[\/list\]/ms',
		'/\[\*\]\s?(.*?)\n/ms'
	);
	
	$out = array(	 
		'<strong>\1</strong>',
		'<em>\1</em>',
		'<u>\1</u>',
		'<img src="\1" alt="\1" />',
		'<iframe width="560" height="315" src="$1" allowfullscreen></iframe>',
		'<a href="mailto:\1">\1</a>',
		'<a href="\1">\2</a>',
		'<span style="font-size:\1%">\2</span>',
		'<span style="color:\1">\2</span>',
		'<blockquote>\1</blockquote>',
		'<ol start="\1">\2</ol>',
		'<ul>\1</ul>',
		'<li>\1</li>'
	);

	$text = preg_replace($in, $out, $text);

	$text = str_replace("http://www.youtube.com/watch?v=", "http://www.youtube.com/embed/", $text);
	$text = str_replace("&amp;list=UUWDzmLpJP-z4qopWVA4qfTQ", "", $text);
	$text = str_replace("&amp;index=1", "", $text);
	$text = str_replace("&amp;index=2", "", $text);
	$text = str_replace("&amp;index=3", "", $text);
	$text = str_replace("&amp;index=4", "", $text);
	$text = str_replace("&amp;index=5", "", $text);
	$text = str_replace("&amp;index=6", "", $text);
	$text = str_replace("&amp;index=7", "", $text);
	$text = str_replace("&amp;index=8", "", $text);
	$text = str_replace("&amp;index=9", "", $text);
	$text = str_replace("&amp;feature=plcp", "", $text);
	$text = str_replace("&amp;feature=related", "", $text);
	$text = str_replace("&amp;feature=player_embedded", "", $text);
	$text = str_replace("&amp;feature=fvwrel", "", $text);
	$text = nl2br($text);
	$text = str_replace("</span><br />", "</span>", $text);
	$text = str_replace("</span>\r\n<br />", "</span>", $text);
	$text = str_replace("</blockquote>\r\n<br />", "</blockquote>", $text);
	$text = str_replace("<ul><br />", "<ul>", $text); 
	$text = str_replace("</ul><br />", "</ul>", $text);
	$text = str_replace("</ol><br />", "</ol>", $text);
	$text = str_replace("<br />\n</li>", "</li>", $text);
	$text = str_replace("</blockquote><br />", "</blockquote>", $text);
	$text = str_replace("<span style=\"color: #000000\"><br />", "<span style=\"color: #000000\">", $text);
	$text = str_replace("</code><br />", "</code>", $text);

	if(!function_exists('removeBr')) {
		function removeBr($s) {
			return str_replace("<br />", "", $s[0]);
		}
	}

	$text = preg_replace_callback('/<pre class="code">(.*?)<\/pre>/ms', "removeBr", $text);
	$text = preg_replace('/<p><pre>(.*?)<\/pre><\/p>/ms', "<pre>\\1</pre>", $text);
	
	$text = preg_replace_callback('/<ul>(.*?)<\/ul>/ms', "removeBr", $text);
	$text = preg_replace('/<p><ul>(.*?)<\/ul><\/p>/ms', "<ul>\\1</ul>", $text);
	
	return $text;
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
	$search = array(
		'@<script[^>]*?>.*?</script>@si',
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
		'@&#(\d+);@e'
	);
	
	$replace = array(
		'',
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
		'chr(\1)'
	);
	
	return preg_replace($search, $replace, $HTML);
}

/**
 * compress
 *
 * Compresses a string
 * 
 * @param string $text
 * @return string $text
 */ 
function compress($string, $filetype = "php") {
	if($filetype === "php") {
		$string = str_replace("<?php\r", "<?php ", $string);
	    return str_replace(array("\r\n", "\r", "\n", "\t", "  ", "    ", "    "), "", $string);      
	} else {
		global $Load;

		if($filetype === "css") {
			$Load->library("cssmin", NULL, NULL, "minify");
			return CSSMin::minify($string);
		} elseif($filetype === 'js') {
			$Load->library("jsmin", NULL, NULL, "minify");
			return JSMin::minify($string);
		}
	}

	return '';
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

function createURL($text)  {     
    $result = ' '. $text; 
    $result = preg_replace("#([\t\r\n ])([a-z0-9]+?){1}://([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*)?)#i", '\1<a href="\2://\3" target="_blank">\2://\3</a>', $result); 
    $result = preg_replace("#([\t\r\n ])(www|ftp)\.(([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*)?)#i", '\1<a href="http://\2.\3" target="_blank">\2.\3</a>', $result); 
    $result = preg_replace("#([\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $result); 

    return substr($result, 1); 
}

function display($content = NULL, $environment = TRUE, $language = TRUE) {
	if($content and ($environment === TRUE or _get("environment") === $environment) and $language === TRUE) {
		return $content;
	} elseif($content and ($environment === TRUE or _get("environment") === $environment) and $language === whichLanguage()) {
		return $content;
	}

	return NULL;
}

function exploding($string, $URL = NULL, $separator = ",") {
	if(strlen($string) > 0) {
		$string = str_replace(", ", ",", $string);
		$parts  = explode($separator, $string);
		$count  = count($parts) - 1;
		$return = NULL;

		if($count > 0) {
			for($i = 0; $i <= $count; $i++) {
				if(!is_null($URL)) {
					if($i === $count) {
						$return .= '<a href="'. path($URL . slug($parts[$i])) .'" title="'. $parts[$i] .'">'. $parts[$i] .'</a>';
					} elseif($i === $count - 1) {
						$return .= '<a href="'. path($URL . slug($parts[$i])) .'" title="'. $parts[$i] .'">'. $parts[$i] .'</a> '. __("and") .' ';
					} else {
						$return .= '<a href="'. path($URL . slug($parts[$i])) .'" title="'. $parts[$i] .'">'. $parts[$i] .'</a>, ';
					}
				} else {
					if($i === $count) {
						$return .= $parts[$i];
					} elseif($i === $count - 1) {
						$return .= $parts[$i] .' '. __("and") .' ';
					} else {
						$return .= $parts[$i] .', ';
					}
				}
			}

			return $return;
		} else {
			return '<a href="'. path($URL . slug($string)) .'" title="'. $string .'">'. $string .'</a>';
		}
	}

	return FALSE;
}

function like($ID = 0, $application = NULL, $likes = FALSE) {
	$likes = ($likes) ? " ($likes)" : NULL;

	if($ID > 0 and !is_null($application)) {
		return  '<a title="'. __("I Like") .'" href="'. path("$application/like/$ID") .'"><img src="'. path("www/lib/images/like.png", TRUE) .'" /> '. __("I Like") . $likes .'</a>';
	}

	return FALSE;
}

function dislike($ID = 0, $application = NULL, $dislikes = FALSE) {
	$dislikes = ($dislikes) ? " ($dislikes)" : NULL;

	if($ID > 0 and !is_null($application)) {
		return '<a title="'. __("I Dislike") .'" href="'. path("$application/dislike/$ID") .'"><img src="'. path("www/lib/images/dislike.png", TRUE) .'" /> '. __("I Dislike") . $dislikes .'</a>';
	}

	return FALSE;
}

function report($ID = 0, $application = NULL) {
	if($ID > 0 and !is_null($application)) {
		return '<a title="'. __("Report Link") .'" href="'. path("$application/report/$ID") .'"><img src="'. path("www/lib/images/report.png", TRUE) .'" /> '. __("Report link") .'</a>';
	}

	return FALSE;
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

function fbComments($URL, $count = FALSE, $posts = 50, $width = 750) {
	if($count) {
		return '<div class="fb-comments-count" data-href="'. $URL .'"></div> <span data-singular="'. __("comment") .'">'. __("comments") .'</span>';
	} else {
		return '<div class="fb-comments" data-href="'. $URL .'" data-num-posts="'. $posts .'" data-width="'. $width .'"></div>';
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
	if(is_null($text)) {
		return FALSE;
	} 
	
	if($text === TRUE) {
		return TRUE;
	} elseif($filter === TRUE) {
		$text = cleanHTML($text);		
	} elseif($filter === "remove") { 
		$text = str_replace("\'", "", $text);
		$text = str_replace('\"', "", $text);
		$text = str_replace("'", "", $text);
		$text = str_replace('"', "", $text);
	} else {	
		$text = addslashes($text);
	}
	
	$text = str_replace("<", "", $text);
	$text = str_replace(">", "", $text);
	$text = str_replace("%27", "", $text);
	$text = str_replace("%22", "", $text);
	$text = str_replace("%20", "", $text);
	$text = str_replace("indexphp", "index.php", $text);
		
	return $text;
}

function quotes($text) {
	$text = str_replace("?s", "'s", $text);
	$text = str_replace("?m", "'m", $text);
	$text = str_replace("?t", "'t", $text);
	$text = str_replace("s?", "s", $text); 

	return stripslashes($text);
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
	return ((int) $count === 0 or (int) $count > 1) ? (int) $count ." ". __($plural) : (int) $count ." ". __($singular);
}

function gravatar($email) {  
   	return img("http://www.gravatar.com/avatar/". md5($email) ."");
}

function json($json, $encode = TRUE) {
	return ($encode) ? json_encode($json) : json_decode($json);
}

function parseCSV($file) {
	$fh = fopen($file, "r");
	
	while($line = fgetcsv($fh, 1000, ",")) {
	    print $line[1];
	}
}

/**
 * pathToImages
 *
 * Add path to db images
 * 
 * @author Daniel Chaur (@hasdman)
 * @param string $HTML
 * @param string $imagePath
 * @return string $HTML
 */ 
function pathToImages($HTML = NULL, $imagePath = NULL) {
	if($HTML and $imagePath) {
		$newPath = ($imagePath === "lib") ? path("www/lib/images/", TRUE) : path("www/lib/themes/$imagePath/", TRUE);

		$patterns = array(
			'<img.*src="([^http].*?)".*?>',
			'/<a(.*)href="([^http].*\.(jpg|gif|png))"(.*)>(.*?)<\/a>/',
			'/url\(\'?([^\'\)]+)\'?\)/m'
		);

		$replacements = array(
			'img src="'. $newPath .'\1" ', 
			'<a$1href="'. $newPath .'$2"$4>$5</a> ',
			'url('. $newPath .'\1) '
		);
		
		$HTML = preg_replace($patterns, $replacements, $HTML);	
		
		return $HTML;
	}

	return FALSE;
}

function getCode($code) {
    if(!is_array($code)) {
    	$code = explode("\n", $code);
    }

    $result = NULL;

    foreach($code as $line => $codeLine) {
        if(preg_match("/<\?(php)?[^[:graph:]]/", $codeLine)) {
            $result .= highlight_string($codeLine, TRUE) ."<br />";
        } else {
            $result .= preg_replace("/(&lt;\?php&nbsp;)+/", "", highlight_string("<?php ". $codeLine, TRUE)) ."<br />";
        }
    }

    return '<div class="code">'. $result .'</div>';
}

function showContent($content) {
	$content = str_replace('<hr />', "", $content);
	$content = str_replace('<hr>', "", $content);
	$content = str_replace("------", "", $content);
	$content = stripslashes($content);
	
	return setCode($content, TRUE, TRUE);		
}

function setCode($HTML, $return = FALSE) {
	$HTML  = str_replace("[Code]", "[code]", $HTML);
	$HTML  = str_replace("[/Code]", "[/code]", $HTML);
	$HTML  = preg_replace('/<pre[^>]*>/ms', "[code]", $HTML);
	$HTML  = preg_replace('/<\/pre>/ms', "[/code]", $HTML);

   	$codes = explode("[code]", $HTML);

   	if(count($codes) > 1) {
   		for($i = 1; $i <= count($codes) - 1; $i++) {
   			if(isset($codes[$i])) {
				$code = explode("[/code]", $codes[$i]);

		   		if(isset($code[0])) {
		   			$code[0] = ($return) ? stripslashes(getCode($code[0])) : $code[0];	
		   		}

		   		$codes[$i] = ($return) ? implode("", $code) : implode("[/code]", $code);		   		
		   	}	
	   	}
   	} 	
   	
   	return ($return) ? implode("", $codes) : implode("[code]", $codes);
}

function randomString($length = 6) {  
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
	$characters = array(
		"Á" => "A", "Ç" => "c", "É" => "e", "Í" => "i", "Ñ" => "n", "Ó" => "o", "Ú" => "u", 
		"á" => "a", "ç" => "c", "é" => "e", "í" => "i", "ñ" => "n", "ó" => "o", "ú" => "u",
		"à" => "a", "è" => "e", "ì" => "i", "ò" => "o", "ù" => "u", "ã" => "a", "¿" => "", 
		"?" =>  "", "¡" =>  "", "!" =>  "", ": " => "-"
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
	$content = str_replace('<!-- Pagebreak -->', "<!---->", $content);
	$content = str_replace('<!--Pagebreak-->', "<!---->", $content);
	$content = str_replace('------', "<!---->", $content);
	$content = str_replace('<hr />', "<!---->", $content);
	$content = str_replace('<hr>', "<!---->", $content);
			
	$parts = explode("<!---->", $content);

	if(count($parts) > 1) {
		return $parts[0] .'<p><a href="'. $URL .'" title="'. __("Read more") .'">&raquo; '. __("Read more") .'...</a></p>';
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
	global $Load;

	if($coding === "clean") {
		return $_POST[$position];
	} elseif($position === TRUE) {		
		return $_POST;
	} elseif(!$position) {
		$Load->helper("debugging");

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
			} elseif($filter === NULL) {
				$POST = decode($_POST[$position]);
			} else { 
				$data = decode($_POST[$position]);
				$data = str_replace("'", "\'", $data);
				$data = str_replace("“", '"', $data);
				$data = str_replace("”", '"', $data);
				
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
	} elseif(isset($_POST[$position]) and $_POST[$position] === 0) {
		return 0;
	} else {
		return FALSE;
	}

	return $POST;
}

function GET($position = FALSE, $coding = "decode", $filter = "escape") {
	global $Load;

	if($coding === "clean") {
		return $_GET[$position];
	} elseif($position === TRUE) {		
		return $_GET;
	} elseif(!$position) {
		$Load->helper("debugging");

		____($_GET);
	} elseif(isset($_GET[$position]) and is_array($_GET[$position])) {
		$GET = $_GET[$position];
	} elseif(isset($_GET[$position]) and $_GET[$position] === "") {
		return NULL;
	} elseif(isset($_GET[$position])) {
		if($coding === "b64") {
			$GET = base64_decode($_GET[$position]);
		} elseif($coding === "unserialize") {
			$GET = unserialize(base64_decode($_GET[$position]));
		} elseif($coding === "encrypt") {
			if($filter === TRUE) {
				$GET = encrypt(encode($_GET[$position]));
			} elseif($filter === "escape") {
				$GET = encrypt(filter(encode($_GET[$position]), "escape"));
			} else {
				$GET = encrypt(filter(encode($_GET[$position]), TRUE));
			}
		} elseif($coding === "encode") {
			if($filter === TRUE) {
				$GET = encode($_GET[$position]);
			} elseif($filter === "escape") {
				$GET = filter(encode($_GET[$position]), "escape");
			}  else {
				$GET = filter(encode($_GET[$position]), TRUE);
			}
		} elseif($coding === "decode-encrypt") {
			if($filter === TRUE) {
				$GET = encrypt(filter($_GET[$position], TRUE));
			} elseif($filter === "escape") {
				$GET = encrypt(filter($_GET[$position], "escape"));
			}  else {
				$GET = encrypt($_GET[$position]);
			}		
		} elseif($coding === "decode") {	
			if($filter === TRUE) {
				$GET = filter(decode($_GET[$position]), TRUE);
			} elseif($filter === "escape") {
				$GET = filter(decode($_GET[$position]), "escape");
			} elseif($filter === NULL) {
				$GET = decode($_GET[$position]);
			} else { 
				$data = decode($_GET[$position]);
				$data = str_replace("'", "\'", $data);
				
				$GET = $data;
			}
		} else {
			if($filter === TRUE) {
				$GET = filter($_GET[$position], TRUE);
			} elseif($filter === "escape") {
				$GET = filter($_GET[$position], "escape");
			}  else {
				$GET = $_GET[$position];
			}		
		}	
	} elseif(isset($_GET[$position]) and $_GET[$position] === 0) {
		return 0;
	} else {
		return FALSE;
	}

	return $GET;
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
		return (is_array(POST($position))) ? POST($position) : (POST($position) ? htmlentities(POST($position, "decode", FALSE)) : NULL);
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

function social($URL, $content, $facebook = TRUE, $twitter = TRUE, $gPlus = TRUE, $linkedin = TRUE, $float = FALSE) {
	$float = ($float) ? " float-right" : NULL;

	$HTML  = '<div class="social'. $float .'">';
	$HTML .= ($facebook) ? ' <div class="fb-like" data-href="'. $URL .'" data-send="true" data-layout="button_count" data-width="100" data-show-faces="true" data-font="lucida grande"></div>' : "";
	$HTML .= ($twitter)  ? ' <a href="https://twitter.com/share" class="twitter-share-button" data-url="'. $URL .'" data-text="'. $content .'" data-via="'. _via .'" data-lang="'. _get("webLang") .'">Tweet</a>' : "";
	$HTML .= ($gPlus)    ? ' <div class="g-plusone" data-size="medium" data-href="'. $URL .'"></div>' : "";
	$HTML .= ($linkedin) ? ' <script type="IN/Share" data-url="'. $URL .'" data-counter="right"></script>' : "";
	$HTML .= '</div>';

	return $HTML;
}

function showLinks($content) {
	return preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1" target="_blank">$1</a>', $content);
}