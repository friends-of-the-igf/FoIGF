<?php
/**
* Extracts file contents
*
* @package FoIGF
*/
class FileExtractionTask extends BuildTask{
	
	protected $title = "Extract File Content";
	
	protected $description = "Extracts all file content using Solrs text extraction";
	
	function run($request){
		$files = File::get();

		foreach($files as $file) {
			if(!$file->FileContentCache) {
				$file->extractFileAsText(true);
			}
			echo '.';
		}
		echo 'done';
	}


	
}