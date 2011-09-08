<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

function getScript($js, $application = NULL, $extra = NULL, $getJs = FALSE) {
	$HTML = NULL;
	
	if(file_exists($js)) {		
		return loadScript($js);
	} else {
		if(isset($application)) {
			return loadScript($js, $application);
		} else {
			if($js === "jquery") {
				return loadScript("lib/scripts/js/jquery.js");
			} elseif($js === "external") {
				$HTML = '	<script type="text/javascript">
								$(document).ready(function() { 
									$(function() {
										$(\'a[rel*=external]\').click(function() {
											window.open(this.href);
											return false;
										});
									});
								});
							</script>				
							
							<noscript><p class="NoDisplay">'. __("Disable Javascript") .'</p></noscript>';					
			} elseif($js === "show-element") {
				$HTML  = '	<script type="text/javascript">
								function showElement(obj) {
									if(obj.className == "no-display") {
										obj.className = "display";
									} else {
										obj.className = "no-display";
									}
								}
							</script>';
			} elseif($js === "tiny-mce") {
				$HTML  = loadScript("www/lib/scripts/js/tiny_mce/tiny_mce.js"); 
				$HTML .= '<script type="text/javascript">';
				
				if($extra !== "basic") {
					$HTML .= '			
									tinyMCE.init({
										mode : "exact",
										elements : "editor",
										theme : "advanced",
										skin : "o2k7",
										cleanup: true,
										plugins : "advcode,safari,pagebreak,style,advhr,advimage,advlink,emotions,preview,media,fullscreen,template,inlinepopups,advimage,media,paste",              
										theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,|,link,unlink,|,image,advcode,|,forecolor,|,charmap,|,pastetext,pasteword,pastetext,fullscreen,pagebreak,preview",
										theme_advanced_buttons2 : "",
										theme_advanced_buttons3 : "",
										theme_advanced_toolbar_location : "top",
										theme_advanced_toolbar_align : "left",
										theme_advanced_statusbar_location : "bottom",
										theme_advanced_resizing : false,
										convert_urls : false,                    
										content_CSS : "css/content.css",               
										external_link_list_url : "lists/link_list.js",
										external_image_list_url : "lists/image_list.js",
										media_external_list_url : "lists/media_list.js"
									});
							';	
				} else {
					$HTML .= '		
									tinyMCE.init({
										mode : "exact",
										elements : "editor",
										theme : "simple",
										editor_selector : "mceSimple"
									});
							';	
				}				
				
				$HTML .= '	function insertHTML(content) {
								parent.tinyMCE.execCommand(\'mceInsertContent\', false, content);
							}
						</script>';
			}
			
			return $HTML;
		}
	}
}
?>