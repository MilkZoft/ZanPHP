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
 * i18n Helper
 *
 * 
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	helpers
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/helpers/i18n_helper
 */

/**
 * __()
 *
 * Returns the translation of a specific text
 * 
 * @param string $text
 * @return string value
 */
function __($text) {
	if(get("translation") === "gettext") {
		global $Gettext_Reader;
		
		if(is_null($Gettext_Reader)) {
			return $text;
		}
		
		return $Gettext_Reader->translate($text);
	} else {
		global $Load, $phrase;
		
		$language = whichLanguage();
		
		if(file_exists("www/lib/languages/language.". strtolower($language) .".php")) {
			include_once "www/lib/languages/language.". strtolower($language) .".php";
		} 

		$position = strtolower(str_replace(" ", "_", $text));

		return isset($phrase[$position]) ? $phrase[$position] : $text; 	
	}
}

/**
 * getLanguage
 *
 * Returns the translation of a specific Language Word
 * 
 * @param boolean $invert = TRUE
 * @return string value
 */
function getLanguage($lang, $flags = FALSE) {
	$languages = getLanguagesFromDir();

	foreach($languages as $language) {
		if($flags) {
			if($language["language"] === $lang) {
				return '<img class="no-border" src="'. get("webURL") .'/www/lib/images/icons/flags/'. strtolower($lang) .'.png" alt="'. __(_($lang)) .'" />';	
			}
		} else {
			if($language["language"] === $lang) {
				return __(_($lang));
			}
		}
	}

	return FALSE;
}

/**
 * isLanguage
 *
 * Defines if an Abbrevation is a standard XML language
 * 
 * @param boolean $invert = TRUE
 * @return string value
 */
function isLang($lang = FALSE) {
	$lang = (!$lang) ? strtolower(segment(0)) : $lang;	
	
	$langs = array("ar", "bs", "be", "bu", "ca", "ch", "cr", "cz", "da", "du", "en", "et", "fi", "fr", "ga", "ge", "gr", "he", "hu", "in", "it", "jp", "ku", "li", "ma", "pe", "po", "pt", "ro", "ru", "se", "sk", "sn", "es", "sw", "th", "tk", "uk", "ur", "vi");
	
	return in_array($lang, $langs) ? TRUE : FALSE;
}


/**
 * whichLanguage
 *
 * Returns the default Language
 * 
 * @param boolean $invert = TRUE
 * @return string value
 */
function whichLanguage($invert = TRUE, $lower = FALSE) {
	global $Load;
	
	$Load->helper("router");

	if(isLang(segment(0))) {
		if(segment(0) and !$invert) {
			return segment(0);
		} elseif(segment(0) and getLang(segment(0), TRUE)) { 
			return ($lower) ? strtolower(getLang(segment(0), TRUE)) : getLang(segment(0), TRUE);
		} elseif(!$invert) {
			return getLang(get("webLanguage"));
		} else {
			return ($lower) ? strtolower(get("webLanguage")) : get("webLanguage");
		}	
	} else {
		if(!$invert) {
			return getLang(get("webLanguage"));
		} else {
			return ($lower) ? strtolower(get("webLanguage")) : get("webLanguage");
		}	
	}
}

function getLanguages($flags = FALSE) {
	$data = array();

	$languages = getLanguagesFromDir();

	foreach($languages as $language) {
		$default = ($language["language"] === get("webLanguage")) ? TRUE : FALSE;

		$data[] = array("default" => $default, "name" => $language["language"], "value" => getLanguage($language["language"], $flags));
	}
	
	return $data;
}

function getLanguagesInput($lang = NULL, $name = "language", $input = "radio") {
	$languages = getLanguages(TRUE);
	$HTML = NULL;

	if($input === "select") {
		$HTML = '<select name="'. $name .'" size="1">';
	}

	foreach($languages as $language) {
		if($language["default"] or $lang === $language["name"]) {
			$check = ($input === "radio") ? ' checked="checked"' : ' selected="selected"';
		} else {
			$check = NULL;
		}	

		if($input === "radio") {
			$HTML .= '<input id="language" name="'. $name .'" type="radio" value="'. $language["name"] .'" '. $check .' /> '. $language["value"];
		} elseif($input === "select") {
			$HTML .= '<option value="'. $language["name"] .'"'. $check .'>'. __(_($language["name"])) .'</option>';
		}
	}

	if($input === "select") {
		$HTML .= '</select>';
	}
	
	return $HTML;
}

function getLang($lg, $invert = FALSE) {
	$languages = array(
					"Arabic"	 => "ar",
					"Basque"	 => "bs",
					"Belarusian" => "be",
					"Bulgarian"  => "bu",
					"Catalan"	 => "ca",
					"Chinese"	 => "ch",
					"Croatian"   => "cr",
					"Czech"		 => "cz",
					"Danish"	 => "da",
					"Dutch"		 => "du",
					"English" 	 => "en",
					"Estonian"   => "et",
					"Finnish"	 => "fi",
					"French"  	 => "fr",
					"Galician"   => "ga",
					"German"	 => "ge",
					"Greek"		 => "gr",
					"Hebrew"	 => "he",
					"Hungarian"  => "hu",
					"Indonesian" => "in",
					"Italian"	 => "it",
					"Japanese"	 => "jp",
					"Kurdish"	 => "ku",
					"Lithuanian" => "li",
					"Macedonian" => "ma",
					"Persian"	 => "pe",
					"Polish"	 => "po",
					"Portuguese" => "pt",
					"Romanian"   => "ro",
					"Russian"	 => "ru",
					"Serbian"	 => "se",
					"Slovak"	 => "sk",
					"Slovenian"	 => "sn",
					"Spanish" 	 => "es",
					"Swedish"	 => "sw",
					"Thai"		 => "th",
					"Turkish"	 => "tk",
					"Ukrainian"  => "uk",
					"Urdu"		 => "ur",
					"Vietnamese" => "vi"
				);	
	
	foreach($languages as $language => $lang) {
		if($invert) {
			if($lg === $lang) {
				return $language;
			}	
		} else {
			if($language === $lg) {
				return $lang;
			}
		}
	}

	return ($invert) ? "English" : "en";
}

function getLanguagesFromDir() {
	$path = "www/lib/languages/gettext";
	$dir  = dir($path);
	
	$languages[0]["language"] = "English";
	$languages[0]["lang"]	  = "en";

	$i = 1;
	
	while($element = $dir->read()) { 
		if($element !== ".." and $element !== "." and $element !== ".DS_Store" and $element !== "index.html") {
			$language  = str_replace("language.", "", $element);
			$parts = explode(".", $language);

			if(count($parts) > 1) {
				if($parts[1] === "mo") {
					$languages[$i]["language"] = ucfirst($parts[0]);			
					$languages[$i]["lang"]	   = getLang(ucfirst($parts[0]));
						
					$i++;		
				}
			}
		}
	}	
		
	$dir->close();		
	
	return $languages;
}