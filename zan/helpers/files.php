<?php
function createZip($files = array(), $destination = NULL, $overwrite = FALSE) {  
    if(file_exists($destination) and !$overwrite) { 
	    return FALSE; 
	}  
      
    $validFiles = array();  
      
    if(is_array($files)) {    
        foreach($files as $file) {    
            if(file_exists($file)) {  
                $validFiles[] = $file;  
            }  
        }  
    }  
     
    if(count($validFiles)) {    
        $Zip = new ZipArchive();
          
        if(!$Zip->open($destination, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE)) {  
            return FALSE;  
        }  
         
        foreach($validFiles as $file) {  
            $Zip->addFile($file, $file);  
        }  
 
        $Zip->close();  
  
        return file_exists($destination);  
    } else {  
        return false;  
    }  
}  

/**
 * FILES
 * 
 * Gets a specific position value from $_FILES
 * 
 * @param mixed  $name   = FALSE
 * @param string $coding = NULL
 * @return mixed
 */ 
function FILES($name = FALSE, $position = NULL, $i = NULL) {
	if(!$name) {
		____($_FILES);
	} elseif($position === NULL) {
		return isset($_FILES[$name]) ? $_FILES[$name] : FALSE;
	} elseif($i !== NULL and is_numeric($i)) {
		return isset($_FILES[$name][$position][$i]) ? $_FILES[$name][$position][$i] : FALSE;
	} else {
		return isset($_FILES[$name][$position]) ? $_FILES[$name][$position] : FALSE;
	}
}

/**
 * getFileSize
 * 
 * 
 *
 * @param string $position
 * @param string $coding = "decode"
 * @return string $coding = "decode"
 */
function getFileSize($size) {	
	if($size <= 0) {
		return FALSE;		
	} elseif($size < 1048576) {
		return round($size / 1024, 2) ." Kb";
	} else {
		return round($size / 1048576, 2) ." Mb";
	}
}

function unzipFile($file, $destination) {  
    $Zip = new ZipArchive();  
    
    if(!$Zip->open($file)) {  
        die("Could not open archive");  
    }  
      
    $Zip->extractTo($destination);  
    $Zip->close();  
}  