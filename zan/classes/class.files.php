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
 */

/**
 * Access from index.php
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

/**
 * ZanPHP Files Class
 *
 * This class is used to upload files to server
 *
 * @package		ZanPHP
 * @subpackage	core
 * @category	classes
 * @author		MilkZoft Developer Team
 * @link		http://www.zanphp.com/documentation/en/classes/files_class
 */
class ZP_Files extends ZP_Load {

	/**
	 * Contains the error if the file has one
	 * 
	 * @var public $fileError
	 */
	public $fileError;	
	
	/**
	 * Contains the name of the file
	 * 
	 * @var public $filename
	 */
	public $filename;
	
	/**
	 * Contains the size in bytes of the file
	 * 
	 * @var public $fileSize
	 */
	public $fileSize;
	
	/**
	 * Contains the temporal name of the file
	 * 
	 * @var public $fileTmp
	 */
	public $fileTmp;
	
	/**
	 * Contains the mime type of the file
	 * 
	 * @var public $fileType
	 */
	public $fileType;
	
	public function __construct() {
		$this->config("files");
		$this->config("images");	
	}
	
    /**
     * Get the type of a file and divide into audios, documents, images or videos
     *
     * @param string $ext
     * @param boolean $mimeType = FALSE
     * @param boolean $return = FALSE
     * @return void
     */
	public function getType($ext, $mimeType = FALSE, $return = FALSE, $icons = FALSE) {	
		if(!$mimeType) {
			$ext   = strtolower($ext);		
			$parts = explode("/", $ext);
			
			if(count($parts) === 2) {
				$ext = $parts[1];
			}
		} else {
			$ext   = strtolower($ext);		
			$parts = explode(_dot, $ext);
			
			if(count($parts) === 2) {
				$ext = $parts[1];
			}
		}
						
		if($ext === "vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
			$ext = "xlsx";
		} elseif($ext === "vnd.openxmlformats-officedocument.presentationml.presentation") {
			$ext = "pptx";
		} elseif($ext === "vnd.openxmlformats-officedocument.wordprocessingml.document") {
			$ext = "docx";
		} elseif($ext === "msword") {
			$ext = "doc";
		} elseif($ext === "vnd.ms-excel") {
			$ext = "xls";
		} elseif($ext === "vnd.ms-powerpoint") {
			$ext = "ppt";		
		} elseif($ext === "plain") {
			$ext = "txt";
		} elseif($ext === "x-rar") {
			$ext = "rar";
		} elseif($ext === "octet-stream") {
			$ext = $ext2[1];
		} elseif($ext === "pjpeg") {
			$ext = "jpg";
		} elseif($ext === "x-png") {
			$ext = "png";
		}
		
		if($icons) {
			if($ext === "txt") {
				$icon[0] = _webURL . _sh . _www . _sh . _lib . _sh . _images . _sh . _icons . _sh . _files . _sh . "text.png";
				$icon[1] = __("Text File");
			} elseif($ext === "doc" or $ext === "docx") {
				$icon[0] = _webURL . _sh . _www . _sh . _lib . _sh . _images . _sh . _icons . _sh . _files . _sh . "doc.png";
				$icon[1] = __("Document File");
			} elseif($ext === "pdf") {	
				$icon[0] = _webURL . _sh . _www . _sh . _lib . _sh . _images . _sh . _icons . _sh . _files . _sh . "pdf.png";
				$icon[1] = __("PDF File");
			} elseif($ext === "ppt" or $ext === "pptx") {
				$icon[0] = _webURL . _sh . _www . _sh . _lib . _sh . _images . _sh . _icons . _sh . _files . _sh . "ppt.png";
				$icon[1] = __("Power Point File");
			} elseif($ext === "rar" or $ext === "iso") {
				$icon[0] = _webURL . _sh . _www . _sh . _lib . _sh . _images . _sh . _icons . _sh . _files . _sh . "rar.png";
				$icon[1] = __("Winrar File");
			} elseif($ext === "xls" or $ext === "xlsx" or $ext === "csv") {
				$icon[0] = _webURL . _sh . _www . _sh . _lib . _sh . _images . _sh . _icons . _sh . _files . _sh . "xls.png";
				$icon[1] = __("Excel File");
			} elseif($ext === "zip") {
				$icon[0] = _webURL . _sh . _www . _sh . _lib . _sh . _images . _sh . _icons . _sh . _files . _sh . "zip.png";
				$icon[1] = __("Winzip File");
			} elseif($ext === "7z") {
				$icon[0] = _webURL . _sh . _www . _sh . _lib . _sh . _images . _sh . _icons . _sh . _files . _sh . "7z.png";
				$icon[1] = __("7z File");				
			} elseif($ext === "ai" or $ext === "svg") {
				$icon[0] = _webURL . _sh . _www . _sh . _lib . _sh . _images . _sh . _icons . _sh . _files . _sh . "ai.png";
				$icon[1] = __("Adobe Illustrator File");								
			} elseif($ext === "cdr") {
				$icon[0] = _webURL . _sh . _www . _sh . _lib . _sh . _images . _sh . _icons . _sh . _files . _sh . "cdr.png";
				$icon[1] = __("Corel Draw File");				
			} elseif($ext === "exe" or $ext === "msi") {
				$icon[0] = _webURL . _sh . _www . _sh . _lib . _sh . _images . _sh . _icons . _sh . _files . _sh . "exe.png";
				$icon[1] = __("Executable File");				
			}	
					
			return $icon;
		}
					
		if($return) {
			return $ext;
		}
					
		if($ext	=== "wav") {
			return "audio";
		} elseif($ext === "midi") {
			return "audio";
		} elseif($ext === "mid") {
			return "audio";
		} elseif($ext === "mp3") {
			return "audio";
		} elseif($ext === "wma") {
			return "audio";
		} elseif($ext === "7z") {
			return "document";
		} elseif($ext === "ai") {
			return "document";
		} elseif($ext === "cdr") {
			return "document";
		} elseif($ext === "csv") {
			return "document";
		} elseif($ext === "doc") {
			return "document";
		} elseif($ext === "docx") {
			return "document";
		} elseif($ext === "fla") {
			return "document";
		} elseif($ext === "exe") {
			return "document";
		} elseif($ext === "iso") {
			return "document";
		} elseif($ext === "msi") {
			return "document";
		} elseif($ext === "pdf") {
			return "document";
		} elseif($ext === "ppt") {
			return "document";
		} elseif($ext === "pptx") {
			return "document";
		} elseif($ext === "psd") {
			return "document";
		} elseif($ext === "rar") {
			return "document";
		} elseif($ext === "svg") {
			return "document";
		} elseif($ext === "swf") {
			return "document";
		} elseif($ext === "txt") {
			return "document";
		} elseif($ext === "xls") {
			return "document";
		} elseif($ext === "xlsx") {
			return "document";
		} elseif($ext === "zip") {
			return "document";
		} elseif($ext === "jpg") {
			return "image";
		} elseif($ext === "jpeg") {
			return "image";
		} elseif($ext === "png") {
			return "image";
		} elseif($ext === "gif") {
			return "image";
		} elseif($ext === "bmp") {
			return "image";	
		} elseif($ext === "mpg") {
			return "video";
		} elseif($ext === "mpeg") {
			return "video";
		} elseif($ext === "avi") {
			return "video";
		} elseif($ext === "wmv") {
			return "video";
		} elseif($ext === "asf") {
			return "video";		
		} elseif($ext === "mp4") {
			return "video";
		} elseif($ext === "flv") {
			return "video";
		} elseif($ext === "mov") {
			return "video";			
		} 
		
		return FALSE;
	}
	
    /**
     * Upload a file to specific path
     *
     * @param string $path = NULL
     * @param string $type = "Image"
     * @return string value
     */							
	public function upload($path = NULL, $type = "image") {	
		ini_set("post_max_size", 18388608);
		ini_set("upload_max_filesize", 18388608);
		ini_set("max_execution_time", "1000");
		ini_set("max_input_time", "1000");	
		
		$ext   = strtolower($this->fileType);		
		$parts = explode("/", $ext);
		
		if(count($parts) === 2) {
			$ext = $parts[1];	
		} 

		$ext2 = explode(_dot, $this->filename);
		
		if($ext === "vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
			$ext = "xlsx";
		} elseif($ext === "vnd.openxmlformats-officedocument.presentationml.presentation") {
			$ext = "pptx";
		} elseif($ext === "vnd.openxmlformats-officedocument.wordprocessingml.document") {
			$ext = "docx";
		} elseif($ext === "msword") {
			$ext = "doc";
		} elseif($ext === "vnd.ms-excel") {
			$ext = "xls";
		} elseif($ext === "vnd.ms-powerpoint") {
			$ext = "ppt";		
		} elseif($ext === "plain") {
			$ext = "txt";
		} elseif($ext === "x-rar") {
			$ext = "rar";
		} elseif($ext === "octet-stream") {
			$ext = $ext2[1];
		} elseif($ext === "pjpeg") {
			$ext = "jpg";
		} elseif($ext === "jpeg") {
			$ext = "jpg";
		} elseif($ext === "x-png") {
			$ext = "png";
		}

		if($this->filename !== "") {
			$parts = explode(_dot, $this->filename);
		}
		
		if(count($parts) > 0) {
			if($parts[1] === "csv") {
				$ext = "csv";
			} elseif($parts[1] === "msi") {
				$ext = "msi";
			}
		}
		
		$filename = code(5, FALSE) . "_" . slug($parts[0]) . _dot . $ext;
		$file     = $path . $filename;		
		
		if(file_exists($file)) {
			$error["upload"]   = FALSE;
			$error["message"]  = "The file already exists";
			$error["filename"] = $filename; 
		} elseif($this->fileSize > _fileSize) {
			$error["upload"]  = FALSE;
			$error["message"] = "The file size exceed the permited limit"; 
		} elseif($this->fileError === 1) {
			$error["upload"]  = FALSE;
			$error["message"] = "An error has ocurred"; 
		} elseif($this->getType($this->fileType) !== $type) {
			$error["upload"]  = FALSE;
			$error["message"] = "The file type is not permited"; 
		} elseif(move_uploaded_file($this->fileTmp, $file)) {
			@chmod($path . $filename, 0777);
			
			$error["upload"]   = TRUE;
			$error["message"]  = "The image has been upload correctly"; 
			$error["filename"] = $filename; 
		} else {
			$error["upload"]  = FALSE;
			$error["message"] = "A problem occurred when trying to upload file";
		}
		
		return $error;
	}

    /**
     * Upload and resize an image
     *
     * @param string $dir
     * @param string $name = "File"
     * @return array value
     */	
	public function uploadImage($dir, $inputName = "file", $type = "resize", $small = TRUE, $medium = TRUE, $original = TRUE) {
		if(!is_dir($dir)) {
			@mkdir($dir, 0777);
		}
		
		if(FILES($inputName, "name")) {
			$this->filename  = FILES($inputName, "name");
			$this->fileType  = FILES($inputName, "type");
			$this->fileSize  = FILES($inputName, "size");
			$this->fileError = FILES($inputName, "error");
			$this->fileTmp   = FILES($inputName, "tmp_name");
		} else {
			return FALSE;
		}
		
		$upload = $this->upload($dir);
		
		if(!$upload["upload"]) {			
			return FALSE;
		}
		
		$this->Images = $this->core("Images");
		
		$this->Images->load($dir . $upload["filename"]);
		
		if($type === "normal") {
			return $dir . $upload["filename"];
		} elseif($type === "resize") {					
			if($small) {
				$size["small"] = $this->Images->getResize("small", $dir, $upload["filename"]);	
			}
			
			if($medium) {
				$size["medium"] = $this->Images->getResize("medium", $dir, $upload["filename"], _minMedium, _maxMedium);
			}
			
			if($original) {
				$size["original"] = $this->Images->getResize("original", $dir, $upload["filename"], _minOriginal, _maxOriginal);
			}
			
			@unlink($dir . $upload["filename"]);
				
			return $size;
		} elseif($type === "mural") { 
			if($this->Images->getWidth() !== _muralWidth and $this->Images->getHeight() !== _muralHeight) { 
				unlink($dir . $upload["filename"]); 
				
				$alert["alert"] = getAlert("The mural image is too big"); 
				
				return $alert;
			} else { 
				return $dir . $upload["filename"];
			}
		}
	}
	
}
