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
	public $fileError = NULL;	
	
	/**
	 * Contains the name of the file
	 * 
	 * @var public $filename
	 */
	public $filename = NULL;
	
	/**
	 * Contains the size in bytes of the file
	 * 
	 * @var public $fileSize
	 */
	public $fileSize = NULL;
	
	/**
	 * Contains the temporal name of the file
	 * 
	 * @var public $fileTmp
	 */
	public $fileTmp = NULL;
	
	/**
	 * Contains the mime type of the file
	 * 
	 * @var public $fileType
	 */
	public $fileType = NULL;
	
	public function __construct() {
		$this->helper("debugging");
	}
	
	public function getFileInformation($filename = FALSE) {
		if(!$this->filename and !$filename) {
			return FALSE;
		} else {
			$filename = ($this->filename) ? $this->filename : $filename;
		}

		$file["icon"] = NULL;

		$parts = explode(".", $filename);

		if(is_array($parts)) {
			$file["name"] 	   = $parts[0]; 
			$file["extension"] = array_pop($parts);

			$audio 	  = array("wav", "midi", "mid", "mp3", "wma");
			$codes    = array("asp", "php", "c", "as", "html", "js", "css", "rb");
			$document = array("csv", "doc", "docx", "pdf", "ppt", "pptx", "txt", "xls", "xlsx");
			$image    = array("jpg", "jpeg", "png", "gif", "bmp");
			$programs = array("7z", "ai", "cdr", "fla", "exe", "dmg", "pkg", "iso", "msi", "psd", "rar", "svg", "swf", "zip");
			$video 	  = array("mpg", "mpeg", "avi", "wmv", "asf", "mp4", "flv", "mov");

			if(in_array(strtolower($file["extension"]), $audio)) {
				$file["type"] = "audio";
			} elseif(in_array(strtolower($file["extension"]), $codes)) {
			 	$file["type"] = "code";
			} elseif(in_array(strtolower($file["extension"]), $document)) {
				$file["type"] = "document";
			} elseif(in_array(strtolower($file["extension"]), $image)) {
				$file["type"] = "image";
			} elseif(in_array(strtolower($file["extension"]), $video)) {
				$file["type"] = "program";
			} elseif(in_array(strtolower($file["extension"]), $video)) {
				$file["type"] = "video";
			} else {
				$file["type"] = "unknown";
			}

			$icons = array(
				"txt"  => array(get("webURL") ."www/lib/images/icons/files/text.png", __(_("Text File"))),
				"doc"  => array(get("webURL") ."/www/lib/images/icons/files/doc.png", __(_("Document File"))),
				"docx" => array(get("webURL") ."/www/lib/images/icons/files/doc.png", __(_("Document File"))),
			 	"pdf"  => array(get("webURL") ."/www/lib/images/icons/files/pdf.png", __(_("PDF File"))),
			 	"ppt"  => array(get("webURL") ."/www/lib/images/icons/files/ppt.png", __(_("Power Point File"))),
			 	"pptx" => array(get("webURL") ."/www/lib/images/icons/files/ppt.png", __(_("Power Point File"))),
			 	"rar"  => array(get("webURL") ."/www/lib/images/icons/files/rar.png", __(_("WinRAR File"))),
			 	"iso"  => array(get("webURL") ."/www/lib/images/icons/files/rar.png", __(_("ISO File"))),
			 	"xls"  => array(get("webURL") ."/www/lib/images/icons/files/xls.png", __(_("Excel File"))),
			 	"xlsx" => array(get("webURL") ."/www/lib/images/icons/files/xls.png", __(_("Excel File"))),
			 	"csv"  => array(get("webURL") ."/www/lib/images/icons/files/xls.png", __(_("Excel File"))),
			 	"zip"  => array(get("webURL") ."/www/lib/images/icons/files/zip.png", __(_("WinZIP File"))),
			 	"7z"   => array(get("webURL") ."/www/lib/images/icons/files/7z.png",  __(_("7z File"))),
			 	"ai"   => array(get("webURL") ."/www/lib/images/icons/files/ai.png",  __(_("Adobe Illustrator File"))),
			 	"svg"  => array(get("webURL") ."/www/lib/images/icons/files/ai.png",  __(_("Adobe Illustrator File"))),
			 	"cdr"  => array(get("webURL") ."/www/lib/images/icons/files/cdr.png", __(_("Corel Draw File"))),
			 	"msi"  => array(get("webURL") ."/www/lib/images/icons/files/exe.png", __(_("Executable File"))),
			 	"exe"  => array(get("webURL") ."/www/lib/images/icons/files/exe.png", __(_("Executable File"))),
			 	"dmg"  => array(get("webURL") ."/www/lib/images/icons/files/exe.png", __(_("Executable File"))),
			 	"pkg"  => array(get("webURL") ."/www/lib/images/icons/files/exe.png", __(_("Executable File"))),
			);
						
			foreach($icons as $extension => $icon) { 
				if($file["extension"] === $extension) {
					$file["icon"] = $icon;

					break;
				}
			}	
			
			return $file;
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

		$file = $this->getFileInformation();
		
		if(!$file) {
			$error["upload"]  = FALSE;
			$error["message"] = "A problem occurred when trying to upload file";

			return $error;
		}
		
		$filename = code(5, FALSE) ."_". slug($file["name"]) .".". $file["extension"];
		$URL 	  = $path . $filename;		
		
		if(file_exists($URL)) { 
			$error["upload"]   = FALSE;
			$error["message"]  = "The file already exists";
			$error["filename"] = $filename; 
		} elseif($this->fileSize > _fileSize) { 
			$error["upload"]  = FALSE;
			$error["message"] = "The file size exceed the permited limit"; 
		} elseif($this->fileError === 1) { 
			$error["upload"]  = FALSE;
			$error["message"] = "An error has ocurred"; 
		} elseif($file["type"] !== $type) { 
			$error["upload"]  = FALSE;
			$error["message"] = "The file type is not permited"; 
		} elseif(move_uploaded_file($this->fileTmp, $URL)) {
			chmod($URL, 0777);
		
			$error["upload"]   = TRUE;
			$error["message"]  = "The file has been upload correctly"; 
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

	public function resize($dir, $filename) {
		$this->Images = $this->core("Images");
				
		$size["miniature"] = $this->Images->getResize("miniature", $dir, $filename);	
		$size["medium"]    = $this->Images->getResize("medium", $dir, $filename);
		$size["large"]     = $this->Images->getResize("large", $dir, $filename);
		$size["original"]  = $this->Images->getResize("original", $dir, $filename);
		
		return $size;	
	}
	
}