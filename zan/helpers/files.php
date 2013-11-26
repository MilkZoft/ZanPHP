<?php
if (!defined("ACCESS")) {
    die("Error: You don't have permission to access here...");
}

if (!function_exists("createZip")) {
    function createZip($files = array(), $destination = null, $overwrite = false)
    {  
        if (file_exists($destination) and !$overwrite) { 
    	    return false; 
    	}  
          
        $validFiles = array();  
          
        if (is_array($files)) {    
            foreach ($files as $file) {    
                if (file_exists($file)) {  
                    $validFiles[] = $file;  
                }  
            }  
        }  
         
        if (count($validFiles)) {    
            $Zip = new ZipArchive();
              
            if (!$Zip->open($destination, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE)) {  
                return false;  
            }  
             
            foreach ($validFiles as $file) {  
                $Zip->addFile($file, $file);  
            }  
     
            $Zip->close();  
            return file_exists($destination);  
        } else {  
            return false;  
        }  
    }  
}

if (!function_exists("FILES")) {
    function FILES($name = false, $position = null, $i = null)
    {
    	if (!$name) {
    		____($_FILES);
    	} elseif ($position === null) {
    		return isset($_FILES[$name]) ? $_FILES[$name] : false;
    	} elseif ($i !== null and is_numeric($i)) {
    		return isset($_FILES[$name][$position][$i]) ? $_FILES[$name][$position][$i] : false;
    	} else {
    		return isset($_FILES[$name][$position]) ? $_FILES[$name][$position] : false;
    	}
    }
}

if (!function_exists("getExtension")) {
    function getExtension($filename = false)
    {
        $extension = explode(".", $filename);
        return end($extension);
    }
}

if (!function_exists("getFilesFromMultimedia")) {
    function getFilesFromMultimedia($multimedia)
    {
        $HTML = div("multimedia");       
         
        if ($multimedia) {     
            foreach ($multimedia as $category) {
                if ($category["audio"]) { 
                    $HTML .= '<span id="audio" class="pointer"><strong>'. __("Audio") .'</strong></span><br /><ul id="multimedia-list-audio" class="multimedia-list">';                        
                    $count = count($category["audio"]) - 1;

                    for ($i = 0; $i <= $count; $i++) {
                        $HTML .= '<li><a class="pointer" onclick="javascript:add(\'audio\', \''. $category["audio"][$i]["Filename"] .'\', \''. path($category["audio"][$i]["URL"], true) .'\');">'. $category["audio"][$i]["Filename"] .'</a></li>';
                    }

                    $HTML .= '</ul>';
                } 

                if ($category["codes"]) {
                    $HTML .= '<span id="codes" class="pointer"><strong>'. __("Codes") .'</strong></span><br /><ul id="multimedia-list-codes" class="multimedia-list">';                        
                    $count = count($category["codes"]) - 1;

                    for ($i = 0; $i <= $count; $i++) {
                        $HTML .= '<li><a class="pointer" onclick="javascript:add(\'codes\', \''. $category["codes"][$i]["Filename"] .'\', \''. path($category["codes"][$i]["URL"], true) .'\');">'. $category["codes"][$i]["Filename"] .'</a></li>';
                    }

                    $HTML .= '</ul>';
                } 

                if ($category["documents"]) {
                    $HTML .= '<span id="documents" class="pointer"><strong>'. __("Documents") .'</strong></span><br /><ul id="multimedia-list-documents" class="multimedia-list">';                        
                    $count = count($category["documents"]) - 1;

                    for ($i = 0; $i <= $count; $i++) {
                        $HTML .= '<li><a class="pointer" onclick="javascript:add(\'documents\', \''. $category["documents"][$i]["Filename"] .'\', \''. path($category["documents"][$i]["URL"], true) .'\');">'. $category["documents"][$i]["Filename"] .'</a></li>';
                    }

                    $HTML .= '</ul>';
                } 

                if ($category["images"]) {
                    $HTML .= '<span id="images" class="pointer"><strong>'. __("Images") .'</strong></span><br /><ul id="multimedia-list-images" class="multimedia-list">';                        
                    $count = count($category["images"]) - 1;

                    for ($i = 0; $i <= $count; $i++) {
                        $HTML .= '<li><a class="pointer" onclick="javascript:add(\'images\', \''. $category["images"][$i]["Filename"] .'\', \''. path($category["images"][$i]["URL"], true) .'\');">'. $category["images"][$i]["Filename"] .'</a></li>';
                    }

                    $HTML .= '</ul>';
                }

                if ($category["programs"]) {
                    $HTML .= '<span id="programs" class="pointer"><strong>'. __("Programs") .'</strong></span><br /><ul id="multimedia-list-programs" class="multimedia-list">';                        
                    $count = count($category["programs"]) - 1;

                    for ($i = 0; $i <= $count; $i++) {
                        $HTML .= '<li><a class="pointer" onclick="javascript:add(\'programs\', \''. $category["programs"][$i]["Filename"] .'\', \''. path($category["programs"][$i]["URL"], true) .'\');">'. $category["programs"][$i]["Filename"] .'</a></li>';
                    }

                    $HTML .= '</ul>';
                }

                if ($category["unknown"]) {
                    $HTML .= '<span id="unknown" class="pointer"><strong>'. __("Unknown") .'</strong></span><br /><ul id="multimedia-list-unknown" class="multimedia-list">';                        
                    $count = count($category["unknown"]) - 1;

                    for ($i = 0; $i <= $count; $i++) {
                        $HTML .= '<li><a class="pointer" onclick="javascript:add(\'unknown\', \''. $category["unknown"][$i]["Filename"] .'\', \''. path($category["unknown"][$i]["URL"], true) .'\');">'. $category["unknown"][$i]["Filename"] .'</a></li>';
                    }

                    $HTML .= '</ul>';
                }

                if ($category["videos"]) {
                    $HTML .= '<span id="videos" class="pointer"><strong>'. __("Videos") .'</strong></span><br /><ul id="multimedia-list-videos" class="multimedia-list">';                        
                    $count = count($category["videos"]) - 1;

                    for ($i = 0; $i <= $count; $i++) {
                        $HTML .= '<li><a class="pointer" onclick="javascript:add(\'videos\', \''. $category["videos"][$i]["Filename"] .'\', \''. path($category["videos"][$i]["URL"], true) .'\');">'. $category["videos"][$i]["Filename"] .'</a></li>';
                    }

                    $HTML .= '</ul>';
                }

            }
        }
        
        $HTML .= div(false) . br();
        return $HTML;
    }
}

if (!function_exists("getFileSize")) {
    function getFileSize($size)
    {	
    	if ($size <= 0) {
    		return false;		
    	} elseif ($size < 1048576) {
    		return round($size / 1024, 2) ." Kb";
    	} else {
    		return round($size / 1048576, 2) ." Mb";
    	}
    }
}

if (!function_exists("unzipFile")) {
    function unzipFile($file, $destination)
    {  
        $Zip = new ZipArchive();  
        
        if (!$Zip->open($file)) {  
            die("Could not open archive");  
        }  
          
        $Zip->extractTo($destination);  
        $Zip->close();  
    } 
}