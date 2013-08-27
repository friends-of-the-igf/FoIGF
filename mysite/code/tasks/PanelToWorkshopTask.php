<?php

class PanelToWorkshopTask extends BuildTask{
	
	protected $title = "Switch Panel Discussions to Workshops";
	
	protected $description = "Switch Panel Discussions to Workshops";
	
	function run($request){
		$count = 0;
		$panel = Type::get()->filter(array('Name' => 'Panel discussion'))->First()->ID;
		$workshop = Type::get()->filter(array('Name' => 'Workshop'))->First()->ID;
		foreach(MeetingSession::get() as $session){
			if($session->TypeID == $panel){
				$session->TypeID == $workshop;
			}
		}
		echo $count.' Sessions Written';
	}
}