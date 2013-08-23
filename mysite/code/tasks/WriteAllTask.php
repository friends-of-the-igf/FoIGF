<?php

class WriteAllTask extends BuildTask{
	
	protected $title = "Write All Objects";
	
	protected $description = "Write all objects";
	
	function run($request){
		foreach(Meeting::get() as $meeting){
			$meeting->write();
		}
		foreach(MeetingSession::get() as $session){
			$session->URLSegment = $session->Link();
			$session->write();
		}
		foreach(Member::get() as $member){
			$member->write();
		}
		echo 'done';
	}


	
}