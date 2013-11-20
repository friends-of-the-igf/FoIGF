<?php
/**
* Changes Session of Type Panel to Sessions of type Workshop
*
* @package FoIGF
*/
class PanelToWorkshopTask extends BuildTask{
	
	protected $title = "Switch Panel Discussions to Workshops";
	
	protected $description = "Switch Panel Discussions to Workshops";
	
	function run($request){
		$count = 0;
	
		foreach(MeetingSession::get() as $session){
			if($session->TypeID == 2){
				$session->TypeID = 4;
				$session->write();
				echo $session->Title;
			}
		}
		
	}
}