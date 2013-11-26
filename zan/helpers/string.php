<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

if (!function_exists("bbCode")) {
	function bbCode($text) {
		$text = trim($text);
		$text = preg_replace_callback('/\[code\](.*?)\[\/code\]/ms', "escape", $text);
		
		$in  = array(
			'/\[b\](.*?)\[\/b\]/ms', '/\[i\](.*?)\[\/i\]/ms', '/\[u\](.*?)\[\/u\]/ms', '/\[img\](.*?)\[\/img\]/ms', '/\[video\](.*?)\[\/video\]/ms', 
			'/\[email\](.*?)\[\/email\]/ms', '/\[url\="?(.*?)"?\](.*?)\[\/url\]/ms', '/\[size\="?(.*?)"?\](.*?)\[\/size\]/ms', 
			'/\[color\="?(.*?)"?\](.*?)\[\/color\]/ms', '/\[quote](.*?)\[\/quote\]/ms', '/\[list\=(.*?)\](.*?)\[\/list\]/ms', 
			'/\[list\](.*?)\[\/list\]/ms', '/\[\*\]\s?(.*?)\n/ms'
		);		
		
		$out = array(
			'<strong>\1</strong>', '<em>\1</em>', '<u>\1</u>', '<img src="\1" alt="\1" />', 
			'<iframe width="560" height="315" src="$1" allowfullscreen></if rame>', '<a href="mailto:\1">\1</a>', 
			'<a href="\1">\2</a>', '<span style="font-size:\1%">\2</span>', '<span style="color:\1">\2</span>', 
			'<blockquote>\1</blockquote>', '<ol start="\1">\2</ol>', '<ul>\1</ul>', '<li>\1</li>'
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
		$text = preg_replace_callback('/<pre class="code">(.*?)<\/pre>/ms', "removeBr", $text);
		$text = preg_replace('/<p><pre>(.*?)<\/pre><\/p>/ms', "<pre>\\1</pre>", $text);
		$text = preg_replace_callback('/<ul>(.*?)<\/ul>/ms', "removeBr", $text);
		$text = preg_replace('/<p><ul>(.*?)<\/ul><\/p>/ms', "<ul>\\1</ul>", $text);
		return $text;
	}
}

if (!function_exists("cleanHTML")) {
	function cleanHTML($HTML)
	{
		$search = array(
			'@<script[^>]*?>.*?</script>@si', '@<[\/\!]*?[^<>]*?>@si', '@([\r\n])[\s]+@', '@&(quot|#34);@i', '@&(amp|#38);@i', 
			'@&(lt|#60);@i', '@&(gt|#62);@i', '@&(nbsp|#160);@i', '@&(iexcl|#161);@i', '@&(cent|#162);@i', '@&(pound|#163);@i', 
			'@&(copy|#169);@i', '@&#(\d+);@e'
		);		
		
		$replace = array('', '', '\1', '"', '&', '<', '>', ' ', chr(161), chr(162), chr(163), chr(169), 'chr(\1)');		
		
		return preg_replace($search, $replace, $HTML);
	}
}

if (!function_exists("compress")) {
	function compress($string, $filetype = "php") 
	{
		if ($filetype === "php") {
			$string = str_replace("<?php\r", "<?php ", $string);
		    
		    return str_replace(array("\r\n", "\r", "\n", "\t", "  ", "    ", "    "), "", $string);      
		} else {
			global $Load;

			if ($filetype === "css") {
				$Load->library("cssmin", null, null, "minify");
				
				return CSSMin::minify($string);
			} elseif ($filetype === 'js') {
				$Load->library("jsmin", null, null, "minify");

				return JSMin::minify($string);
			}
		}

		return null;
	}
}

if (!function_exists("cut")) {
	function cut($text, $length = 12, $type = "text", $slug = false, $file = false, $elipsis = false)
	{
		if ($type === "text") {
			$elipsis = "...";
			$words   = explode(" ", $text);
					
			if (count($words) > $length) {
				return str_replace("\n", "", implode(" ", array_slice($words, 0, $length)) . $elipsis);
			}
			
			return $text;
		} elseif ($type === "word") {
			if ($file) {
				if (strlen($text) < $length) {
					$max = strlen($text);
				}
				
				if ($slug) {
					return substr(slug($text), 0, $length);
				} else {
					return substr($text, 0, $length);			
				}
			} else {
				if (strlen($text) < 13) {
					return $text;
				}
				
				if (!$elipsis) {
					if ($slug) {
						return substr(slug($text), 0, $length);
					} else {
						return substr($text, 0, $length);
					}
				} else {
					if ($slug) {
						return substr(slug($text), 0, $length) . $elipsis;
					} else {
						return substr($text, 0, $length) . $elipsis;			
					}
				}
			}
		}
	}
}

if (!function_exists("createURL")) {
	function createURL($text)
	{     
	    $result = ' '. $text; 
	    $result = preg_replace("#([\t\r\n ])([a-z0-9]+?){1}://([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*)?)#i", '\1<a href="\2://\3" target="_blank">\2://\3</a>', $result); 
	    $result = preg_replace("#([\t\r\n ])(www|ftp)\.(([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*)?)#i", '\1<a href="http://\2.\3" target="_blank">\2.\3</a>', $result); 
	    $result = preg_replace("#([\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $result); 
	    return substr($result, 1); 
	}
}

if (!function_exists("display")) {
	function display($content = null, $environment = true, $language = true)
	{
		if ($content and ($environment === true or _get("environment") === $environment) and $language === true) {
			return $content;
		} elseif ($content and ($environment === true or _get("environment") === $environment) and $language === whichLanguage()) {
			return $content;
		}

		return null;
	}
}

if (!function_exists("escape")) {
	function escape($string) {
		global $text;
		$code = $string[1];
		$code = str_replace("[", "&#91;", $code);
		$code = str_replace("]", "&#93;", $code);
		return getCode($code);
	}	
}

if (!function_exists("exploding")) {
	function exploding($string, $URL = null, $separator = ",")
	{
		if (strlen($string) > 0) {
			$string = str_replace(", ", ",", $string);
			$parts  = explode($separator, $string);
			$count  = count($parts) - 1;
			$return = null;

			if ($count > 0) {
				for ($i = 0; $i <= $count; $i++) {
					if (!is_null($URL)) {
						if ($i === $count) {
							$return .= '<a href="'. path($URL . slug($parts[$i])) .'" title="'. $parts[$i] .'">'. $parts[$i] .'</a>';
						} elseif ($i === $count - 1) {
							$return .= '<a href="'. path($URL . slug($parts[$i])) .'" title="'. $parts[$i] .'">'. $parts[$i] .'</a> '. __("and") .' ';
						} else {
							$return .= '<a href="'. path($URL . slug($parts[$i])) .'" title="'. $parts[$i] .'">'. $parts[$i] .'</a>, ';
						}
					} else {
						if ($i === $count) {
							$return .= $parts[$i];
						} elseif ($i === $count - 1) {
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

		return false;
	}
}

if (!function_exists("like")) {
	function like($ID = 0, $application = null, $likes = false)
	{
		if ($ID > 0 and !is_null($application)) {
			return  '<a title="'. __("I Like") .'" href="'. path("$application/like/$ID") .'"><img src="'. path("www/lib/images/like.png", true) .'" /> '. __("I Like") . ($likes ? " ($likes)" : null) .'</a>';
		}

		return false;
	}
}

if (!function_exists("dislike")) {
	function dislike($ID = 0, $application = null, $dislikes = false)
	{
		if ($ID > 0 and !is_null($application)) {
			return '<a title="'. __("I Dislike") .'" href="'. path("$application/dislike/$ID") .'">
						<img src="'. path("www/lib/images/dislike.png", true) .'" /> '. __("I Dislike") . ($dislikes ? " ($dislikes)" : null) .'
					</a>';
		}

		return false;
	}
}

if (!function_exists("report")) {
	function report($ID = 0, $application = null)
	{
		if ($ID > 0 and !is_null($application)) {
			return '<a title="'. __("Report Link") .'" href="'. path("$application/report/$ID") .'">
						<img src="'. path("www/lib/images/report.png", true) .'" /> '. __("Report link") .'
					</a>';
		}

		return false;
	}
}

if (!function_exists("decode")) {
	function decode($text, $URL = false)
	{
		if (is_string($text)) {
			return (!$URL) ? utf8_decode($text) : urldecode($text);	
		}

		return $text;
	}
}

if (!function_exists("encode")) {
	function encode($text, $URL = false)
	{
		return (!$URL) ? utf8_encode($text) : urlencode($text);
	}
}

if (!function_exists("fbComments")) {
	function fbComments($URL, $count = false, $posts = 50, $width = 750)
	{
		if ($count) {
			return '<span class="fb-comments-count" data-href="'. $URL .'"></span> <span data-singular="'. __("comment") .'">'. __("comments") .'</span>'; 
		} else {
			return '<div class="fb-comments" data-href="'. $URL .'" data-num-posts="'. $posts .'" data-width="'. $width .'"></div>';	
		}	
	}
}

if (!function_exists("filter")) {
	function filter($text, $filter = false)
	{
		if (is_null($text)) {
			return false;
		} 
		
		if ($text === true) {
			return true;
		} elseif ($filter === true) {
			$text = cleanHTML($text);		
		} elseif ($filter === "remove") { 
			$text = str_replace("\'", "", $text);
			$text = str_replace('\"', "", $text);
			$text = str_replace("'", "", $text);
			$text = str_replace('"', "", $text);
		} 
		
		$text = str_replace("<", "", $text);
		$text = str_replace(">", "", $text);
		$text = str_replace("%27", "", $text);
		$text = str_replace("%22", "", $text);
		$text = str_replace("%20", "", $text);
		$text = str_replace("indexphp", "index.php", $text);
		return $text;
	}
}

if (!function_exists("quotes")) {
	function quotes($text)
	{
		$text = str_replace("?s", "'s", $text);
		$text = str_replace("?m", "'m", $text);
		$text = str_replace("?t", "'t", $text);
		$text = str_replace("s?", "s", $text); 
		return stripslashes($text);
	}
}

if (!function_exists("getBetween")) {
	function getBetween($content, $start, $end)
	{
	    $array = explode($start, $content);

	    if (isset($array[1])) {
	        $array = explode($end, $array[1]);
	        return $array[0];
	    }

	    return null;
	}
}

if (!function_exists("getTotal")) {
	function getTotal($count, $singular, $plural)
	{
		return ((int) $count === 0 or (int) $count > 1) ? (int) $count ." ". __($plural) : (int) $count ." ". __($singular);
	}
}

if (!function_exists("gravatar")) {
	function gravatar($email)
	{  
	   	return img("http://www.gravatar.com/avatar/". md5($email) ."");
	}
}

if (!function_exists("json")) {
	function json($json, $encode = true)
	{
		return ($encode) ? json_encode($json) : json_decode($json);
	}
}

if (!function_exists("parseCSV")) {
	function parseCSV($file)
	{
		$fh = fopen($file, "r");
		$lines = null;

		while ($line = fgetcsv($fh, 1000, ",")) {
		    $lines .= $line[1];
		}

		return $lines;
	}
}

if (!function_exists("pathToImages")) { 
	function pathToImages($HTML = null, $imagePath = null)
	{
		if ($HTML and $imagePath) {
			$newPath = ($imagePath === "lib") ? path("www/lib/images/", true) : path("www/lib/themes/$imagePath/", true);
			$patterns = array('<img.*src="([^http].*?)".*?>', '/<a(.*)href="([^http].*\.(jpg|gif |png))"(.*)>(.*?)<\/a>/', '/url\(\'?([^\'\)]+)\'?\)/m');
			$replacements = array('img src="'. $newPath .'\1" ', '<a$1href="'. $newPath .'$2"$4>$5</a> ', 'url('. $newPath .'\1) ');			
			return preg_replace($patterns, $replacements, $HTML);
		}

		return false;
	}
}

if (!function_exists("getCode")) {
	function getCode($code)
	{
	    if (!is_array($code)) {
	    	$code = explode("\n", $code);
	    }

	    $result = null;

	    foreach ($code as $line => $codeLine) {
	        if (preg_match("/<\?(php)?[^[:graph:]]/", $codeLine)) {
	        	$codeLine = str_replace('\"', '"', $codeLine);
	        	$codeLine = str_replace("\'", "'", $codeLine);
	            $result .= htmlentities($codeLine) ."<br />";
	        } else {
	        	$codeLine = str_replace('\"', '"', $codeLine);
	        	$codeLine = str_replace("\'", "'", $codeLine);
	            $result .= preg_replace("/(&lt;\?php&nbsp;)+/", "", htmlentities(stripslashes($codeLine)) ."<br />");
	        }
	    }

	    return '<pre class="prettyprint linenums">'. $result .'</pre>';
	}
}

if (!function_exists("getAd")) {
	function getAd($size = "block")
	{
		$size = strtolower($size);

		if ($size === "block" or $size === "336px" or $size === "300px") {
			$ad = '<script type="text/javascript">google_ad_client = "'. AD_CLIENT .'";google_ad_slot = "'. AD_SLOT_BLOCK .'";google_ad_width = '. AD_WIDTH_BLOCK .';google_ad_height = '. AD_HEIGHT_BLOCK .';</script>';
		} elseif ($size === "sky" or $size === "728px") {
			$ad = '<script type="text/javascript">google_ad_client = "'. AD_CLIENT .'";google_ad_slot = "'. AD_SLOT_SKY .'";google_ad_width = '. AD_WIDTH_SKY .';google_ad_height = '. AD_HEIGHT_SKY .';</script>';
		} elseif ($size === "medium" or $size === "234px") {
			$ad = '<script type="text/javascript">google_ad_client = "'. AD_CLIENT .'";google_ad_slot = "'. AD_SLOT_MEDIUM .'";google_ad_width = '. AD_WIDTH_MEDIUM .';google_ad_height = '. AD_HEIGHT_MEDIUM .';</script>';			
		}

		if (isMobile()) {
			$ad = '<script type="text/javascript">google_ad_client = "'. AD_CLIENT .'";google_ad_slot = "'. AD_SLOT_MEDIUM .'";google_ad_width = '. AD_WIDTH_MEDIUM .';google_ad_height = '. AD_HEIGHT_MEDIUM .';</script>';
		}

		if (_get("environment") === 4) {
			return $ad .'<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>';
		}

		return null;
	}
}

if (!function_exists("removeRareChars")) {
	function removeRareChars($content)
	{
		$content = str_replace("%u200B", "", $content);

		return $content;
	}
}

if (!function_exists("showContent")) {
	function showContent($content)
	{
		$content = str_replace('<hr />', "", $content);
		$content = str_replace('<hr>', "", $content);
		$content = str_replace("------", "", $content);	
		$content = str_replace('<pre class="prettyprint">', '<pre class="prettyprint linenums">', $content);	
		$content = str_replace('<UIKit/UIKit.h>', '&lt;UIKit/UIKit.h&gt;', $content);
		$content = str_replace('<CoreLocation/CoreLocation.h>', '&lt;CoreLocation/CoreLocation.h&gt;', $content);	
		$content = str_replace("[Ad:336px]", '<p>'. getAd() .'</p>', $content);
		$content = str_replace("[Ad:728px]", '<p>'. getAd("728px") .'</p>', $content);
		$content = str_replace("[Ad:Block]", '<p>'. getAd() .'</p>', $content);
		$content = str_replace("[Ad:Sky]",   '<p>'. getAd("728px") .'</p>', $content);
		$content = str_replace("[ad:336px]", '<p>'. getAd() .'</p>', $content);
		$content = str_replace("[ad:728px]", '<p>'. getAd("728px") .'</p>', $content);
		$content = str_replace("[Ad:block]", '<p>'. getAd() .'</p>', $content);	
		$content = str_replace('\"', '"', $content);
		$content = str_replace("\'", "'", $content);
		$content = str_replace("<a ", '<a rel="nofollow" ', $content);
		$content = removeRareChars($content);

		return setCode($content);		
	}
}

if (!function_exists("setCode")) {
	function setCode($HTML, $return = false)
	{
		$HTML = str_replace("[Code]", '<pre class="prettyprint linenums">', $HTML);
		$HTML = str_replace("[/Code]", '</pre>', $HTML);
		$HTML = str_replace("[code]", '<pre class="prettyprint linenums">', $HTML);
		$HTML = str_replace("[/code]", '</pre>', $HTML);
	   	$codes = explode('<pre class="prettyprint linenums">', $HTML);

	   	if (count($codes) > 1) {
	   		for ($i = 1; $i <= count($codes) - 1; $i++) {
	   			if (isset($codes[$i])) {
					$code = explode("</pre>", $codes[$i]);

			   		if (isset($code[0])) {
			   			$code[0] = ($return) ? htmlspecialchars(getCode($code[0])) : htmlspecialchars($code[0]);
			   			$code[0] = str_replace("&amp;", "&", $code[0]);  	
						$code[0] = str_replace("&nbsp;", " ", $code[0]);					
			   		}

			   		$codes[$i] = ($return) ? implode("", $code) : implode("</pre>", $code);		   		
			   	}	
		   	}
	   	} 	
	   	
	   	return ($return) ? implode("", $codes) : implode('<pre class="prettyprint linenums">', $codes);	   	
	}
}

if (!function_exists("randomString")) {
	function randomString($length = 6)
	{  
	    $consonant = array("b", "c", "d", "f", "g", "h", "j", "k", "l", "m", "n", "p", "r", "s", "t", "v", "w", "x", "y", "z");  
	    $vocal = array("a", "e", "i", "o", "u");  
	    $string = null;  
	    srand((double) microtime() * 1000000);  
	    $max = $length / 2;  

	    for ($i = 1; $i <= $max; $i++) {  
	    	$string .= $consonant[rand(0, 19)];  
	    	$string .= $vocal[rand(0, 4)];  
	    }  

	    return $string;  
	}
}

if (!function_exists("repeat")) {
	function repeat($string, $times = 2)
	{
		$HTML = null;
		
		for ($i = 0; $i <= $times; $i++) {
			$HTML .= $string;
		}

		return $HTML;
	}
}

if (!function_exists("slug")) {
	function slug($string)
	{		
		$characters = array(
			"Á" => "A", "Ç" => "c", "É" => "e", "Í" => "i", "Ñ" => "n", "Ó" => "o", "Ú" => "u", "á" => "a", "ç" => "c", 
			"é" => "e", "í" => "i", "ñ" => "n", "ó" => "o", "ú" => "u", "à" => "a", "è" => "e", "ì" => "i", "ò" => "o", 
			"ù" => "u", "ã" => "a", "¿" => "", "?" =>  "", "¡" =>  "", "!" =>  "", ": " => "-"
		);		
		
		$string = strtr($string, $characters); 
		$string = strtolower(trim($string));
		$string = preg_replace("/[^a-z0-9-]/", "-", $string);
		$string = preg_replace("/-+/", "-", $string);
		
		return (substr($string, strlen($string) - 1, strlen($string)) === "-") ? substr($string, 0, strlen($string) - 1) : $string;
	}
}

if (!function_exists("pageBreak")) {
	function pageBreak($content, $URL = null, $label = null)
	{
		$content = str_replace("<p><!-- pagebreak --></p>", "<!---->", $content);
		$content = str_replace('<p style="text-align: center;"><!-- pagebreak --></p>', "<!---->", $content);
		$content = str_replace('<p style="text-align: left;"><!-- pagebreak --></p>', "<!---->", $content);
		$content = str_replace('<p style="text-align: right;"><!-- pagebreak --></p>', "<!---->", $content);
		$content = str_replace('<p style="text-align: justif y;"><!-- pagebreak --></p>', "<!---->", $content);
		$content = str_replace('<p style="text-align: center;"><span style="color: #ff0000;"><!----></span></p>', "<!---->", $content);
		$content = str_replace('<p style="text-align: center;"><em><!-- pagebreak --></em></p>', "<!---->", $content);
		$content = str_replace('<p style="text-align: center;"><strong><!-- pagebreak --></strong></p>', "<!---->", $content);
		$content = str_replace('<p style="text-align: center;"><span style="text-decoration: underline;"><!-- pagebreak --></span></p>', "<!---->", $content);
		$content = str_replace('<p style="text-align: justif y;"><!-- pagebreak --></p>', "<!---->", $content);
		$content = str_replace('<p><!-- pagebreak -->', "<p><!-- pagebreak --></p>\n<p>", $content);
		$content = str_replace("<p><!-- pagebreak --></p>", "<!---->", $content);
		$content = str_replace('<!-- pagebreak -->', "<!---->", $content);	
		$content = str_replace('<!-- Pagebreak -->', "<!---->", $content);
		$content = str_replace('<!--Pagebreak-->', "<!---->", $content);
		$content = str_replace('------', "<!---->", $content);
		$content = str_replace('<div style="page-break-after: always;"><span style="display: none;">&nbsp;</span></div>', "<!---->", $content);
		$content = str_replace('<hr />', "<!---->", $content);
		$content = str_replace('<hr>', "<!---->", $content);				
		$parts = explode("<!---->", $content);

		if (count($parts) > 1) {
			if (is_null($URL)) {
				return $parts[0];
			} else {
				return $parts[0] . (is_null($label) ? '<p><a href="'. $URL .'" title="'. __("Read more") .'">&raquo; '. __("Read more") .'...</a></p>' : $label);
			}
		}
		
		return $content;		
	}
}

if (!function_exists("POST")) {
	function POST($position = false, $coding = "decode", $filter = "escape")
	{
		global $Load;

		if ($position !== true and $position !== false) {
			if (isset($_POST[$position])) {
				$_POST[$position] = str_replace("'", "\'", $_POST[$position]);
				$_POST[$position] = str_replace("\\\'", "\'", $_POST[$position]);
				$_POST[$position] = str_replace("“", '"', $_POST[$position]);
				$_POST[$position] = str_replace("”", '"', $_POST[$position]);
			}
		}

		if ($coding === "clean") {
			return isset($_POST[$position]) ? $_POST[$position] : FALSE;
		} elseif ($position === true) {		
			return $_POST;
		} elseif (!$position) {
			$Load->helper("debugging");
			____($_POST);
		} elseif (isset($_POST[$position]) and is_array($_POST[$position])) {
			$POST = $_POST[$position];
		} elseif (isset($_POST[$position]) and $_POST[$position] === "") {
			return null;
		} elseif (isset($_POST[$position])) {
			if ($coding === "b64") {
				$POST = base64_decode($_POST[$position]);
			} elseif ($coding === "unserialize") {
				$POST = unserialize(base64_decode($_POST[$position]));
			} elseif ($coding === "encrypt") {
				if ($filter === true) {
					$POST = encrypt(encode($_POST[$position]));
				} elseif ($filter === "escape") {
					$POST = encrypt(filter(encode($_POST[$position]), "escape"));
				} else {
					$POST = encrypt(filter(encode($_POST[$position]), true));
				}
			} elseif ($coding === "encode") {
				if ($filter === true) {
					$POST = encode($_POST[$position]);
				} elseif ($filter === "escape") {
					$POST = filter(encode($_POST[$position]), "escape");
				}  else {
					$POST = filter(encode($_POST[$position]), true);
				}
			} elseif ($coding === "decode-encrypt") {
				if ($filter === true) {
					$POST = encrypt(filter($_POST[$position], true));
				} elseif ($filter === "escape") {
					$POST = encrypt(filter($_POST[$position], "escape"));
				}  else {
					$POST = encrypt($_POST[$position]);
				}		
			} elseif ($coding === "decode") {	
				if ($filter === true) {
					$POST = filter(decode($_POST[$position]), true);
				} elseif ($filter === "escape") {
					$POST = filter(decode($_POST[$position]), "escape");
				} elseif ($filter === null) {
					$POST = decode($_POST[$position]);
				} else { 
					$data = decode($_POST[$position]);				
					
					$POST = $data;
				}
			} else {
				if ($filter === true) {
					$POST = filter($_POST[$position], true);
				} elseif ($filter === "escape") {
					$POST = filter($_POST[$position], "escape");
				}  else {
					$POST = $_POST[$position];
				}		
			}	
		} elseif (isset($_POST[$position]) and $_POST[$position] === 0) {
			return 0;
		} else {
			return false;
		}

		return $POST;
	}
}

if (!function_exists("GET")) {
	function GET($position = false, $coding = "decode", $filter = "escape")
	{
		global $Load;

		if ($coding === "clean") {
			return $_GET[$position];
		} elseif ($position === true) {		
			return $_GET;
		} elseif (!$position) {
			$Load->helper("debugging");
			____($_GET);
		} elseif (isset($_GET[$position]) and is_array($_GET[$position])) {
			$GET = $_GET[$position];
		} elseif (isset($_GET[$position]) and $_GET[$position] === "") {
			return null;
		} elseif (isset($_GET[$position])) {
			if ($coding === "b64") {
				$GET = base64_decode($_GET[$position]);
			} elseif ($coding === "unserialize") {
				$GET = unserialize(base64_decode($_GET[$position]));
			} elseif ($coding === "encrypt") {
				if ($filter === true) {
					$GET = encrypt(encode($_GET[$position]));
				} elseif ($filter === "escape") {
					$GET = encrypt(filter(encode($_GET[$position]), "escape"));
				} else {
					$GET = encrypt(filter(encode($_GET[$position]), true));
				}
			} elseif ($coding === "encode") {
				if ($filter === true) {
					$GET = encode($_GET[$position]);
				} elseif ($filter === "escape") {
					$GET = filter(encode($_GET[$position]), "escape");
				}  else {
					$GET = filter(encode($_GET[$position]), true);
				}
			} elseif ($coding === "decode-encrypt") {
				if ($filter === true) {
					$GET = encrypt(filter($_GET[$position], true));
				} elseif ($filter === "escape") {
					$GET = encrypt(filter($_GET[$position], "escape"));
				}  else {
					$GET = encrypt($_GET[$position]);
				}		
			} elseif ($coding === "decode") {	
				if ($filter === true) {
					$GET = filter(decode($_GET[$position]), true);
				} elseif ($filter === "escape") {
					$GET = filter(decode($_GET[$position]), "escape");
				} elseif ($filter === null) {
					$GET = decode($_GET[$position]);
				} else { 
					$data = decode($_GET[$position]);
					$data = str_replace("'", "\'", $data);					
					$GET = $data;
				}
			} else {
				if ($filter === true) {
					$GET = filter($_GET[$position], true);
				} elseif ($filter === "escape") {
					$GET = filter($_GET[$position], "escape");
				}  else {
					$GET = $_GET[$position];
				}		
			}	
		} elseif (isset($_GET[$position]) and $_GET[$position] === 0) {
			return 0;
		} else {
			return false;
		}

		return $GET;
	}
}

if (!function_exists("REQUEST")) {
	function REQUEST($position = false, $coding = "decode", $filter = "escape")
	{
		global $Load;

		if ($coding === "clean") {
			return $_REQUEST[$position];
		} elseif ($position === true) {		
			return $_REQUEST;
		} elseif (!$position) {
			$Load->helper("debugging");
			____($_REQUEST);
		} elseif (isset($_REQUEST[$position]) and is_array($_REQUEST[$position])) {
			$REQUEST = $_REQUEST[$position];
		} elseif (isset($_REQUEST[$position]) and $_REQUEST[$position] === "") {
			return null;
		} elseif (isset($_REQUEST[$position])) {
			if ($coding === "b64") {
				$REQUEST = base64_decode($_REQUEST[$position]);
			} elseif ($coding === "unserialize") {
				$REQUEST = unserialize(base64_decode($_REQUEST[$position]));
			} elseif ($coding === "encrypt") {
				if ($filter === true) {
					$REQUEST = encrypt(encode($_REQUEST[$position]));
				} elseif ($filter === "escape") {
					$REQUEST = encrypt(filter(encode($_REQUEST[$position]), "escape"));
				} else {
					$REQUEST = encrypt(filter(encode($_REQUEST[$position]), true));
				}
			} elseif ($coding === "encode") {
				if ($filter === true) {
					$REQUEST = encode($_REQUEST[$position]);
				} elseif ($filter === "escape") {
					$REQUEST = filter(encode($_REQUEST[$position]), "escape");
				}  else {
					$REQUEST = filter(encode($_REQUEST[$position]), true);
				}
			} elseif ($coding === "decode-encrypt") {
				if ($filter === true) {
					$REQUEST = encrypt(filter($_REQUEST[$position], true));
				} elseif ($filter === "escape") {
					$REQUEST = encrypt(filter($_REQUEST[$position], "escape"));
				}  else {
					$REQUEST = encrypt($_REQUEST[$position]);
				}		
			} elseif ($coding === "decode") {	
				if ($filter === true) {
					$REQUEST = filter(decode($_REQUEST[$position]), true);
				} elseif ($filter === "escape") {
					$REQUEST = filter(decode($_REQUEST[$position]), "escape");
				} elseif ($filter === null) {
					$REQUEST = decode($_REQUEST[$position]);
				} else { 
					$data = decode($_REQUEST[$position]);
					$data = str_replace("'", "\'", $data);
					$data = str_replace("“", '"', $data);
					$data = str_replace("”", '"', $data);					
					$REQUEST = $data;
				}
			} else {
				if ($filter === true) {
					$REQUEST = filter($_REQUEST[$position], true);
				} elseif ($filter === "escape") {
					$REQUEST = filter($_REQUEST[$position], "escape");
				}  else {
					$REQUEST = $_REQUEST[$position];
				}		
			}	
		} elseif (isset($_REQUEST[$position]) and $_REQUEST[$position] === 0) {
			return 0;
		} else {
			return false;
		}

		return $REQUEST;
	}
}

if (!function_exists("recoverPOST")) {
	function recoverPOST($position, $value = null)
	{ 
		if (is_null($value)) {
			return (is_array(POST($position))) ? POST($position) : (POST($position) ? htmlentities(POST($position, "decode", false)) : null);
		} else { 
			if (is_array($value)) {
				foreach ($value as $val) {
					if (!is_array($val)) {
						$data[] = htmlentities($val);
					} else {
						array_walk_recursive($val, create_function('&$val', '$val = htmlentities($val);'));
						$data[] = $val;
					}
				}
				
				return $data;
			} else { 
				if ($position === "content") {
					return (POST($position)) ? POST($position, "decode", false) : decode($value);
				}

				return (POST($position)) ? htmlentities(POST($position, "decode", false)) : htmlentities(decode($value));
			}	
		}
	}
}

if (!function_exists('removeBr')) {
	function removeBr($string) {
		return str_replace("<br />", "", $e[0]);
	}
}

if (!function_exists("removeSpaces")) {
	function removeSpaces($text, $trim = false)
	{
		$text = preg_replace("/\s+/", " ", $text);				
		return ($trim) ? trim($text) : $text;		
	}
}

if (!function_exists("social")) {
	function social($URL, $content, $facebook = true, $twitter = true, $gPlus = true, $linkedin = true, $float = false)
	{
		$float = ($float) ? " float-right" : null;
		$HTML  = '<div class="social'. $float .'">';
		$HTML .= ($facebook) ? ' <div class="fb-like" data-href="'. $URL .'" data-send="true" data-layout="button_count" data-width="100" data-show-faces="true" data-font="lucida grande"></div>' : "";
		$HTML .= ($twitter) ? ' <a href="https://twitter.com/share" class="twitter-share-button" data-url="'. $URL .'" data-text="'. $content .'" data-via="'. VIA .'" data-lang="'. _get("webLang") .'">Tweet</a>' : "";
		$HTML .= ($gPlus) ? ' <div class="g-plusone" data-size="medium" data-href="'. $URL .'"></div>' : "";
		$HTML .= ($linkedin) ? ' <script type="IN/Share" data-url="'. $URL .'" data-counter="right"></script>' : "";
		$HTML .= '</div>';
		return $HTML;
	}
}

if (!function_exists("showLinks")) {
	function showLinks($content)
	{
		return preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1" target="_blank">$1</a>', $content);
	}
}

if (!function_exists("num2str")) {
	function num2str($number, $translate = FALSE, $function = FALSE)
	{
		switch($number) {
			case 1:
				$string = "one";
				break;
			case 2:
				$string = "two";
				break;
			case 3:
				$string = "three";
				break;
			case 4:
				$string = "four";
				break;
			case 5:
				$string = "five";
				break;
			case 6:
				$string = "six";
				break;
			case 7:
				$string = "seven";
				break;
			case 8:
				$string = "eight";
				break;
			case 9:
				$string = "nine";
				break;
			default:
				$string = "zero";
		}

		if($translate) {
			$string = __($string);
		}

		if($function) {
			$string = $funciont($string);
		}

		return $string;
	}
}