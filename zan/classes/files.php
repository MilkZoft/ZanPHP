<?php
if (!defined("ACCESS")) {
	die("Error: You don't have permission to access here...");
}

class ZP_Files extends ZP_Load 
{
	public $fileError = null;	
	public $filename = null;
	public $fileSize = null;
	public $fileTmp = null;
	public $fileType = null;
	
	public function __construct() 
	{
		$this->helper("debugging");
	}

	public function createFiles($names = false, $files = false, $types = false, $sizes = false) 
	{
	    if (!is_array($names) and !is_array($files) and !is_array($types)) {
	        return false;
	    }
	    
	    for($i = 0; $i <= count($files) - 1; $i++) {
	        $data = $files[$i];
	        $type = $types[$i];
	        
	        ini_set("upload_max_filesize", "50M");
	        ini_set("memory_limit", "256M");
	        ini_set("max_execution_time", 300);
	        
	        $parts = explode(".", $names[$i]);
	        $filename = substr(sha1($parts[0]), 0, 15);
	        $extension = end($parts);
	        $dir = "unknown"; 

	        switch($type) {
	            case 'image/jpeg':
	            	$extension = "jpg";
	            	$dir = "images";
	            	break;
	            case 'image/png': 
	            	$extension = "png";
	            	$dir = "images";
	            	break;
	            case 'image/gif':
	            	$extension = "gif";
	            	$dir = "images";
	            	break;
	            case 'application/msword':
	            	$extension = "doc";
	            	$dir = "documents";
	            	break;
	            case 'application/vnd.ms-powerpoint':
	            	$extension = "ppt";
	            	$dir = "documents";
	            	break;
	            case 'application/vnd.ms-excel':
	            	$extension = "xls";
	            	$dir = "documents";
	            	break;
	            case 'application/pdf':
	            	$extension = "pdf"; 
	            	$dir = "documents";
	            	break;
	            case 'text/plain':
	            	$extension = "txt"; 
	            	$dir = "documents";
	            	break;
	            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
	            	$extension = "docx";
	            	$dir = "documents";
	            	break;
	            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
	            	$extension = "xlsx";
	            	$dir = "documents";
	            	break;
	            case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
	            	$extension = "pptx";
	            	$dir = "documents";
	            	break;
	            case 'application/zip':
	            	$extension = "zip";
	            	$dir = "documents";
	            	break;
	            case 'text/php':
	            	$extension = "php";
	            	$dir = "codes";
	            	break;
	            case 'text/html':
	            	$extension = "html";
	            	$dir = "codes";
	            	break;
	            case 'text/javascript':
	            	$extension = "js";
	            	$dir = "codes";
	            	break;
	            case 'text/css':
	            	$extension = "css";
	            	$dir = "codes";
	            	break;
	            case 'audio/mp3':
	            	$extension = "mp3";
	            	$dir = "audio";
	            	break;
	            case 'video/mp4':
	            	$extension = "mp4";
	            	$dir = "videos";
	            	break;
	            case 'octet-stream':
	            	$extension = "exe";
	            	$dir = "programs";
	            	break;
	        }

	        $data = str_replace("data:$type;base64,", "", $data);
	        $data = str_replace(" ", "+", $data);
	        $data = base64_decode($data);

	        if (file_exists("www/lib/files/$dir/$filename.$extension")) {
	            $filename = substr(sha1(code(10)), 0, 15);
	        }

	        file_put_contents("www/lib/files/$dir/$filename.$extension", $data);

	        if (file_exists("www/lib/files/$dir/$filename.$extension")) {
		        $f[$i] = array(
		        	"filename" => $names[$i],
		        	"url" 	   => "www/lib/files/$dir/$filename.$extension",
		        	"category" => $dir,
		        	"size" 	   => $sizes[$i]
		        );
	        } else {
	        	return false;
	        }
	    }

	    return $f;
	}
	
	public function deleteFiles($files) 
	{
		if (is_array($files)) {
			foreach ($files as $file) {
				@unlink($file);
			}

			return true;
		} else {
			return false;
		}
	}

	public function getFileInformation($filename = false) 
	{
		if (!$this->filename and !$filename) {
			return false;
		} else {
			$filename = ($this->filename) ? $this->filename : $filename;
		}

		$file["icon"] = null;

		$parts = explode(".", $filename);

		if (is_array($parts)) {
			$file["name"] = $parts[0]; 
			$file["extension"] = array_pop($parts);

			$audio 	  = array("wav", "midi", "mid", "mp3", "wma");
			$codes 	  = array("asp", "php", "c", "as", "html", "js", "css", "rb");
			$document = array("csv", "doc", "docx", "pdf", "ppt", "pptx", "txt", "xls", "xlsx");
			$image 	  = array("jpg", "jpeg", "png", "gif", "bmp");
			$programs = array("7z", "ai", "cdr", "fla", "exe", "dmg", "pkg", "iso", "msi", "psd", "rar", "svg", "swf", "zip");
			$video    = array("mpg", "mpeg", "avi", "wmv", "asf", "mp4", "flv", "mov");

			if (in_array(strtolower($file["extension"]), $audio)) {
				$file["type"] = "audio";
			} elseif (in_array(strtolower($file["extension"]), $codes)) {
			 	$file["type"] = "code";
			} elseif (in_array(strtolower($file["extension"]), $document)) {
				$file["type"] = "document";
			} elseif (in_array(strtolower($file["extension"]), $image)) {
				$file["type"] = "image";
			} elseif (in_array(strtolower($file["extension"]), $video)) {
				$file["type"] = "program";
			} elseif (in_array(strtolower($file["extension"]), $video)) {
				$file["type"] = "video";
			} else {
				$file["type"] = "unknown";
			}

			$icons = array(
				"txt"  => array(_get("webURL") ."www/lib/images/icons/files/text.png", __("Text File")),
				"doc"  => array(_get("webURL") ."/www/lib/images/icons/files/doc.png", __("Document File")),
				"docx" => array(_get("webURL") ."/www/lib/images/icons/files/doc.png", __("Document File")),
			 	"pdf"  => array(_get("webURL") ."/www/lib/images/icons/files/pdf.png", __("PDF File")),
			 	"ppt"  => array(_get("webURL") ."/www/lib/images/icons/files/ppt.png", __("Power Point File")),
			 	"pptx" => array(_get("webURL") ."/www/lib/images/icons/files/ppt.png", __("Power Point File")),
			 	"rar"  => array(_get("webURL") ."/www/lib/images/icons/files/rar.png", __("WinRAR File")),
			 	"iso"  => array(_get("webURL") ."/www/lib/images/icons/files/rar.png", __("ISO File")),
			 	"xls"  => array(_get("webURL") ."/www/lib/images/icons/files/xls.png", __("Excel File")),
			 	"xlsx" => array(_get("webURL") ."/www/lib/images/icons/files/xls.png", __("Excel File")),
			 	"csv"  => array(_get("webURL") ."/www/lib/images/icons/files/xls.png", __("Excel File")),
			 	"zip"  => array(_get("webURL") ."/www/lib/images/icons/files/zip.png", __("WinZIP File")),
			 	"7z"   => array(_get("webURL") ."/www/lib/images/icons/files/7z.png",  __("7z File")),
			 	"ai"   => array(_get("webURL") ."/www/lib/images/icons/files/ai.png",  __("Adobe Illustrator File")),
			 	"svg"  => array(_get("webURL") ."/www/lib/images/icons/files/ai.png",  __("Adobe Illustrator File")),
			 	"cdr"  => array(_get("webURL") ."/www/lib/images/icons/files/cdr.png", __("Corel Draw File")),
			 	"msi"  => array(_get("webURL") ."/www/lib/images/icons/files/exe.png", __("Executable File")),
			 	"exe"  => array(_get("webURL") ."/www/lib/images/icons/files/exe.png", __("Executable File")),
			 	"dmg"  => array(_get("webURL") ."/www/lib/images/icons/files/exe.png", __("Executable File")),
			 	"pkg"  => array(_get("webURL") ."/www/lib/images/icons/files/exe.png", __("Executable File")),
			);
						
			foreach($icons as $extension => $icon) { 
				if ($file["extension"] === $extension) {
					$file["icon"] = $icon;
					
					break;
				}
			}	
			
			return $file;
		}

		return false;
	}

	public function upload($path = null, $type = "image")
	{	
		ini_set("post_max_size", 18388608);
		ini_set("upload_max_filesize", 18388608);
		ini_set("max_execution_time", "1000");
		ini_set("max_input_time", "1000");

		$file = $this->getFileInformation();

		if (!$file) {
			$error["upload"] = false;
			$error["message"] = "A problem occurred when trying to upload file";
			
			return $error;
		}
		
		if (strlen($file["name"]) > 50) {
			$filename = code(5, false) ."_". slug($file["name"]) .".". $file["extension"];
		} else {
			$filename = slug($file["name"]) .".". $file["extension"];
		}

		$URL = $path . $filename;

		if (file_exists($URL)) { 
			$error = array(
				"upload"   => false,
				"message"  => "The file already exists",
				"filename" => $filename
			); 
		} elseif ($this->fileSize > FILE_SIZE) { 
			$error = array(
				"upload"  => false,
				"message" => "The file size exceed the permited limit"
			);
		} elseif ($this->fileError === 1) {
			$error = array(
				"upload"  => false,
				"message" => "An error has ocurred"
			);
		} elseif ($file["type"] !== $type) {
			$error = array(
				"upload"  => false,
				"message" => "The file type is not permited"
			);
		} elseif (move_uploaded_file($this->fileTmp, $URL)) {
			chmod($URL, 0777);
			
			$error = array(
				"upload"   => true,
				"message"  => "The file has been upload correctly",
				"filename" => $filename
			);
		} else { 
			$error = array(
				"upload"  => false,
				"message" => "A problem occurred when trying to upload file"
			);
		}
		
		return $error;
	}

	public function uploadImage($dir, $name = "file", $type = "resize", $sizes = array("t", "s", "m", "l", "o"))
	{
		if (!is_dir($dir)) {
			@mkdir($dir, 0777);
		}
		
		if (FILES($name, "name")) {
			$this->filename  = FILES($name, "name");
			$this->fileType  = FILES($name, "type");
			$this->fileSize  = FILES($name, "size");
			$this->fileError = FILES($name, "error");
			$this->fileTmp   = FILES($name, "tmp_name");
		} else {
			return false;
		}
		
		$upload = $this->upload($dir);

		if (!$upload["upload"]) {
			return false;
		}
		
		$this->Images = $this->core("Images");
		$this->Images->load($dir . $upload["filename"]);
		
		if ($type === "normal") {
			return $dir . $upload["filename"];
		} elseif ($type === "resize") {
			if (isset($sizes["t"])) {
				$size["thumbnail"] = $this->Images->getResize("thumbnail", $dir, $upload["filename"], MIN_THUMBNAIL, MAX_THUMBNAIL);
			}

			if (isset($sizes["s"])) {
				$size["small"] = $this->Images->getResize("small", $dir, $upload["filename"]);
			}
			
			if (isset($sizes["m"])) {
				$size["medium"] = $this->Images->getResize("medium", $dir, $upload["filename"], MIN_MEDIUM, MAX_MEDIUM);
			}
			
			if (isset($sizes["l"])) {
				$size["large"] = $this->Images->getResize("large", $dir, $upload["filename"], MIN_LARGE, MAX_LARGE);
			}

			if (isset($sizes["o"])) {
				$size["original"] = $dir . $upload["filename"];
			}

			return $size;
		} elseif ($type === "mural") { 
			if ($this->Images->getWidth() !== MURAL_WIDTH and $this->Images->getHeight() !== MURAL_HEIGHT) {
				unlink($dir . $upload["filename"]); 
				
				$size = MURAL_WIDTH ."x". MURAL_HEIGHT . __(" exactly.");
				
				$alert["alert"] = getAlert(__("The mural image's resolution must be ") . $size);
				
				return $alert;
			} else { 
				return $dir . $upload["filename"];
			}
		}
	}

	public function resize($dir, $filename, $thumbnail = true, $small = true, $medium = true) 
	{
		$this->Images = $this->core("Images");
		
		return array(
			"thumbnail" => ($thumbnail) ? $this->Images->getResize("thumbnail", $dir, $filename) : null,
			"small" 	=> ($small) ? $this->Images->getResize("small", $dir, $filename) : null,
			"medium" 	=> ($medium) ? $this->Images->getResize("medium", $dir, $filename) : null
		);
	}

	public function createFileFromBase64($data, $filename = false)
	{
		$start = strpos($data, ",") + 1;
		$base64 = substr($data, $start);
		$base64 = str_replace(" ", "+", $base64);
        $data = base64_decode($base64);

        if (is_string($filename)) {
        	if (file_put_contents($filename, $data, LOCK_EX) === false) {
        		return false;
        	}
        	
        	if (file_exists($filename)) {
        		return true;
        	} else {
        		return false;
        	}
        }

        return $data;
	}
}