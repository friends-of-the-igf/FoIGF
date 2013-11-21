<?php
/**
* A Meeting Session Topic to categorise Meeting Sessions
*
* @package FoIGF
*/
class TranscriptTypeReport extends SS_Report {

	function title() {
		return "Session - Transcript and Proposal Type Report";
	}
	
	function sourceRecords($params, $sort, $limit) {
		if(!$sort || $sort == "") {
			$sort = 'TranscriptType';
		}
		$sessions = MeetingSession::get()->exclude(array('TranscriptType' => '', 'ProposalType' => ''))->sort($sort);
		$list = new ArrayList();
		foreach($sessions as $session) {
			if(!$session->Content) {
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
			"TranscriptType" => array(
				"Title" => "Transcript Type"
			),
			"ProposalType" => array(
				"Title" => "Proposal Type"
			),
			"Date" => array(
				"Title" => "Date"
			)
		);
		
		return $fields;
	}

}
