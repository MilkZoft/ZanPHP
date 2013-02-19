<?php
class CSSMin
{
	public static function minify($css) 
	{
	    $cssmin = new CSSMin;
		return trim($cssmin->_optimize($css));
	}

	protected function _optimize($contents)
	{
		$comment = '/\*[^*]*\*+(?:[^/*][^*]*\*+)*/';		
		$double_quot = '"[^"\\\\]*(?:\\\\.[^"\\\\]*)*"';		
		$single_quot = "'[^'\\\\]*(?:\\\\.[^'\\\\]*)*'";		
		$contents = preg_replace(
			"<($double_quot|$single_quot)|$comment>Ss",
			"$1",
			$contents
		);

		$contents = preg_replace_callback(
			'<' .
			'\s*([@{};,])\s*' .				
			'| \s+([\)])' .			
			'| ([\(:])\s+' .
			'>xS',
			array('CSSMin', '_optimize_call_back'),
			$contents
		);

		return $contents;
	}

	protected function _optimize_call_back($matches)
	{		
		unset($matches[0]);		
		return current(array_filter($matches));
	}
}