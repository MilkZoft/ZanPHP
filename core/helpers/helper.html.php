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
 	
function anchor($text, $URL = NULL, $title = NULL, $external = FALSE, $class = NULL, $onclick = NULL, $onchange = NULL, $onmouseover = NULL) {
	$title  = (is_null($title))  ? removeSpaces(cleanHTML($text)) : $title;
	$class  = (!is_null($class)) ? ' class="' . $class . '"' : NULL;
	$events = NULL;
	
	if(!is_null($onclick)) {
		$events  = ' onclick="' . $onclick . '"';
	}
	
	if(!is_null($onchange)) {
		$events .= ' onchange="' . $onchange . '"';
	}
	
	if(!is_null($onmouseover)) {
		$events .= ' onmouseover="' . $onmouseover . '"'; 
	}
	
	if($URL === FALSE) {
		return '<a title="' . $title . '"' . $class . ' name="' . $title . '"' . $events . '>' . $text . '</a>';
	} elseif($URL === NULL) {
		return '<a name="' . $text . '"></a>';	
	} elseif($external === TRUE) {
		return '<a rel="external" href="' . $URL . '" title="' . $title . '"' . $events . '>' . $text . '</a>';
	} else {
		return '<a href="' . $URL . '" title="' . $title . '"' . $events . '>' . $text . '</a>';
	}
}

function body($open = TRUE) {
	if($open === TRUE) {
		return "<body>" . char("\n");
	} else {
		return "</body>";
	}
}
	
function bold($text, $br = TRUE) {
	$HTML = '<span class="Bold">' . $text . '</span>'; 
	
	if($br === TRUE) {
		$HTML .= '<br />';
	}
	
	return $HTML;
}

function br($jumps = 1) {
	$br = NULL;
	
	for($i = 0; $i <= $jumps; $i++) {
		$br .= "<br />" . char("\n");
	}
	
	return $br;
}

function char($char, $repeat = 1) {
	$HTML = NULL;
	
	if(_webCharacters === TRUE) {
		for($i = 0; $i <= $repeat; $i++) {
			$HTML .= $char;
		}
		
		return $HTML;
	}
	
	return NULL;
}

function deleteImg($HTML) {
	return eregi_replace("<img[^<>]*/>", "", $HTML);	
}
	
function div($ID, $type = "id", $style = NULL, $content = NULL) { 
	if($ID === FALSE) {
		return '</div>' 										 . char("\n");
	} elseif($type === TRUE) {
		return '<div id="' . $ID . '">' . $content. '</div>' 	 . char("\n");		
	} elseif($type === FALSE) {
		return '<div class="' . $ID . '">' . $content . '</div>' . char("\n");
	} elseif(strtolower($type) === "id") {
		return '<div id="' . $ID . '">' 						 . char("\n\t");	
	} elseif(strtolower($type) === "id/class") {
		return '<div id="' . $ID . '" class="' . $style . '">'	 . char("\n\t");		
	} elseif(strtolower($type) === "class") {
		return '<div class="' . $ID . '">' 						 . char("\n\t");
	}
}

function docType($type = "XHTML 1.0 Strict") {
	if($type === "HTML 5") {
		return '<!DOCTYPE HTML>' . char("\n");
	} elseif($type === "XHTML 1.0 Strict") {
		return '<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xHTML1/DTD/xHTML1-strict.dtd">' . char("\n");
	}
}

function getHTMLDecode($HTML) {
	return html_entity_decode($HTML);
}

function h1($text) {
	return char("\t") . "<h1>$text</h1>" . char("\n");
}

function h2($text) {
	return char("\t") . "<h2>$text</h2>" . char("\n");
}

function h3($text) {
	return char("\t") . "<h3>$text</h3>" . char("\n");
}

function img($src, $alt = NULL, $class = "no-border", $attributes = NULL) {
	if(is_null($alt)) {
		return '<img src="' . $src . '" ' . $attributes . ' />';
	} elseif(!is_null($alt) and !is_null($class)) {
		return '<img src="' . $src . '" alt="' . $alt . '" title="' . $alt . '" class="' . $class . '" ' . $attributes . ' />';
	} elseif(!is_null($alt)) {
		return '<img src="' . $src . '" alt="' . $alt . '" title="' . $alt . '" ' . $attributes . ' />';
	} elseif(!is_null($class)) {
		return '<img src="' . $src . '" class="' . $class . '" ' . $attributes . ' />';
	}
}

function head($open = TRUE) {
	if($open === TRUE) {
		return "<head>"	 . char("\n\t");	
	} else {
		return "</head>" . char("\n\t");
	}
}

function HTML($open = TRUE) {
	if($open === TRUE) {
		return '<html xmlns="http://www.w3.org/1999/xhtml" lang="'._webLang.'" xml:lang="'._webLang.'">' . char("\n");
	} else {
		return "</html>";
	}
}
	
function p($text, $class = "left") {
	return char("\n\t") . '<p class="' . $class . '">'. char("\n\t\t") . $text . char("\n\t") . '</p>' . char("\n");
}

function small($text) {
	return '<span class="small">' . $text . '</span>';
}	

function span($class, $value) {
	return '<span class="' . $class . '">' . $value . '</span>';
}

function openUl($ID = NULL, $class = NULL) {
	$ID    = (!is_null($ID))    ? ' id="' . $ID . '"'       : NULL;
	$class = (!is_null($class)) ? ' class="' . $class . '"' : NULL; 
	
	return '<ul' . $ID . $class.'>' . char("\n");
}

function li($list) {
	$HTML = NULL;
	
	if(isMultiArray($list)) {		
		foreach($list as $li) {
			$class = (isset($li["class"])) ? ' class="' . $li["class"] . '"' : NULL;
			
			if(strlen($li["item"]) > 1) {
				$HTML .= char("\t", 2) . '<li' . $class . '>' . $li["item"] . '</li>' . char("\n");			
			}
		}
	} elseif(is_array($list)) {
		for($i = 0; $i <= count($list) - 1; $i++) {
			$HTML .= char("\t", 2) . '<li>' . $list[$i] . '</li>' . char("\n");
		}
	} else {
		$HTML .= char("\t", 2) . '<li>' . $list . '</li>' . char("\n");
	}
			
	return $HTML;
}

function closeUl() {
	return char("\t") . "</ul>" . char("\n");
}

function ul($list, $ID = NULL, $class = NULL) {
	$ID    = (!is_null($ID))    ? ' id="'.$ID.'"'       : NULL;
	$class = (!is_null($class)) ? ' class="'.$class.'"' : NULL;
	
	$HTML = '<ul' . $ID . $class . '>' . char("\t");
		if(isMultiArray($list)) {
			foreach($list as $li) {
				$class = (isset($li["class"])) ? ' class="'.$li["class"].'"' : NULL;
				
				$HTML .= char("\t", 2) . '<li' . $class . '>' . $li["item"] . '</li>' . char("\n");
			}
		} elseif(is_array($list)) {
			for($i = 0; $i <= count($list) - 1; $i++) {
				$HTML .= char("\t", 2) . '<li>' . $list[$i] . '</li>' . char("\n");
			}
		}
	$HTML .= char("\t") . '</ul>' . char("\n");
	
	return $HTML;
}

function loadCSS($CSS) {
	return '<link rel="stylesheet" href="'. _webURL . _sh . $CSS .'" type="text/css" media="all" />';
}

function loadScript($js, $application = NULL) {
	if(file_exists($js)) {		
		return '<script type="text/javascript" src="'. _webURL . _sh . $js .'"></script>';
	} else {
		if(isset($application)) {
			$file = _applications . _sh . $application . _sh . _views . _sh . _js . _sh . $js . _dot . _js;
			
			if(file_exists($file)) {
				return '<script type="text/javascript" src="'. _webURL . _sh . $file .'"></script>';
			}
		}
	}
}

function getScript($js, $application = NULL, $extra = NULL, $getJs = FALSE) {
	if(file_exists($js)) {		
		return loadScript($js);
	} else {
		if(isset($application)) {
			return loadScript($js, $application);
		} else {
			if($js === "jquery") {
				return loadScript("lib/scripts/js/jquery.js");
			} elseif($js === "nivo-slider") {
				$HTML .= loadScript("lib/scripts/js/nivo-slider/nivo-slider.js");
				$HTML .= loadCSS("lib/scripts/js/nivo-slider/themes/default/default.css");
				$HTML .= loadCSS("lib/scripts/js/nivo-slider/nivo-slider.css");
				$HTML .= '	<script type="text/javascript">
								$(window).load(function() {
									$(\'#slider\').nivoSlider();
								});	
							</script>';
			} elseif($js === "checkbox") {
				$HTML  = '	<script type="text/javascript">
								function checkAll(idForm) {
									for(i = 0; i < document.getElementById(idForm).elements.length; i++) {
										if(document.getElementById(idForm).elements[i].type == "checkbox") {
											document.getElementById(idForm).elements[i].checked = true;
										}
									}
								}
								
								function unCheckAll(idForm) {
									for(i = 0; i < document.getElementById(idForm).elements.length; i++) {
										if(document.getElementById(idForm).elements[i].type == "checkbox") {
											document.getElementById(idForm).elements[i].checked = false;
										}
									}
								}
							</script>';					
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
			} elseif($js === "insert-html") {
				$HTML  = '	<script type="text/javascript">
								function insertHTML(content) {
									parent.tinyMCE.execCommand(\'mceInsertContent\', false, content);
								}
							</script>
				
							<noscript><p class="no-display">'. __("Disable Javascript") .'</p></noscript>';
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
				if($extra !== "basic") {
					$HTML  = loadScript("lib/scripts/js/tiny_mce/tiny_mce.js");
					$HTML .= '	<script type="text/javascript">		
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
								</script>';	
				} else {
					$HTML  = loadScript("lib/scripts/js/tiny_mce/tiny_mce.js");
					$HTML .= '	<script type="text/javascript">		
									tinyMCE.init({
										mode : "exact",
										elements : "editor",
										theme : "simple",
										editor_selector : "mceSimple"
									});
								</script>';	
				}
			} elseif($js === "upload") {
				$iPx   = (POST("iPx"))            ? POST("iPx")                                                  : 'i';
				$iPath = (POST("iPath"))          ? POST("iPath")                                                : 'lib/files/images/uploaded/';
				$iPath = (POST($iPx . "Dirbase")) ? POST($iPx . "Dirbase")                                       : $iPath;
				$iPath = (POST($iPx . "Make"))    ? POST($iPx . "Dir") . nice(POST($iPx . "Dirname")) . _sh 	 : $iPath;				
					
				$dPx   = (POST("dPx"))   ? POST("dPx")   : "d";
				$dPath = (POST("dPath")) ? POST("dPath") : "lib/files/documents/uploaded/";
				
				$dPath = (POST($dPx . "Dirbase")) ? POST($dPx . "Dirbase")                                       : $dPath;
				$dPath = (POST($dPx . "Make"))    ? POST($dPx . "Dir") . nice(POST($dPx . "Dirname")) . _sh 	 : $dPath;
				
				$application = ucfirst(segment(2));
				?>
					<script type="text/javascript">
					<!-- 
						function uploadResponse(state, file) {
							var path, insert, ok, error, form, message; 
							
							path = '<?php print _webURL . _sh . $iPath;?>' + file;
							HTML = '\'<img src=\\\'' + path + '\\\' alt=\\\'' + file + '\\\' />\'';
							insert = '<li><input name="iLibrary[]" type="checkbox" value="' + path + '" /><span class="small">00<' + '/span>';
							insert = insert + '<a href="' + path + '" rel="external" title="<?php print __("Preview"); ?>"><span class="tiny-image tiny-search">&nbsp;&nbsp;&nbsp;&nbsp;</span><' + '/a>';
							insert = insert + '<a class="pointer" onclick="javascript:insertHTML(' + HTML + ');" title="<?php print __("Insert image"); ?>"><span class="tiny-image tiny-add">&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;';
							insert = insert + '<span class="bold">' + file + '<' + '/span><' + '/a><' + '/li>';						
							
							if(state == 1) {
								message = '<?php print __("The file size exceed the permited limit"); ?>';
							}
							
							if(state == 2) {
								message = '<?php print __("An error has ocurred"); ?>';
							}
							
							if(state == 3) {
								message = '<?php print __("The file type is not permited"); ?>';
							}
							
							if(state == 4) {
								message = '<?php print __("A problem occurred when trying to upload file"); ?>';
							}
							
							if(state == 5) {
								message = '<?php print __("The file already exists"); ?>';
							}
							
							if(state == 6) {
								message = '<?php print __("Successfully uploaded file"); ?>';
								document.getElementById('i-add-upload').innerHTML = insert + document.getElementById('i-add-upload').innerHTML;
							}
							
							document.getElementById('i-upload-message').innerHTML = message;
						}												
						
						function uploadDocumentsResponse(dState, dFile, dIcon, dAlt) {
							var dPath, dInsert, dOk, dError, dForm, dMessage, dHTML;
							
							dPath = '<?php print _webURL . _sh . $dPath; ?>' + dFile;					
							dHTML = '\'<a href=\\\'' + dPath + '\\\' title=\\\'' + dFile + '\\\'><img src=\\\'' + dIcon + '\\\' alt=\\\'' + dAlt + '\\\' /></a>\'';
							
							dInsert = '<li><input name="dLibrary[]" type="checkbox" value="' + dPath + '" />';
							dInsert = dInsert + '<span class="small">00<' + '/span><a href="' + dPath + '" title="<?php print __("Download file"); ?>">';
							dInsert = dInsert + '<span class="tiny-image tiny-file">&nbsp;&nbsp;&nbsp;&nbsp;</span><' + '/a>';
							dInsert = dInsert + '<a class="pointer" onclick="javascript:insertHTML(' + dHTML + ');" title="<?php print __("Insert file"); ?>">';
							dInsert = dInsert + '<span class="tiny-image tiny-add">&nbsp;&nbsp;&nbsp;&nbsp;</span>';
							dInsert = dInsert + '<span class="bold">' + dFile + '<' + '/span><' + '/a><' + '/li>';								
					
							if(dState == 1) {
								message = '<?php print __("The file size exceed the permited limit"); ?>';
							}
							
							if(dState == 2) {
								message = '<?php print __("An error has ocurred"); ?>';
							}
							
							if(dState == 3) {
								message = '<?php print __("The file type is not permited"); ?>';
							}
							
							if(dState == 4) {
								message = '<?php print __("A problem occurred when trying to upload file"); ?>';
							}
							
							if(dState == 5) {
								message = '<?php print __("The file already exists"); ?>';
							}								
							
							if(dState == 6) {
								message = '<?php print __("Successfully uploaded file"); ?>';
								document.getElementById('d-add-upload').innerHTML = dInsert + document.getElementById('d-add-upload').innerHTML;
							}
							
							document.getElementById('d-upload-message').innerHTML = message;
						}
					 -->
					</script>
					
					<noscript><p class="no-display"><?php print __("Disable Javascript"); ?></p></noscript>
				
				<?php
				return NULL;				
			}
			
			return $HTML;
		}
	}	
}