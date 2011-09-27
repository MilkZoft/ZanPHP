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
function __($text, $normal = FALSE) {
	if(_translation === "gettext" and !$normal) {
		global $Gettext_Reader;
		
		if(is_null($Gettext_Reader)) {
			return $text;
		}
		
		return $Gettext_Reader->translate($text);
	} else {
		global $Load;
		
		$language = whichLanguage();
		
		if($language === "English") {
			$Load->language("English");
		} elseif($language === "Spanish") {
			$Load->language("Spanish");
		} elseif($language === "French") {
			$Load->language("French");
		} elseif($language === "Portuguese") {
			$Load->language("Portuguese");
		} else {
			$Load->language("English");
		}

		return encode(translation($text)); 	
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
function getLanguage($language, $flags = FALSE) {
	if($flags) {
		if($language === "Spanish") {
			return '<img class="no-border" src="'. _webURL . '/www/lib/images/icons/flags/spanish.png" alt="' . __("Spanish") . '" />';
		} elseif($language === "English") {
			return '<img class="no-border" src="'. _webURL . '/www/lib/images/icons/flags/english.png" alt="' . __("English") . '" />';
		} elseif($language === "French") {
			return '<img class="no-border" src="'. _webURL . '/www/lib/images/icons/flags/french.png" alt="' . __("French") . '" />';
		} elseif($language === "Portuguese") {
			return '<img class="no-border" src="'. _webURL . '/www/lib/images/icons/flags/portuguese.png" alt="' . __("Portuguese") . '" />';
		}
	} else {
		if($language === "Spanish") {
			return __("Spanish");
		} elseif($language === "English") {
			return __("English");
		} elseif($language === "French") {
			return __("French");
		} elseif($language === "Portuguese") {
			return __("Portuguese");		
		}
	}
}

/**
 * getXMLang()
 *
 * Returns the standard XML language
 * 
 * @param string $language
 * @param string $invert   = FALSE
 * @return string $language / bool
 */
function getXMLang($language, $invert = FALSE) {
	if($invert === TRUE) {
		if($language === "en") {
			return "English";
		} elseif($language === "es") {
			return "Spanish";
		} elseif($language === "fr") {
			return "French";
		} elseif($language === "it") {
			return "Italian";
		} elseif($language === "pt") {
			return "Portuguese";
		}
	} elseif($invert === FALSE) {
		if($language === "English") {
			return "en";
		} elseif($language === "Spanish") {
			return "es";
		} elseif($language === "French") {
			return "fr";
		} elseif($language === "Italian") {
			return "it";
		} elseif($language === "Portuguese") {
			return "pt";
		}
	} else {
		return $language;	
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
function isLang($language = FALSE) {
	if(!$language) {
		$language = segment(0);	
	}
	
	if($language === "en") {
		return TRUE;
	} elseif($language === "es") {
		return TRUE;
	} elseif($language === "fr") {
		return TRUE;
	} elseif($language === "it") {
		return TRUE;
	} elseif($language === "pt") {
		return TRUE;
	}
	
	return FALSE;
}


/**
 * whichLanguage
 *
 * Returns the default Language
 * 
 * @param boolean $invert = TRUE
 * @return string value
 */
function whichLanguage($invert = TRUE) {
	global $Load;
	
	$Load->helper("router");
	
	if(segment(0) === "en" or segment(0) === "es" or segment(0) === "fr" or segment(0) === "pt") {
		if(segment(0) and $invert === FALSE) {
			return segment(0);
		} elseif(segment(0) and getXMLang(segment(0), TRUE) != FALSE) {
			return getXMLang(segment(0), TRUE);
		} elseif($invert === FALSE) {
			return getXMLang(_webLanguage, FALSE);
		} else {
			return _webLanguage;
		}	
	} else {
		if($invert === FALSE) {
			return getXMLang(_webLanguage, FALSE);
		} else {
			return _webLanguage;
		}	
	}
}

function getLanguages($flags = FALSE) {
	$data = array();

	if(_Spanish) {
		if(_webLanguage === "Spanish") {
			$default = TRUE;	
		} else {
			$default = FALSE;
		}

		$data[] = array("default" => $default, "name" => "Spanish", "value" => getLanguage("Spanish", $flags));
	}
	
	if(_English) {
		if(_webLanguage === "English") {
			$default = TRUE;	
		} else {
			$default = FALSE;
		}

		$data[] = array("default" => $default, "name" => "English", "value" => getLanguage("English", $flags));
	}
	
	if(_French) {
		if(_webLanguage === "French") {
			$default = TRUE;	
		} else {
			$default = FALSE;
		}

		$data[] = array("default" => $default, "name" => "French", "value" => getLanguage("French", $flags));
	}
	
	if(_Portuguese) {
		if(_webLanguage === "Portuguese") {
			$default = TRUE;	
		} else {
			$default = FALSE;
		}

		$data[] = array("default" => $default, "name" => "Portuguese", "value" => getLanguage("Portuguese", $flags));
	}
	
	return $data;
}

function getLanguageRadios($lang = NULL) {
	$languages = getLanguages(TRUE);
	$HTML = NULL;

	foreach($languages as $language) {
		if($language["default"]) {
			$check = ' checked="checked"';
		} elseif($lang === $language["name"]) {
			$check = ' checked="checked"';
		} else {
			$check = NULL;
		}	

		$HTML .= '<input id="language" name="language" type="radio" value="'. $language["name"] .'" tabindex="4"'. $check .' /> '. $language["value"];
	}
	
	return $HTML;
}
