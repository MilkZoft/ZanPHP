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
 * Validations Helper
 *
 * 
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	helpers
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/helpers/validations_helper
 */
 

function getMetatags($type = "Web", $title = NULL, $description = NULL) {	
	if($type === "Web") {
		$HTML  = char("\t") . '<meta http-equiv="content-type" content="text/html; charset=utf-8" />'	. char("\n\t");
		$HTML .= '<meta name="title" content="'.$title.'" />' 											. char("\n\t");
		$HTML .= '<meta name="author" content="MilkZoft" />' 											. char("\n\t");
		$HTML .= '<meta name="description" content="'.$description.'" />' 								. char("\n\t");
		$HTML .= '<meta name="language" content="'._webLanguage.'" />' 									. char("\n\t");
		$HTML .= '<meta name="revisit" content="1 day" />' 												. char("\n\t");
		$HTML .= '<meta name="distribution" content="global" />' 										. char("\n\t");
		$HTML .= '<meta name="robots" content="All" />' 												. char("\n", 2);
	} else {
		$HTML  = char("\t") . '<meta http-equiv="content-type" content="text/html; charset=utf-8" />' 	. char("\n\t");
		$HTML .= '<meta name="robots" content="noindex, nofollow" />' 									. char("\n\t");
		$HTML .= '<meta name="googlebot" content="noindex, nofollow" />' 								. char("\n", 2);
	}
	
	return $HTML;
}

function getFavicon($type = "Web", $theme = NULL) {	
	$themePath = _webURL . _sh . _lib . _sh . _themes  . _sh . $theme . _sh;
	
	if($type === "Web") {
		return '<link rev="shortcut icon" rel="shortcut icon" href="'.$themePath . _images . _sh . "Icon.ico".'" />' . char("\n");
	} else {
		return '<link rev="shortcut icon" rel="shortcut icon" href="'.$themePath . _images . _sh . "Icon.ico".'" />' . char("\n");
	}
}

function compressScript($script) {	
	return compress(file_get_contents(str_replace(_webURL . _sh, "", $script)), FALSE);
}