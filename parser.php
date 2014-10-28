<?php

include_once('inc/Parsedown.php');

class AutoParser extends Parsedown {

	public $md_folder = "md";

	public $html_folder = "html";

	public $css_file = "css/github-markdown.css";

	public function parser() {

		$allfiles = array();

		$allfiles = $this->_scandir($this->md_folder);

	
		foreach($allfiles as $key => $file) {
			
			$html = $this->_parser($file);	

			$file_name = pathinfo($file, PATHINFO_FILENAME);

			$target_path = $this->html_folder . DIRECTORY_SEPARATOR . substr($file, strlen($this->md_folder)+1);

			$dirname = dirname($target_path);

			$this->_mkdir($dirname);

			file_put_contents($dirname . DIRECTORY_SEPARATOR . $file_name.".html", $html);
		}
	}

	private function _parser($file) {

		
		$target_path = $this->html_folder . DIRECTORY_SEPARATOR . substr($file, strlen($this->md_folder)+1);
		
		
		$dirname = dirname($target_path);
		
		$count = count( explode( DIRECTORY_SEPARATOR, $dirname));

		$css_path = str_repeat("../", $count-1);
		

		$content = '<!DOCTYPE html>
		<html>
		    	<head>
			    	<link rel="stylesheet" href="'. $css_path . $this->css_file . '">
			        	<title>PHP Markdown Lib - Readme</title>
			        	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		    	</head>
		    	<body>
		    		<h1> </h1>
		    		<article class="markdown-body">' . $this->text(file_get_contents($file)). '</article">
		    	</body>
		</html>';

		return $content;
	}
	
	private function _mkdir($path, $mode = 0777) {

		$dirs = explode(DIRECTORY_SEPARATOR, $path);
	
		$count = count($dirs);
		$path = '.';
		for ($i = 0; $i < $count; ++$i) {
			$path .= DIRECTORY_SEPARATOR . $dirs[$i];

			if (!is_dir($path) && !@mkdir($path, $mode)) {
				return false;
			}
		}

		return true;
	}

	private function _scandir($from) {
		
		if(! is_dir($from))
			return false;

		$files = array();

		if( $dh = opendir($from)) {
			while( false !== ($file = readdir($dh))) {
				// Skip '.' and '..'
				if( $file == '.' || $file == '..') {
					continue;
				}

				$path = $from . DIRECTORY_SEPARATOR . $file;

				if( is_dir($path) ) {

					$files  = array_merge($files, $this->_scandir($path));
				} else {

					$file_extension = pathinfo($file, PATHINFO_EXTENSION);
 
					if($file_extension == "md") {
						$files[] = $path;
					}
					
				}
					
			}

			closedir($dh);
		}

		return $files;
	}
}


$auto = new AutoParser();

$auto->md_folder = "md";
$auto->html_folder = "html";
$auto->parser();

?>