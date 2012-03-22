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
 * Inflect Helper
 *
 * 
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	helpers
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/helpers/inflect_helper
 */

/**
 * pluralize
 *
 * Returns the plural form of a Word
 * 
 * @param string $word
 * @return string $word
 */
function pluralize($word) {		
	$uncountable = array("sheep",
						 "fish",
						 "deer",
						 "series",
						 "species",
						 "money",
						 "rice",
						 "information",
						 "equipment"
	);	
	
	$irregular = array(	"move"   => "moves",
						"foot"   => "feet",
						"goose"  => "geese",
						"sex"    => "sexes",
						"child"  => "children",
						"man"    => "men",
						"tooth"  => "teeth",
						"person" => "people"
	);	
	
	$plural = array(	"/(quiz)$/i"               		=> "$1zes",
						"/^(ox)$/i"                		=> "$1en",
						"/([m|l])ouse$/i"          		=> "$1ice",
						"/(matr|vert|ind)ix|ex$/i" 		=> "$1ices",
						"/(x|ch|ss|sh)$/i"         		=> "$1es",
						"/([^aeiouy]|qu)y$/i"      		=> "$1ies",
						"/(hive)$/i"               		=> "$1s",
						"/(?:([^f])fe|([lr])f)$/i" 		=> "$1$2ves",
						"/(shea|lea|loa|thie)f$/i" 		=> "$1ves",
						"/sis$/i"                  		=> "ses",
						"/([ti])um$/i"             		=> "$1a",
						"/(tomat|potat|ech|her|vet)o$/i"=> "$1oes",
						"/(bu)s$/i"                		=> "$1ses",
						"/(alias)$/i"              		=> "$1es",
						"/(octop)us$/i"            		=> "$1i",
						"/(ax|test)is$/i"          		=> "$1es",
						"/(us)$/i"                 		=> "$1es",
						"/s$/i"                    		=> "s",
						"/$/"                      		=> "s"
	);	
				
	if(in_array(strtolower($word), $uncountable)) {
		return $word;
	}
	
	foreach($irregular as $pattern => $result) {
		$pattern = "/$pattern$/i";
		
		if(preg_match($pattern, $word)) {
			return preg_replace($pattern, $result, $word);
		}
	}

	foreach($plural as $pattern => $result) {
		if(preg_match($pattern, $word)) {
			return preg_replace($pattern, $result, $word);
		}
	}
	
	return $word;
}

/**
 * singularize
 *
 * Returns the singular form of a Word
 * 
 * @param string $word
 * @return string $word
 */
function singularize($word) {
	$uncountable = array("sheep",
						 "fish",
						 "deer",
						 "series",
						 "species",
						 "money",
						 "rice",
						 "information",
						 "equipment"
	);	
	
	$irregular = array(	"move"   => "moves",
						"foot"   => "feet",
						"goose"  => "geese",
						"sex"    => "sexes",
						"child"  => "children",
						"man"    => "men",
						"tooth"  => "teeth",
						"person" => "people"
	);	

	$singular = array(	"/(quiz)zes$/i"              => "$1",
						"/(matr)ices$/i"             => "$1ix",
						"/(vert|ind)ices$/i"         => "$1ex",
						"/^(ox)en$/i"                => "$1",
						"/(alias)es$/i"              => "$1",
						"/(octop|vir)i$/i"           => "$1us",
						"/(cris|ax|test)es$/i"       => "$1is",
						"/(shoe)s$/i"                => "$1",
						"/(o)es$/i"                  => "$1",
						"/(bus)es$/i"                => "$1",
						"/([m|l])ice$/i"             => "$1ouse",
						"/(x|ch|ss|sh)es$/i"         => "$1",
						"/(m)ovies$/i"               => "$1ovie",
						"/(s)eries$/i"               => "$1eries",
						"/([^aeiouy]|qu)ies$/i"      => "$1y",
						"/([lr])ves$/i"              => "$1f",
						"/(tive)s$/i"                => "$1",
						"/(hive)s$/i"                => "$1",
						"/(li|wi|kni)ves$/i"         => "$1fe",
						"/(shea|loa|lea|thie)ves$/i" => "$1f",
						"/(^analy)ses$/i"            => "$1sis",
						"/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i"  => "$1$2sis",
						"/([ti])a$/i"                => "$1um",
						"/(n)ews$/i"                 => "$1ews",
						"/(h|bl)ouses$/i"            => "$1ouse",
						"/(corpse)s$/i"              => "$1",
						"/(us)es$/i"                 => "$1",
						"/s$/i"                      => ""
	);	
	
	if(in_array(strtolower($word), $uncountable)) {
		return $word;
	}

	foreach($irregular as $result => $pattern) {
		$pattern = "/$pattern$/i";
		
		if(preg_match($pattern, $word)) {
			return preg_replace($pattern, $result, $word);
		}
	}

	foreach($singular as $pattern => $result) {
		if(preg_match($pattern, $word)) {
			return preg_replace($pattern, $result, $word);
		}
	}
	
	return $word;
}
