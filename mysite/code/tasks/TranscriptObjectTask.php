<?php
/**
* A task to write all objects
*
* @package FoIGF
*/
class TranscriptObjectTask extends BuildTask{
	
	protected $title = "Create Transcript Objects";
	
	protected $description = "Create Transcipt objects from existing transcripts on Sessions";
	
	function run($request){
		$count = 0;
		$sessions = MeetingSession::get();
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
		foreach($sessions as $session){
			if($session->TranscriptType != null){
				$count++;
				$trans_obj = new SessionTranscript();
				$trans_obj->LanguageID = $eng->ID;
				$trans_obj->TranscriptType = $session->TranscriptType;
				$trans_obj->Content = $session->TranscriptContent;
				if($session->TranscriptID != 0){
					$trans_obj->TranscriptID  = $session->TranscriptID;
				}
				$trans_obj->write();
				$session->Transcripts()->add($trans_obj);
				$session->write();
			}
		}
		echo $count.' Transcript objects created and saved to Sessions';
	}


	
}