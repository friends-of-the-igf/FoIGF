<?php

class TranscriptAssignTask extends BuildTask{
	
	protected $title = "Transcript Assign Task";
	
	protected $description = "Assign transcript type to Sessions";
	
	function run($request){
		$f_count = 0;
		$t_count = 0;
		foreach(MeetingSession::get() as $session){
			if($session->TranscriptType == null){
				if($session->TranscriptID == null || $session->TranscriptID == 0){
					$session->TranscriptType = 'Text';
					$t_count++;
				} else {
					$session->TranscriptType = 'File';
					$f_count++;
				}
				$session->write();
			}
		}
		
		echo $f_count .' Sessions assigned with type File<br>'.$t_count.' Sessions assigned with type Text';
	}


	
}