<?php 
class StreamReader {
	
	public function read($bytes) {
		return FALSE;
	}
  
	public function seekto($position) {
		return FALSE;
	}
  
	public function currentpos() {
		return FALSE;
	}
  
	public function length() {
		return FALSE;
	}
}

class StringReader {
	public $_pos;
	public $_str;

	public function __construct($str = NULL) {
		$this->_str = $str;
		$this->_pos = 0;
	}

	public function read($bytes) {
		$data = substr($this->_str, $this->_pos, $bytes);
		
		$this->_pos += $bytes;
    
		if(strlen($this->_str) < $this->_pos) {
			$this->_pos = strlen($this->_str);
		}
		
		return $data;
	}

	public function seekto($pos) {
		$this->_pos = $pos;
    
		if(strlen($this->_str) < $this->_pos) {
			$this->_pos = strlen($this->_str);
		}
		
		return $this->_pos;
	}

	public function currentpos() {
		return $this->_pos;
	}

	public function length() {
		return strlen($this->_str);
	}

}

class FileReader {
	public $_pos;
	public $_fd;
	public $_length;

	public function __construct($filename) {
		if(file_exists($filename)) {
			$this->_length = filesize($filename);
			$this->_pos    = 0;
			$this->_fd     = fopen($filename,'rb');
      
			if(!$this->_fd) {
				$this->error = 3; 
				return FALSE;
			}
		} else {
			$this->error = 2; 
			return FALSE;
		}
	}

	public function read($bytes) {
		if($bytes) {
			fseek($this->_fd, $this->_pos);

			while($bytes > 0) {
				$chunk  = fread($this->_fd, $bytes);
				$data  .= $chunk;
				$bytes -= strlen($chunk);
			}
			
			$this->_pos = ftell($this->_fd);
      
			return $data;
		} else {
			return NULL;
		}
	}

	public function seekto($pos) {
		fseek($this->_fd, $pos);
		$this->_pos = ftell($this->_fd);
		
		return $this->_pos;
	}

	public function currentpos() {
		return $this->_pos;
	}

	public function length() {
		return $this->_length;
	}	

	public function close() {
		fclose($this->_fd);
	}
	
}

class CachedFileReader extends StringReader {
	
	public function __construct($filename) {
		if(file_exists($filename)) {
			$length = filesize($filename);
			$fd 	= fopen($filename, 'rb');	  	
		
			if(!$fd) {
				$this->error = 3; 
				
				return FALSE;
			}
		  
			$this->_str = fread($fd, $length);
			
			fclose($fd);		
		} else {		
			$this->error = 2;
		  	
		  	return FALSE;		  
		}
	}
	
}
?>
