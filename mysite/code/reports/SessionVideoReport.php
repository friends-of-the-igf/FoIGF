<?php

class SessionVideoReport extends SS_Report {

	function title() {
		return "Session - Missing Videos Report";
	}
	
	function sourceRecords($params, $sort, $limit) {
		$sessions = MeetingSession::get()->sort($sort);
		$list = new ArrayList();
		foreach($sessions as $session) {
			if($session->Videos()->Count() == 0) {
				$list->push($session);
			}
		}
		return $list;
	}

	function columns() {
		$fields = array(
			"ID" => array(
				"title" => "ID"
			),
			"Meeting.Title" => array(
				"Title" => "Meeting"
			),
			"Title" => array(
				"title" => "Title"
			),
			"Date" => array(
				"Title" => "Date"
			)
		);
		
		return $fields;
	}

}
