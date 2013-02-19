<?php
if (!defined("ACCESS")) {
    die("Error: You don't have permission to access here...");
}

class ZP_Singleton
{
	public static $instances = array();
	
	private final function __clone() {}
	
	private function __construct() {}

	public static function instance($Class, $params = NULL)
	{
		if (is_null($Class)) {
			die("Missing class information");
		}

		if (!array_key_exists($Class, self::$instances)) {	
			$args = NULL;		
			$i = 0;
			
			if (is_array($params)) {
				foreach ($params as $param) {
					if ($i === count($params) - 1) {
						$args .= '"'. $param .'"';
					} else {
						$args .= '"'. $param .'", ';
					}

					$i++;
				}
			}
			
			if (is_null($args)) {
				self::$instances[$Class] = new $Class;
			} else {
				eval("self::\$instances[\$Class] = new \$Class($args);");
			}
		}
		
		return self::$instances[$Class];
	}
}