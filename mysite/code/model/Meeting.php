<?php
/**
* Meeting Object. Contains information about meetings. Has many Meeting Sessions.
*
* @package FoIGF
*/
class Meeting extends DataObject {

	public static $db = array(
		'Title' => 'Text',
		'Website' => 'Text',
		'StartDate' => 'Date',
		'EndDate' => 'Date',
		'URLSegment' => 'Varchar(255)'
		
	);

	public static $default_sort = 'StartDate';


	public static $has_one = array(
		'Location' => 'Location',
		'Image' => 'Image'
	);

	public static $has_many = array(
		'MeetingSessions' => 'MeetingSession',
		'LinkItems' => 'LinkItem'
	);

	public static $summary_fields = array(
		'Title',
		'StartDate',
		'Location.City'
	);

	public static $field_labels = array(
		'Location.City' => 'Location',
		'StartDate' => 'Date'
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
		$date->setConfig('jQueryUI.changeMonth', true);
		$date->setConfig('jQueryUI.changeYear', true);
		$date->setAttribute('placeholder', 'eg. Jan 1, 1999');
		$mainTab->push($date = new DateField('EndDate', 'End Date'));
		$date->setConfig('showcalendar', true);
		$date->setConfig('jQueryUI.changeMonth', true);
		$date->setConfig('jQueryUI.changeYear', true);
		$date->setAttribute('placeholder', 'eg. Jan 1, 1999');

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
		return Controller::join_links('Meeting', $this->ID, $action);
	}

	/**
	 * Returns a link to the Session Holder page with the meeting id as a parameter in the query string.
	 * 
	 * @return String.
	 */
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

	/**
	 * Returns a list of topics included in this meeting. 
	 * 
	 * @return ArrayList.
	 */
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


	/**
	 * Returns a list of speakers included in this meeting. 
	 * 
	 * @return ArrayList.
	 */
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

	public function popularTags($limit = null, $sort = null, $filter = null) {

		$sessions = $this->MeetingSessions();
		$sessionIDs = $sessions->map('ID', 'ID')->toArray();

		$idQuery = DB::query('SELECT TagID FROM MeetingSession_Tags WHERE MeetingSessionID IN ('. implode(',', $sessionIDs) .')');
		$ids = array_keys($idQuery->map());

		$tags = Tag::get()->filter(array('Status' => 'Approved', 'ID' => $ids));

		$count = DB::query('SELECT COUNT(*) FROM MeetingSession_Tags');
		$count = $count->value();

		$output = new ArrayList();
		foreach($tags as $tag) {
			$weight = DB::query('SELECT COUNT(*) FROM MeetingSession_Tags WHERE TagID ='.$tag->ID);
			$weight = $weight->value();

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
			} else {
				$size = "23px";
			}

			$output->push(new ArrayData(array(
				'Tag' => $tag->Title,
				'Link' => $tag->Link(),
				'Weight' => $percent,
				'Size' => $size
			)));
		}
		if($sort) {
			$output->sort('Weight', 'DESC');
		}

		if($limit) {
			return new ArrayList(array_slice($output->items, 0, $limit));
		}
		return $output;
	}

	// /**
	//  * Returns a list the 20 most popular tags as ArrayData 
	//  * 
	//  * @return ArrayList.
	//  */
	// public function popularTags() {
	// 	$sessions = $this->MeetingSessions();

	// 	$uniqueTagsArray = array();
	// 	foreach($sessions as $session) {
	// 		$tags = preg_split("*,*", trim($session->Tags));
	// 		foreach($tags as $tag) {
	// 			if($tag != "") {
	// 				$tag = strtolower($tag);
	// 				$uniqueTagsArray[$tag] = $tag;
	// 			}
	// 		}
	// 	}

	// 	$output = new ArrayList();
	// 	$link = "";
	// 	if($page = SessionsHolder::get()->First()) {
	// 		$link = $page->Link('tag');
	// 	}

	// 	foreach($uniqueTagsArray as $tag) {
	// 		$tagsList = $this->allTagsList();
	// 		$count = $tagsList->Count();
	// 		$filteredList = $tagsList->filter('Tag', $tag);
	// 		$weight = $filteredList->Count();
	// 		$percent = ($weight / $count) * 100;

	// 		if($percent <= 3) {
	// 			$size = "14px";
	// 		} elseif($percent <= 5) {
	// 			$size = "16px";
	// 		} elseif($percent <= 10) {
	// 			$size = "18px";
	// 		} elseif($percent <= 20) {
	// 			$size = "20px";
	// 		} elseif($percent <= 30) {
	// 			$size = "22px";
	// 		} else {
	// 			$size = "22px";
	// 		}

	// 		$output->push(new ArrayData(array(
	// 			'Tag' => $tag,
	// 			'Link' => $link . '/' . urlencode($tag),
	// 			'URLTag' => urlencode($tag),
	// 			'Weight' => $percent,
	// 			'Size' => $size
	// 		)));
	// 	}
	// 	$output->sort('Weight', 'DESC');
		
	// 	$limit = 20;
		
	// 	return new ArrayList(array_slice($output->items, 0, $limit));
	// }

	/**
	 * Returns a list of all tags included in this meeting. 
	 * 
	 * @return ArrayList.
	 */
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

	/**
	 * Returns a string of the year and the city of the Meeting.  
	 * 
	 * @return String.
	 */
	public function getYearLocation(){
		$date = date('Y', strtotime($this->StartDate));
		// $date .= '-'.date('d F Y', strtotime($this->EndDate));
		return $date.' '.$this->Location()->City;
	}

	/**
	 * Returns a list of the Meeting's Meeting Sessions arranged into columnns.  
	 * 
	 * @return ArrayList.
	 */
	public function getMeetingSessions(){
		return $this->makeColumns($this->MeetingSessions());

	}

	/**
	 * Returns a list of the Meeting Sessions arranged into four columnns.  
	 * 
	 * @param $sessions An array list of the sessions to be arranged into columns
	 * @return ArrayList.
	 */
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
	/**
	 * Returns a list of the Meeting Sessions grouped by Day and then Topic arranged into columns.  
	 * 
	 * @return ArrayList.
	 */
	public function meetingDays(){
		
		//initial lists for each day
		$dayList0 = new ArrayList();
		$dayList1 = new ArrayList();
		$dayList2 = new ArrayList();
		$dayList3 = new ArrayList();
		$dayList4 = new ArrayList();

		//date for each say of meeting
		$day0 = date('Y-m-d', strtotime($this->StartDate.'-1 day'));
		$day1 = date('Y-m-d', strtotime($this->StartDate));
		$day2 = date('Y-m-d', strtotime($this->StartDate.'+1 day'));
		$day3 = date('Y-m-d', strtotime($this->StartDate.'+2 day'));
		$day4 = date('Y-m-d', strtotime($this->StartDate.'+3 day'));
		
		//get all sessions from this meeting and sort by topic id
		$sessions = $this->MeetingSessions();

		//go through each meeting session and assign them to an array list based on which day of the meeting they occur
		foreach($sessions as $session){
			$date = date('Y-m-d', strtotime($session->Date));
			switch($date){
				case $day0:
					$dayList0->push($session);
					break;
				case $day1:
					$dayList1->push($session);
					break;
				case $day2:
					$dayList2->push($session);
					break;
				case $day3:
					$dayList3->push($session);
					break;
				case $day4:
					$dayList4->push($session);
					break;
			}
		}	
	
		//add lists for each day to parent list
		$dayLists = new ArrayList(array($dayList0, $dayList1, $dayList2, $dayList3, $dayList4));

		$day_array['Day0']['Date'] = date('l j F Y', strtotime($day0));
		$day_array['Day0']['Day'] = 'Day 0';
		$day_array['Day0']['Count'] = $dayList0->Count();

		$day_array['Day1']['Date'] = date('l j F Y', strtotime($day1));
		$day_array['Day1']['Day'] = 'Day 1';
		$day_array['Day1']['Count'] = $dayList1->Count();

		$day_array['Day2']['Date'] = date('l j F Y', strtotime($day2));
		$day_array['Day2']['Day'] = 'Day 2';
		$day_array['Day2']['Count'] = $dayList2->Count();

		$day_array['Day3']['Date'] = date('l j F Y', strtotime($day3));
		$day_array['Day3']['Day'] = 'Day 3';
		$day_array['Day3']['Count'] = $dayList3->Count();

		$day_array['Day4']['Date'] = date('l j F Y', strtotime($day4));
		$day_array['Day4']['Day'] = 'Day 4';
		$day_array['Day4']['Count'] = $dayList4->Count();

		//get all topics
	 	$topics = Topic::get();

	 	//set a counter to zero;
	 	$count = 0;

	 	//loop over the day lists
		foreach($dayLists as $dayList){
		
			//create a key for the day array based on which day of the meeting is is. 
			$day_key = 'Day'.$count;

			//create an array list to hold topics
			$topicList = new ArrayList();
			
			//iterate over the topics
			foreach($topics as $topic){
				if($topic->Title != "Other"){
					//create an array for each topic
					$topic_arr = array();

					//set the title of the array 
					$topic_arr['Title'] = $topic->Title;
					$topic_sessions = new ArrayList();

					//get all sessions from the current day that match the current topic
					foreach($dayList as $d){
						if($d->TopicID == $topic->ID){
							$topic_sessions->push($d);
						}
					}

					$topic_arr['Count'] = $topic_sessions->Count();
					//format the sessions into columns and add the formatted list to the topic array
					$topic_arr['Sessions'] = $this->makeColumns($topic_sessions);

					//create an arraydata based on the topic array
					$topicData = new ArrayData($topic_arr);

					//add topic arraydata to topic list
					$topicList->push($topicData);
				}
			}

			//add other
			$other = $topics->filter(array('Name' => 'Other'))->First();

			$topic_arr = array();

			//set the title of the array 
			$topic_arr['Title'] = $other->Title;
			$topic_sessions = new ArrayList();

			//get all sessions from the current day that match the current topic
			foreach($dayList as $d){
				if($d->TopicID == $other->ID){
					$topic_sessions->push($d);
				}
			}
			
			$topic_arr['Count'] = $topic_sessions->Count();
			//format the sessions into columns and add the formatted list to the topic array
			$topic_arr['Sessions'] = $this->makeColumns($topic_sessions);

			//create an arraydata based on the topic array
			$topicData = new ArrayData($topic_arr);

			//add topic arraydata to topic list
			$topicList->push($topicData);
		
			//create a second level array under the key for the current day and assign the topicList as the List
			$day_array[$day_key]['Topics'] = $topicList;

			//increase count for key
			$count++;
		}
		
		return new ArrayList($day_array);
	}

}
