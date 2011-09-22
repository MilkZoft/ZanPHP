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
 * Alerts Helper
 *
 * This class selects the driver for the database to use and sends to call their respective methods
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	helpers
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/helpers/alerts_helper
 */

/**
 * Sets and shows an alert
 *
 * @param string  $message
 * @param string  $type = "Error"
 * @param boolean $URL = NULL
 * @return string value
 */
function getAlert($message, $type = "error", $URL = NULL) {
	if(!is_null($URL)) {
		$message = a(__($message), encode($URL), TRUE);
	}
	
	if($type === "error") {
		return '<div class="flashdata error">'. __($message) .'</div>';
	} elseif($type === "success") {
		unset($_POST);
		
		return '<div class="flashdata success">'. __($message) .'</div>';
	} elseif($type === "warning") {
		return '<div class="flashdata warning">'. __($message) .'</div>';
	} elseif($type === "notice") {
		return '<div class="flashdata notice">'. __($message) .'</div>';
	}
}

/**
 * 
 *
 * 
 *
 */
function showAlert($message, $URL = FALSE) {
	print '	<script>
				alert("'. __($message) .'");';
	
	if($URL) {
		print '	window.location.href="'. $URL .'";';			
	}
	
	print '</script>';

	exit();
}