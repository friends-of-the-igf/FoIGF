<?php
class Meeting extends DataObject {

	public static $db = array(
		'Title' => 'Text',
		'StartDate' => 'Date',
		'EndDate' => 'Date'
	);

	public static $has_one = array(
		'Location' => 'Location'
	);

	public static $has_many = array(
		'MeetingSessions' => 'MeetingSession',
		'LinkItems' => 'LinkItem'
	);

	public static $many_many = array(
		'Topics' => 'Topic'
	);

	public static $summary_fields = array(
		'Title',
		'StartDate',
		'EndDate'
	);

	public function getCMSFields() {
		$fields = new FieldList();

		$mainTab = new Tab('Main');
		$sessionsTab = new Tab('Sessions');
		$linksTab = new Tab('Links');
		$topicsTab = new Tab('Topics');
		$tabset = new TabSet("Root",
			$mainTab,
			$sessionsTab,
			$linksTab,
			$topicsTab
		);
		$fields->push( $tabset );

		$mainTab->push(new TextField('Title', 'Title'));
		$mainTab->push($date = new DateField('StartDate', 'Start Date'));
		$date->setConfig('showcalendar', true);
		$mainTab->push($date = new DateField('EndDate', 'End Date'));
		$date->setConfig('showcalendar', true);

		$locations = Location::get()->sort('Name');
		if($locations->Count()) {
			$mainTab->push(new DropdownField('LocationID', 'Location', $locations->map()));			
		}	

		if($this->ID) {
			$gridFieldConfig = new GridFieldConfig_RelationEditor();
			$list = $this->MeetingSessions();
			$gridField = new GridField('MeetingSessions', 'Sessions', $list, $gridFieldConfig);
			$sessionsTab->push($gridField);
		}

		if($this->ID) {
			$gridFieldConfig = new GridFieldConfig_RecordEditor();
			$list = $this->LinkItems();
			$gridField = new GridField('LinkItems', 'Links', $list, $gridFieldConfig);
			$linksTab->push($gridField);
		}

		if($this->ID) {
			$gridFieldConfig = new GridFieldConfig_RelationEditor();
			$list = $this->Topics();
			$gridField = new GridField('Topics', 'Topics', $list, $gridFieldConfig);
			$topicsTab->push($gridField);
		}
		
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
