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
 * Autoload Helper
 *
 * The Helper Autoload contains an implementation of the native PHP function __autoload()
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	helpers
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/helpers/autoload_helper
 */

/**
 * Auto-load a called class when you try to use one that hasn't been defined yet
 *
 * @param string $class
 * @return void
 */
function __autoload($class) {	
	$class = str_replace("ZP_", "", $class);
	
	if(file_exists(_corePath . _sh . _classes . _sh . _class . _dot . strtolower($class) . _PHP)) {
		include _corePath . _sh . _classes . _sh . _class . _dot . strtolower($class) . _PHP;					
	}
}
