<?php
class Meeting extends DataObject {

	public static $db = array(
		'Title' => 'Text',
		'Website' => 'Text',
		'StartDate' => 'Date',
		'EndDate' => 'Date',
		'URLSegment' => 'Varchar(255)'
		
	);

	public static $has_one = array(
		'Location' => 'Location',
		'Image' => 'Image'
	);

	public static $has_many = array(
		'MeetingSessions' => 'MeetingSession',
		'LinkItems' => 'LinkItem'
	);

	public static $summary_fields = array(
		'Title'
	);

	static $searchable_fields = array(
		'Title'
	);

	// fields to return
	static $return_fields = array(
		'Title',
		'URLSegment'
	);

	// set index
	public static $indexes = array(
		"fulltext (Title)"
    );

    // REQUIRED: object table must be set to MyISAM
	static $create_table_options = array(
	    'MySQLDatabase' => 'ENGINE=MyISAM'
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
		$mainTab->push(new TextField('Website', 'Website'));
		$mainTab->push($date = new DateField('StartDate', 'Start Date'));
		$date->setConfig('showcalendar', true);
		$mainTab->push($date = new DateField('EndDate', 'End Date'));
		$date->setConfig('showcalendar', true);

		$locations = Location::get()->sort('City');
		if($locations->Count()) {
			$mainTab->push(new DropdownField('LocationID', 'Location', $locations->map('ID', 'Name')));			
		}	
		$mainTab->push(new UploadField('Image', 'Image'));

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

	public function FilterLink($action = null) {
		if($page = SessionsHolder::get()->First()) {
			$link =  $page->Link();
	
			return $link. '?meeting='. $this->ID;
		}
	}


	public function onBeforeWrite() {
		parent::onBeforeWrite();

		if(!$this->URLSegment) {
			$this->URLSegment = $this->Link();
			
		}
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
			$count = $tagsList->Count();
			$filteredList = $tagsList->filter('Tag', $tag);
			$weight = $filteredList->Count();
			$percent = ($weight / $count) * 100;

			if($percent <= 1) {
				$size = "14px";
			} elseif($percent <= 2) {
				$size = "16px";
			} elseif($percent <= 3) {
				$size = "18px";
			} elseif($percent <= 5) {
				$size = "20px";
			} elseif($percent <= 10) {
				$size = "22px";
			}

			$output->push(new ArrayData(array(
				'Tag' => $tag,
				'Link' => $link . '/' . urlencode($tag),
				'URLTag' => urlencode($tag),
				'Weight' => $percent,
				'Size' => $size
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

	public function getYearLocation(){
		$date = date('Y', strtotime($this->StartDate));
		// $date .= '-'.date('d F Y', strtotime($this->EndDate));
		return $this->Location()->Name().' '.$date;
	}

	public function getMeetingSessions(){
		return $this->makeColumns($this->MeetingSessions());

	}

	public function makeColumns($sessions){

		$total = $sessions->Count();

		$list = new ArrayList();

		$col1 = new ArrayList();
		$col2 = new ArrayList();
		$col3 = new ArrayList();
		$col4 = new ArrayList();

		$sessionIndex = 0;
		$j = 1;

		while ($sessionIndex <= 19 ) {
			

			$session = $sessions->limit(1, $sessionIndex)->first();
			
			if($session) {
				switch ($j) {
					case 1:
						$col1->push($session);
						$j++;
						break;
					case 2:
						$col2->push($session);
						$j++;
						break;
					case 3:
						$col3->push($session);
						$j++;
						break;
					case 4:
						$col4->push($session);
						$j = 1;
						break;	
				}
			}
			$sessionIndex++;
		}

		$list->push(new ArrayData(array('Columns' => $col1)));
		$list->push(new ArrayData(array('Columns' => $col2)));
		$list->push(new ArrayData(array('Columns' => $col3)));
		$list->push(new ArrayData(array('Columns' => $col4)));


		return $list;	

	}

}
