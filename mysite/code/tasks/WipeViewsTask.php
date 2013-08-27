<?php

class WipeViewsTask extends BuildTask{
	
	protected $title = "Reset views on Sessions";
	
	protected $description = "Reset view to zero on all Sessions";
	
	function run($request){
		foreach(MeetingSession::get() as $session){
			$session->Views = 0;
			$session->write();
		}
	}
}