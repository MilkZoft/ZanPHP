<?php
/* ex: set tabstop=2 noexpandtab: */
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

function get($var = NULL) {
	global $ZP;

	if($var === "db") {
		include "www/config/database.php";

		return isset($ZP["db"]) ? $ZP["db"] : FALSE;
	}

	return isset($ZP[$var]) ? $ZP[$var] : FALSE;
}

function set($var = NULL, $value = NULL) {
	global $ZP;

	if(is_null($var) or is_null($value)) {
		return FALSE;
	}

	$ZP[$var] = $value;
}