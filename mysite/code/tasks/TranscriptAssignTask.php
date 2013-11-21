<?php
/**
* A task to assign a transcript type to Meeting Sessions if a transcript exists
*
* @package FoIGF
*/
class TranscriptAssignTask extends BuildTask{
	
	protected $title = "Transcript Assign Task";
	
	protected $description = "Assign transcript type to Sessions";
	
	function run($request){
		$f_count = 0;
		$t_count = 0;
		foreach(MeetingSession::get() as $session){
			if($session->TranscriptType == null){
				if($session->TranscriptContent != null){
					$session->TranscriptType = 'Text';
					$t_count++;
				} else if($session->TranscriptID != 0) {
					$session->TranscriptType = 'File';
					$f_count++;
				} else {
					$session->TranscriptType = null;
				}
				$session->write();
			}
		}
		
		echo $f_count .' Sessions assigned with type File<br>'.$t_count.' Sessions assigned with type Text';
	}


	
}