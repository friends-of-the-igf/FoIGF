<?php
/**
* A task to write all objects
*
* @package FoIGF
*/
class AssignLanguageTask extends BuildTask{
	
	protected $title = "Assign Language";
	
	protected $description = "Assign English to all Videos without a Language";
	
	function run($request){
		$count = 0;
		$message = '';
		$lang = VideoLanguage::get()->filter(array('Name' => 'English'));
		if($lang->Count() == 0){
			echo 'English Language object created<br/>';
			$eng = new VideoLanguage();
			$eng->Name = 'English';
			$eng->write();
		} else {
			echo 'Existing English Language object found<br/>';
			$eng = $lang->First();
		}
		echo 'Writing Videos...<br/>';
		$videos = Video::get();
		foreach($videos as $video){
			if($video->LanguageID == 0){
				$count++;
				$video->LanguageID = $eng->ID;
				$video->write();
			}
		}
		echo $count.' videos assigned withe English';
	}


	
}