<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

if (!function_exists("__")) {
	function __($text, $encode = true)
	{
		if (_get("translation") === "gettext") {
			global $Gettext_Reader;
			
			if (is_null($Gettext_Reader)) {
				return $text;
			}
			
			return $Gettext_Reader->translate($text);
		} else {
			global $Load, $phrase;
			
			$language = whichLanguage();
			
			if (file_exists("www/lib/languages/". strtolower($language) .".php")) {
				include_once "www/lib/languages/". strtolower($language) .".php";
			} 

			$position = strtolower($text);
			$position = str_replace(" ", "_", $position); 
			$position = str_replace("?,", "", $position);
			$position = str_replace("?", "", $position);
			$position = str_replace("!", "", $position);
			$position = str_replace("¡", "", $position);
			$position = str_replace("¿", "", $position);
			$position = str_replace(",", "", $position);
			$position = str_replace(":", "", $position);
			$position = str_replace("'", "", $position);
			$position = str_replace('"', "", $position);
			$position = str_replace(".", "", $position);
			$position = str_replace("&", "-", $position);
			
			if (isset($phrase[$position])) {
				return ($encode) ? encode($phrase[$position]) : $phrase[$position];
			} else {
				if ($language !== "English" and !_get("production")) {
					$content = "";
					$logfile = "www/lib/languages/". strtolower($language) .".txt"; 
					$today = date("d/m/Y");

					if (file_exists($logfile)) {
						$content = file_get_contents($logfile);
					}

					$file = @fopen($logfile, "a+");
					$pos = @strrpos($content, "$today");

					if ($pos !== false) {
						if (!@preg_match("/\\b" . addslashes($position) . "\\b/i", substr($content, $pos + 14))) {
							@fwrite($file, "$position\r\n");
						}
					} else {
						@fwrite($file, "--- $today ---\r\n");
						@fwrite($file, "$position\r\n");
					}
				}

				return $text;
			}
		}
	}
}

if (!function_exists("getLanguage")) {
	function getLanguage($lang, $flags = false)
	{
		$languages = getLanguagesFromDir();
		
		foreach ($languages as $language) {
			if ($flags) {
				if ($language["language"] === $lang) {
					return '<span title="'. __($lang) .'" class="flag '. strtolower($language["lang"]) .'-flag"></span>';	
				}
			} else {
				if ($language["language"] === $lang) {
					return __($lang);
				}
			}
		}

		return false;
	}
}

if (!function_exists("isLang"))
{
	function isLang($lang = false)
	{
		$lang = (!$lang) ? strtolower(segment(0)) : $lang;	
		$langs = array(
			"ar", "bs", "be", "bu", "ca", "ch", "cr", "cz", "da", "du", "en", "et", "fi", "fr", "ga", "ge", "gr", "he", 
			"hu", "in", "it", "jp", "ku", "li", "ma", "pe", "po", "pt", "ro", "ru", "se", "sk", "sn", "es", "sw", "th", 
			"tk", "uk", "ur", "vi"
		);
		return in_array($lang, $langs) ? true : false;
	}
}

if (!function_exists("whichLanguage")) {
	function whichLanguage($invert = true, $lower = false)
	{
		global $Load;		
		$Load->helper("router");

		if (isLang(segment(0))) {
			if (segment(0) and !$invert) {
				return segment(0);
			} elseif (segment(0) and getLang(segment(0), true)) { 
				return ($lower) ? strtolower(getLang(segment(0), true)) : getLang(segment(0), true);
			} elseif (!$invert) {
				return getLang(_get("webLanguage"));
			} else {
				return ($lower) ? strtolower(_get("webLanguage")) : _get("webLanguage");
			}	
		} else {
			if (!$invert) {
				return getLang(_get("webLanguage"));
			} else {
				return ($lower) ? strtolower(_get("webLanguage")) : _get("webLanguage");
			}	
		}
	}
}

if (!function_exists("getLanguages")) {
	function getLanguages($flags = false)
	{
		$data = array();
		$languages = getLanguagesFromDir();

		foreach ($languages as $language) {
			$default = ($language["language"] === _get("webLanguage")) ? true : false;
			$data[] = array("default" => $default, "name" => $language["language"], "value" => getLanguage($language["language"], $flags));
		}
		
		return $data;
	}
}

if (!function_exists("getLanguagesInput")) {
	function getLanguagesInput($lang = null, $name = "language", $input = "radio")
	{
		$languages = getLanguages(true);
		$HTML = null;

		if ($input === "select") {
			$HTML = '<select id="language" name="'. $name .'" size="1">';
		}

		foreach ($languages as $language) {
			if (!isset($checked)) {
				if (!is_null($lang)) {
					if ($lang === $language["name"]) {
						$check = ($input === "radio") ? ' checked="checked"' : ' selected="selected"';
						$checked = true;								
					} else {
						$check = null;
					}
				} else {
					if ($language["default"] === true) {				
						$check = ($input === "radio") ? ' checked="checked"' : ' selected="selected"';
						$checked = true;
					}
				}
			}

			$show = isset($check) ? $check : null;

			if ($input === "radio") {
				$HTML .= ' <input id="language" name="'. $name .'" type="radio" value="'. $language["name"] .'" '. $show .' /> '. $language["value"] .' ';			
			} elseif ($input === "select") {
				$HTML .= ' <option value="'. $language["name"] .'"'. $show .'>'. __($language["name"]) .'</option>';
			}

			unset($check);
		}

		if ($input === "select") {
			$HTML .= '</select>';
		}
		
		return $HTML;
	}
}

if (!function_exists("getLocal")) {
	function getLocal($lang = false)
	{	
		if (!$lang) {
			$lang = whichLanguage();
		}

		$languages = array(
			"Arabic" => "ar_AR", "Basque" => "eu_ES", "Belarusian" => "be_BY", "Bulgarian" => "bg_BG", "Catalan" => "ca_ES", 
			"Chinese" => "zh_CN", "Croatian" => "hr_HR", "Czech" => "cs_CZ", "Danish" => "da_DK", "Dutch" => "nl_NL", 
			"English" => "en_US", "Estonian" => "et_EE", "Finnish" => "fi_FI", "French" => "fr_FR", "Galician" => "gl_ES", 
			"German" => "de_DE", "Greek" => "el_GR", "Hebrew" => "he_IL", "Hungarian" => "hu_HU", "Indonesian" => "id_ID", 
			"Italian" => "it_IT", "Japanese" => "ja_JP", "Kurdish" => "ku_TR", "Lithuanian" => "lt_LT", "Macedonian" => "mk_MK", 
			"Persian" => "Persian", "Polish" => "pl_PL", "Portuguese" => "pt_BR", "Romanian" => "ro_RO", "Russian"	=> "ru_RU", 
			"Serbian" => "sr_RS", "Slovak" => "sk_SK", "Slovenian" => "sl_SI", "Spanish" => "es_LA", "Swedish" => "sv_SE", 
			"Thai" => "th_TH", "Turkish" => "tr_TR", "Ukrainian" => "uk_UA", "Vietnamese" => "vi_VN"
		);

		foreach ($languages as $language => $locale) {
			if ($language === $lang) {
				return $locale;
			}
		}
	}
}

if (!function_exists("getLang")) {
	function getLang($lg, $invert = false)
	{
		$languages = array(
			"Arabic" => "ar", "Basque" => "bs", "Belarusian" => "be", "Bulgarian" => "bu", "Catalan" => "ca", "Chinese" => "ch", 
			"Croatian" => "cr", "Czech" => "cz", "Danish" => "da", "Dutch" => "du", "English" => "en", "Estonian" => "et", 
			"Finnish" => "fi", "French" => "fr", "Galician" => "ga", "German" => "ge", "Greek" => "gr", "Hebrew" => "he", 
			"Hungarian" => "hu", "Indonesian" => "in", "Italian" => "it", "Japanese" => "jp", "Kurdish" => "ku", "Lithuanian" => "li", 
			"Macedonian" => "ma", "Persian" => "pe", "Polish" => "po", "Portuguese" => "pt", "Romanian" => "ro", "Russian" => "ru", 
			"Serbian" => "se", "Slovak"	=> "sk", "Slovenian" => "sn", "Spanish" => "es", "Swedish" => "sw", "Thai" => "th", 
			"Turkish" => "tk", "Ukrainian" => "uk", "Vietnamese" => "vi"
		);	
		
		foreach ($languages as $language => $lang) {
			if ($invert) {
				if ($lg === $lang) {
					return $language;
				}	
			} else {
				if ($language === $lg) {
					return $lang;
				}
			}
		}

		return ($invert) ? "English" : "en";
	}
}

if (!function_exists("getLanguagesFromDir")) {
	function getLanguagesFromDir()
	{
		$path = "www/lib/languages";
		$dir = dir($path);
		$i = 1;
		
		while ($element = $dir->read()) { 
			if ($element !== ".." and $element !== "." and $element !== ".DS_Store" and $element !== "index.html") {
				$language = str_replace("language.", "", $element);
				$parts = explode(".", $language);

				if (count($parts) > 1) {
					if ($parts[1] === "php") {
						$languages[$i]["language"] = ucfirst($parts[0]);			
						$languages[$i]["lang"] = getLang(ucfirst($parts[0]));							
						$i++;		
					}
				}
			}
		}	

		$dir->close();		
		return $languages;
	}
}