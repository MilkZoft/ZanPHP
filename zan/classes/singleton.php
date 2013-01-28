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
 * ZanPHP Singleton Class
 *
 * This class used to instantiate a class once
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	classes
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/classes/singleton_class
 */
class ZP_Singleton {

    /**
     * Contains the instances of the objects
     *
     * @var private static $instances = array()
     */
	public static $instances = array();

	/**
     * Prevent object cloning
     *
     * @return void
     */
	private final function __clone() {}

    /**
     * Prevent direct object creation
     *
     * @return void
     */
	private function __construct() {}

	/**
     * Returns new or existing Singleton instance
     * @param string $class
     * @return object value
     */
	public static function instance($Class, $params = NULL) {
	if(is_null($Class)) {
		die("Missing class information");
	}

	if(!array_key_exists($Class, self::$instances)) {
		$args = NULL;

		$i = 0;
		if(is_array($params)) {
		foreach($params as $param) {
			if($i === count($params) - 1) {
			$args .= '"'. $param .'"';
			} else {
			$args .= '"'. $param .'", ';
			}

			$i++;
		}
		}

		if(is_null($args)) {
		self::$instances[$Class] = new $Class;
		} else {
		eval("self::\$instances[\$Class] = new \$Class($args);");
		}
	}

	return self::$instances[$Class];
	}

}
