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
 * @license		http://www.zanphp.com/documentation/en/basic/licence
 * @link		http://www.zanphp.com
 */
 
/**
 * Access from index.php
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

/**
 * Includes RESTServer
 */
include "restserver.php";

/**
 * ZanPHP Load Class
 *
 * This class is used to load models, views, controllers, classes, libraries, helpers as well as interact directly with templates
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	classes
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/classes/load_class
 */
class ZP_Controller extends ZP_Load {

	public function __construct() {}
	
    /**
     * 
     *
     * @return void
     */
	public function helpers() {
		$helpers = array("pagination", "alerts", "debugging", "time", "string", "forms", "security");
		
		$this->helper($helpers);	
	}
	
}
