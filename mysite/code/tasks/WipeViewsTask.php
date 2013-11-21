<?php
/**
* A task to wipe all views from Meeting Sessions
*
* @package FoIGF
*/
class WipeViewsTask extends BuildTask{
	
	protected $title = "Reset views on Sessions";
	
	protected $description = "Reset view to zero on all Sessions";
	
	function run($request){
		$count = 0;
		foreach(MeetingSession::get() as $session){
			$session->Views = 0;
			$session->write();
			$count++;
		}
		echo $count.' Sessions Written';
	}
}