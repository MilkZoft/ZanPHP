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
 * Debugging Helper
 *
 * The Helper Debugging contains functions for debugging PHP code
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	helpers
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/helpers/debugging_helper
 */

/**
 * A powerfull debugging function based on die() and var_dump or print_r PHP-defined functions
 * @param mixed $var
 * @return void
 */
function ____($var, $dump = TRUE, $exit = TRUE) {
	echo '<pre style="font-size: 1.3em; color: #FF0000; line-height: 18px;">';
		if(!$dump) {
			echo_r($var);
		} else {
			var_dump($var);
		}
	echo '</pre>';	
	
	if($exit) {
		exit();
	}
}