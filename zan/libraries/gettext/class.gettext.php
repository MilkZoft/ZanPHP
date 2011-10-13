<?php 
class Gettext_Reader {
	public $error = 0;
	public $BYTEORDER = 0;        
	public $STREAM = NULL;
	public $short_circuit = false;
	public $enable_cache = false;
	public $originals = NULL;     
	public $translations = NULL;   
	public $pluralheader = NULL;   
	public $total = 0;          
	public $table_originals = NULL;  
	public $table_translations = NULL;  
	public $cache_translations = NULL;  

	public function __construct($language = FALSE, $enable_cache = true) {
		if(!$language) {
			$this->short_circuit = TRUE;
			
			return TRUE;
		}
    
		$this->enable_cache = $enable_cache;
		
		$MAGIC1 = (int) - 1794895138;
		$MAGIC2 = (int) - 569244523;
    
		$this->STREAM = new CachedFileReader($language);
		
		$magic = $this->readint();
    
		if($magic == $MAGIC1) {
			$this->BYTEORDER = 0;
		} elseif($magic == $MAGIC2) {
			$this->BYTEORDER = 1;
		} else {
			$this->error = 1; 
			return false;
		}
    
		$revision = $this->readint();
		
		$this->total = $this->readint();
		$this->originals = $this->readint();
		$this->translations = $this->readint();
	}
	
	public function readint() {
		if($this->BYTEORDER == 0) {
			$read = unpack('V', $this->STREAM->read(4));
			
			return array_shift($read);
		} else {
			$read = unpack('N', $this->STREAM->read(4));
			
			return array_shift($read);
		}
    }

	public function readintarray($count) {
		if($this->BYTEORDER == 0) {
			return unpack('V'.$count, $this->STREAM->read(4 * $count));
		} else {
			return unpack('N'.$count, $this->STREAM->read(4 * $count));
		}
	}
  
	public function load_tables() {
		if(is_array($this->cache_translations) and is_array($this->table_originals) and is_array($this->table_translations)) {
			return TRUE;
		}
		
		$this->STREAM->seekto($this->originals);
		
		$this->table_originals = $this->readintarray($this->total * 2);
		
		$this->STREAM->seekto($this->translations);
		
		$this->table_translations = $this->readintarray($this->total * 2);  
    
		if($this->enable_cache) {
			$this->cache_translations = array();
			
			for($i = 0; $i < $this->total; $i++) {
				$this->STREAM->seekto($this->table_originals[$i * 2 + 2]);
				
				$original = $this->STREAM->read($this->table_originals[$i * 2 + 1]);
        
				$this->STREAM->seekto($this->table_translations[$i * 2 + 2]);
        
				$translation = $this->STREAM->read($this->table_translations[$i * 2 + 1]);
				
				$this->cache_translations[$original] = $translation;
			}
		}
	}
  
	public function get_original_string($num) {
		$length = $this->table_originals[$num * 2 + 1];
		$offset = $this->table_originals[$num * 2 + 2];
    
		if(!$length) {
			return NULL;
		}
		
		$this->STREAM->seekto($offset);
		
		$data = $this->STREAM->read($length);
		
		return (string) $data;
	}
  
	public function get_translation_string($num) {
		$length = $this->table_translations[$num * 2 + 1];
		$offset = $this->table_translations[$num * 2 + 2];
		
		if(!$length) {
			return NULL;
		}
		
		$this->STREAM->seekto($offset);
		
		$data = $this->STREAM->read($length);
		
		return (string) $data;
	}
  
	public function find_string($string, $start = -1, $end = -1) {
		if(($start == -1) or ($end == -1)) {
			$start = 0;
			$end   = $this->total;
		}
    
		if(abs($start - $end) <= 1) {
			$txt = $this->get_original_string($start);
			
			if($string == $txt) {
				return $start;
			} else {
				return -1;
			}
		} elseif($start > $end) {
			return $this->find_string($string, $end, $start);
		} else {
			$half = (int) (($start + $end) / 2);
			$cmp  = strcmp($string, $this->get_original_string($half));
			
			if($cmp == 0) {
				return $half;
			} elseif($cmp < 0) {
				return $this->find_string($string, $start, $half);
			} else {
				return $this->find_string($string, $half, $end);
			}
		}
	}
  
	public function translate($string) {
		if($this->short_circuit) {
			return $string;
		}
		
		$this->load_tables();     
    
		if($this->enable_cache) {
			if(array_key_exists($string, $this->cache_translations)) {
				return $this->cache_translations[$string];
			} else {
				return $string;
			}
		} else {
			$num = $this->find_string($string);
			
			if($num == -1) {
				return $string;
			} else {
				return $this->get_translation_string($num);
			}
		}
	}

	public function get_plural_forms() {
		$this->load_tables();
    
		if(!is_string($this->pluralheader)) {
			if($this->enable_cache) {
				$header = $this->cache_translations[""];
			} else {
				$header = $this->get_translation_string(0);
			}
			
			if(preg_match("/(^|\n)plural-forms: ([^\n]*)\n/i", $header, $regs)) {
				$expr = $regs[1];
			} else {
				$expr = "nplurals=2; plural=n == 1 ? 0 : 1;";
			}
			
			$this->pluralheader = $expr;
		}
    
		return $this->pluralheader;
  }

	public function select_string($n) {
		$string = $this->get_plural_forms();
		$string = str_replace('nplurals', "\$total", $string);
		$string = str_replace("n", $n, $string);
		$string = str_replace('plural', "\$plural", $string);    
		$total  = 0;
		$plural = 0;
		
		eval("$string");
    
		if($plural >= $total) {
			$plural = $total - 1;
		}
		
		return $plural;
	}

	public function ngettext($single, $plural, $number) {
		if($this->short_circuit) {
			if($number != 1) {
				return $plural;
			} else {
				return $single;
			}
		}
    
		$select = $this->select_string($number); 
		$key 	= $single . chr(0) . $plural;
   
		if($this->enable_cache) {
			if(!array_key_exists($key, $this->cache_translations)) {
				return ($number != 1) ? $plural : $single;
			} else {
				$result = $this->cache_translations[$key];
				$list = explode(chr(0), $result);
        
				return $list[$select];
			}
		} else {
			$num = $this->find_string($key);
      
			if($num == -1) {
				return ($number != 1) ? $plural : $single;
			} else {
				$result = $this->get_translation_string($num);
				$list   = explode(chr(0), $result);
        
				return $list[$select];
			}
		}
	}
}
