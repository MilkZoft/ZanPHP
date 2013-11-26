<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

if (!function_exists("paginate")) {
	function paginate($count, $end, $start, $URL, $anchor = "#top", $new = false)
	{
		$pageNav = null;

		if ($count > $end) {				
			$rest = $count % $end;	
			$pages = ($rest === 0) ? $count / $end : (($count - $rest) / $end) + 1;

			if ($pages > 10) {	
				$currentPage = ($start / $end) + 1;

				if ($start === 0) {
					$firstPage = 0;
					$lastPage = 10;
				} elseif ($currentPage >= 5 and $currentPage <= ($pages - 5)) {					
					$firstPage = $currentPage - 5;
					$lastPage = $currentPage + 5;					
				} elseif ($currentPage < 5) {					
					$firstPage = 0;
					$lastPage = $currentPage + 5 + (5 - $currentPage);					
				} else {					
					$firstPage = $currentPage - 5 - (($currentPage + 5) - $pages);
					$lastPage = $pages;					
				}								
			} else {			
				$firstPage = 0;
				$lastPage = $pages;			
			}

			for ($i = $firstPage; $i < $lastPage; $i++) {
				$pge = $i + 1;
				$next = $i * $end;
				if ($new) {	
					$pageNav .= ($start == $next) ? '<li class="active"><a href="#">'. $pge .'</a></li>' : '<li><a href="'. $URL . $pge . "/" . $anchor .'" title="'. $pge .'">'. $pge .'</a></li>';
				} else {
					$pageNav .= ($start == $next) ? '<span class="current">'. $pge .'</span> ' : '<span class="bold"><a href="'. $URL . $pge . "/" . $anchor .'" title="'. $pge .'">'. $pge .'</a></span> ';				
				}
			}
			
			$currentPage = ($start == 0) ? 1 : ($start / $end) + 1;		
			
			if ($new) {	
				$pageNext = ($currentPage < $pages) ? '<li><a href="'. $URL . ($currentPage + 1) ."/". $anchor .'" title="'. __("Next") .'">»</a></li>' : null;						
				$pagePrevious = ($start > 0) ? '<li><a href="'. $URL . ($currentPage - 1) ."/". $anchor .'" title="'. __("Previous") .'">«</a></li>' : null;		
			} else {
				$pageNext = ($currentPage < $pages) ? '<a href="'. $URL . ($currentPage + 1) ."/". $anchor .'" title="'. __("Next") .'">'. __("Next") .'</a> ' : null;						
				$pagePrevious = ($start > 0) ? '<a href="'. $URL . ($currentPage - 1) ."/". $anchor .'" title="'. __("Previous") .'">'. __("Previous") .'</a> ' : null;				
			}
		}		

		if ($new) {
			$pagination = '	<div class="pagination pagination-large pull-left">
								<ul>';

			$pagination .= $pagePrevious . $pageNav . $pageNext;

			$pagination .= '	</ul>
							</div>';
			return $pagination;
		} else {
			return '<div id="pagination">'. $pagePrevious . $pageNav . $pageNext .'</div>';
		}		
	}
}