<?php
class Meeting extends DataObject {

	public static $db = array(
		'Title' => 'Text',
		'StartDate' => 'Date',
		'EndDate' => 'Date'
	);

	public static $has_one = array(
	);

	public static $has_many = array(
		'MeetingSessions' => 'MeetingSession'
	);

	public static $summary_fields = array(
		'Title',
		'StartDate',
		'EndDate'
	);

	public function getCMSFields() {
		$fields = new FieldList();

		$fields->push(new TextField('Title', 'Title'));
		$fields->push($date = new DateField('StartDate', 'Start Date'));
		$date->setConfig('showcalendar', true);
		$fields->push($date = new DateField('EndDate', 'End Date'));
		$date->setConfig('showcalendar', true);

		$gridFieldConfig = new GridFieldConfig_RelationEditor();
		$list = $this->MeetingSessions();
		$gridField = new GridField('MeetingSessions', 'Sessions', $list, $gridFieldConfig);
		$fields->push($gridField);

		return $fields;
	}

	public function Link($action = null) {
		return Controller::join_links('meeting', $this->ID, $action);
	}

	public function getSpeakers() {
		$sessions = $this->MeetingSessions();
		if($sessions->count() != 0) {
			$list = new ArrayList();
			foreach($sessions as $session) {
				$speakers = $session->Speakers();
				if($speakers->count() != 0) {
					foreach($speakers as $speaker) {
						$list->push($speaker);
					}
				}
			}
			$list->removeDuplicates();
			return $list;
		}
	}

}
