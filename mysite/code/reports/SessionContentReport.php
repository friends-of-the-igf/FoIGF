<?php
/**
* Runs a report on misisng Session Content
*
* @package FoIGF
*/
class SessionContentReport extends SS_Report {

	function title() {
		return "Session - Missing Content Report";
	}
	
	function sourceRecords($params, $sort, $limit) {
		
		if(isset($_REQUEST['filters']['MeetingID'])) {
			$sessions = MeetingSession::get()->filter('MeetingID', $_REQUEST['filters']['MeetingID'])->sort($sort);
		} else {
			$sessions = MeetingSession::get()->sort($sort);
		}

		$list = new ArrayList();

		if(isset($_REQUEST['filters']['Type'])) {
			$type = $_REQUEST['filters']['Type'];

			foreach($sessions as $session) {
				if(!$session->$type) {
					$list->push($session);
				}
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
				"title" => "Title",
				'formatting' => '<a href=\"admin/sessions/MeetingSession/EditForm/field/MeetingSession/item/{$ID}/edit\" title=\"Edit session\">{$value}</a>'
			),
			"Date" => array(
				"Title" => "Date"
			)
		);
		
		return $fields;
	}

	function parameterFields() {
		$fields = new FieldList();

		$meetings = Meeting::get()->sort('StartDate');
		if($meetings->Count() != 0) {
			$list = array("" => "-All-");
			foreach($meetings as $meeting) {
				$list[$meeting->ID] = $meeting->StartDate . ' : ' . $meeting->Location()->City . ', ' . $meeting->Location()->Country;
			}
			$fields->push(new DropdownField('MeetingID','Filter by Meeting year', $list));
		}

		$types = array(
			'All' => '-All-',
			'Content' => 'Content',
			'Date' => 'Date',
			'Day' => 'Day',
			'TranscriptContent' => 'Transcript Content',
			'TranscriptID' => 'Transcript File',
			'ProposalContent' => 'Proposal Content',
			'ProposalID' => 'Proposal File',
			'MeetingID' => 'Meeting',
			'TypeID' => 'Type',
			'Topic' => 'Topic'
		);

		$fields->push(new DropdownField('Type','Filter by session with missing information', $types));

		return $fields;
	}

}
