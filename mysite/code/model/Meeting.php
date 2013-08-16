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
		
		$tabset = new TabSet("Root",
			$mainTab,
			$sessionsTab,
			$linksTab
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
		
		return $fields;
	}

	public function Link($action = null) {
		return Controller::join_links('meeting', $this->ID, $action);
	}

	public function getTopics() {
		$sessions = $this->MeetingSessions();
		if($sessions->count() != 0) {
			$list = new ArrayList();
			foreach($sessions as $session) {
				$topic = $session->Topic();
				if($topic) {
					$list->push($topic);
				}
			}
			$list->removeDuplicates();
			return $list;
		}
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

	public function allTags() {
		$sessions = $this->MeetingSessions();

		$uniqueTagsArray = array();
		foreach($sessions as $session) {
			$tags = preg_split("*,*", trim($session->Tags));
			foreach($tags as $tag) {
				if($tag != "") {
					$tag = strtolower($tag);
					$uniqueTagsArray[$tag] = $tag;
				}
			}
		}

		$output = new ArrayList();
		$link = "";
		if($page = SessionsHolder::get()->First()) {
			$link = $page->Link('tag');
		}

		foreach($uniqueTagsArray as $tag) {
			$tagsList = $this->allTagsList();
			$filteredList = $tagsList->filter('Tag', $tag);
			$weight = $filteredList->Count();

			$output->push(new ArrayData(array(
				'Tag' => $tag,
				'Link' => $link . '/' . urlencode($tag),
				'URLTag' => urlencode($tag),
				'Weight' => $weight
			)));
		}
		
		return $output;
	}

	public function allTagsList() {
		$sessions = $this->MeetingSessions();
		$tagsList = new ArrayList();
		foreach($sessions as $session) {
			$tags = preg_split("*,*", trim($session->Tags));
			foreach($tags as $tag) {
				if($tag != "") {
					$tag = strtolower($tag);
					$tagsList->push(new ArrayData(array(
						'Tag' => $tag
					)));
				}
			}
		}
		return $tagsList;
	}

}
