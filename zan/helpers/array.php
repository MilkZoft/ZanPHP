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
 * Array Helper
 *
 * This class selects the driver for the database to use and sends to call their respective methods
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	helpers
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/helpers/array_helper
 */

/**
 * Returns true if the array is a double array, false if only is a simple array.
 *
 * @param array $multiArray
 * @return boolean
 */
function isMultiArray($multiArray) {
	if(is_array($multiArray)) {  
		foreach($multiArray as $array) {  
			if(is_array($array)) {
				return TRUE;  
			}  
		}  
	}  
	
	return FALSE; 
}

function string2Array($string, $char = ",") {
	$string = str_replace(", ", ",", $string);

	$array = explode($char, $string);

	if(count($array) > 0) {
		return $array; 
	}

	return FALSE;
}