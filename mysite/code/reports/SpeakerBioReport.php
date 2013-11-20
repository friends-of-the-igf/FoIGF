<?php
/**
* Runs a report on missing Speaker bios.
*
* @package FoIGF
*/
class SpeakerBioReport extends SS_Report {

	function title() {
		return "Speakers - Missing Bio Report";
	}
	
	function sourceRecords($params, $sort, $limit) {
		
		$speaker_Arr = array();
		if(isset($_REQUEST['filters']['MeetingID'])) {
			$sessions = MeetingSession::get()->filter('MeetingID', $_REQUEST['filters']['MeetingID'])->sort($sort);
			foreach($sessions as $session){
				$speakers = $session->Speakers();
				foreach($speakers as $speaker){
					$speaker_Arr[$speaker->ID] = $speaker;
				}
			}
			$list = new ArrayList($speaker_Arr);
			return $list;
		} else {
			return Group::get()->filter(array('Title' => 'Speakers'))->First()->Members();
		}


	}

	function columns() {
		$fields = array(
			"ID" => array(
				"title" => "ID"
			),
			
			"Name" => array(
				"title" => "Name",
				'formatting' => '<a href=\"admin/security/EditForm/field/Members/item/{$ID}/edit\" title=\"Edit session\">{$value}</a>'
			),
			"BioLink" => array(
				"Title" => "Bio"
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


		return $fields;
	}

}