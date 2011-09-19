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
 * Benchmark Helper
 *
 * The Helper Benchmarck contains an 
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	helpers
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/helpers/benchmark_helper
 */
 
/**
 * A Benchmark utility to test Application Perfomance, Queries and Process
 *
 * @param global $startTime
 * @return float
 */
function benchMarkEnd() {
	global $startTime;
	
	return (microtime(true) - $startTime);
}

/**
 * 
 *
 * 
 *
 */
function benchMarkStart() {
	global $startTime;
	
	$startTime = microtime(true);
}