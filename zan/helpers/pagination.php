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
 * HTML Helper
 *
 * 
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	helpers
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/helpers/html_helper
 */

function paginate($count, $end, $start, $URL, $anchor = "#top") {
	$pageNav 	  = NULL;
	$pagePrevious = NULL;
	$pageFirst    = NULL;
	$pageLast     = NULL;
	$pageNext     = NULL;
	
	if($count > $end) {				
		$rest = $count % $end;	
					
		if($rest === 0) {
			$pages = $count / $end;
		} else {
			$pages = (($count - $rest) / $end) + 1;
		}

		if($pages > 10) {	
			$currentPage = ($start / $end) + 1;
			
			if($start === 0) {
				$firstPage = 0;
				$lastPage  = 10;
			} elseif($currentPage >= 10 and $currentPage <= ($pages - 10)) {					
				$firstPage = $currentPage - 10;
				$lastPage  = $currentPage + 10;					
			} elseif($currentPage < 10) {					
				$firstPage = 0;
				$lastPage  = $currentPage + 10 + (10 - $currentPage);					
			} else {					
				$firstPage = $currentPage - 10 - (($currentPage + 10) - $pages);
				$lastPage  = $pages;					
			}								
		} else {			
			$firstPage = 0;
			$lastPage  = $pages;			
		}
			
		for($i = $firstPage; $i < $lastPage; $i++) {
			$pge  = $i + 1;
			$next = $i * $end;		
					
			if($start == $next) {				
				$pageNav .= '<span class="current">'. $pge .'</span> ';					
			} else {				
				$pageNav .= '<span class="bold"><a href="'. $URL . $pge ."/". $anchor .'" title="'. $pge .'">'. $pge .'</a></span> ';
			}
		}
	
		if($start == 0) { 			
			$currentPage = 1; 			
		} else { 			
			$currentPage = ($start / $end) + 1; 			
		}
	
		if($currentPage < $pages) {			
			$pageNext = '<a href="'. $URL . ($currentPage + 1) ."/". $anchor .'" title="'. __("Next") .'">'. __("Next") .'</a> ';
		}
	
		if($start > 0) {
			$pagePrevious = '<a href="'. $URL . ($currentPage - 1) ."/". $anchor .'" title="'. __("Previous") .'">'. __("Previous") .'</a> ';
		}			
	}		
		
	return '<div id="pagination">'. $pageFirst . $pagePrevious . $pageNav . $pageNext . $pageLast .'</div>';
}