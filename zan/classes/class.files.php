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
				$ext   = $parts[1];
				$parts = explode(".", $ext);
			}
		} else {
			$ext   = strtolower($ext);		
			$parts = explode(".", $ext);
			
			if(count($parts) === 2) {
				$ext = $parts[1];
			}
		}
		
		$extensions = array(
			     		"vnd.openxmlformats-officedocument.spreadsheetml.sheet" 		=> "xlsx",
			     		"vnd.openxmlformats-officedocument.presentationml.presentation" => "pptx",
			     		"vnd.openxmlformats-officedocument.wordprocessingml.document"	=> "docx",
			     		"msword"														=> "doc",
			      		"vnd.ms-excel"													=> "xls",
			      		"vnd.ms-powerpoint"												=> "ppt",
			      		"plain"															=> "txt",
			      		"x-rar"															=> "rar",
			      		"octet-stream"													=> $parts[1],
			      		"pjpeg"															=> "jpg",
			      		"x-png"															=> "png"
			      	);
		
		foreach($extensions as $extension => $e) {
			if($ext === $extension) {
				$ext = $e;
				
				break;
			}
		}
		
		if($icons) {
			$icons = array(
					"txt"  => array(_webURL ."www/lib/images/icons/files/text.png", __(_("Text File"))),
					"doc"  => array(_webURL ."/www/lib/images/icons/files/doc.png", __(_("Document File"))),
					"docx" => array(_webURL ."/www/lib/images/icons/files/doc.png", __(_("Document File"))),
				 	"pdf"  => array(_webURL ."/www/lib/images/icons/files/pdf.png", __(_("PDF File"))),
				 	"ppt"  => array(_webURL ."/www/lib/images/icons/files/ppt.png", __(_("Power Point File"))),
				 	"pptx" => array(_webURL ."/www/lib/images/icons/files/ppt.png", __(_("Power Point File"))),
				 	"rar"  => array(_webURL ."/www/lib/images/icons/files/rar.png", __(_("WinRAR File"))),
				 	"iso"  => array(_webURL ."/www/lib/images/icons/files/rar.png", __(_("ISO File"))),
				 	"xls"  => array(_webURL ."/www/lib/images/icons/files/xls.png", __(_("Excel File"))),
				 	"xlsx" => array(_webURL ."/www/lib/images/icons/files/xls.png", __(_("Excel File"))),
				 	"csv"  => array(_webURL ."/www/lib/images/icons/files/xls.png", __(_("Excel File"))),
				 	"zip"  => array(_webURL ."/www/lib/images/icons/files/zip.png", __(_("WinZIP File"))),
				 	"7z"   => array(_webURL ."/www/lib/images/icons/files/7z.png",  __(_("7z File"))),
				 	"ai"   => array(_webURL ."/www/lib/images/icons/files/ai.png",  __(_("Adobe Illustrator File"))),
				 	"svg"  => array(_webURL ."/www/lib/images/icons/files/ai.png",  __(_("Adobe Illustrator File"))),
				 	"cdr"  => array(_webURL ."/www/lib/images/icons/files/cdr.png", __(_("Corel Draw File"))),
				 	"msi"  => array(_webURL ."/www/lib/images/icons/files/exe.png", __(_("Executable File"))),
				 );
						
			foreach($icons as $extension => $icon) {
				if($ext === $extension) {
					return $icon;
				}
			}	
			
			return $icon;
		}
					
		if($return) {
			return $ext;
		}

		$audio 	  = array("wav", "midi", "mid", "mp3", "wma");
		$document = array("7z", "ai", "cdr", "csv", "doc", "docx", "fla", "exe", "iso", "msi", "pdf", "ppt", "pptx", "psd", "rar", "svg", "swf", "txt", "xls", "xlsx", "zip");
		$image    = array("jpg", "jpeg", "png", "gif", "bmp");
		$video 	  = array("mpg", "mpeg", "avi", "wmv", "asf", "mp4", "flv", "mov");

		if(in_array($ext, $audio)) {
			return "audio";
		} elseif(in_array($ext, $document)) {
			return "document";
		} elseif(in_array($ext, $image)) {
			return "image";
		} elseif(in_array($ext, $video)) {
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
		
		if($ext === "") {
			$error["upload"]  = FALSE;
			$error["message"] = "A problem occurred when trying to upload file";

			return $error;
		}

		if(count($parts) === 2) {
			$ext = $parts[1];	
		} 

		$ext2 = explode(".", $this->filename);
		
		$extensions = array(
			     		"vnd.openxmlformats-officedocument.spreadsheetml.sheet" 		=> "xlsx",
			     		"vnd.openxmlformats-officedocument.presentationml.presentation" => "pptx",
			     		"vnd.openxmlformats-officedocument.wordprocessingml.document"	=> "docx",
			     		"msword"														=> "doc",
			      		"vnd.ms-excel"													=> "xls",
			      		"vnd.ms-powerpoint"												=> "ppt",
			      		"plain"															=> "txt",
			      		"x-rar"															=> "rar",
			      		"octet-stream"													=> $ext2[1],
			      		"pjpeg"															=> "jpg",
			      		"x-png"															=> "png"
			      	);
		
		foreach($extensions as $extension => $e) {
			if($ext === $extension) {
				$ext = $e;
				
				break;
			}
		}

		if($this->filename !== "") {
			$parts = explode(".", $this->filename);
		}
		
		if(count($parts) > 0) {
			if($parts[1] === "csv") {
				$ext = "csv";
			} elseif($parts[1] === "msi") {
				$ext = "msi";
			}
		}
		
		$filename = code(5, FALSE) . "_" . slug($parts[0]) . "." . $ext;
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
		} elseif(@move_uploaded_file($this->fileTmp, $file)) {
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