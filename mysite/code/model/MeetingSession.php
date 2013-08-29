<?php
class MeetingSession extends DataObject {

	public static $db = array(
		'Title' => 'Text',
		'Date' => 'Date',
		'Tags' => 'Text',
		'NewTags' => 'Text',
		'Views' => 'Int',
		'Content' => 'HTMLText',
		'TranscriptContent' => 'HTMLText',
		'ProposalLink' => 'Text',
		'SortOrder' => 'Int',
		'URLSegment' => 'Varchar(255)'
	
	);

	public static $default_sort='SortOrder';

	public static $has_one = array(
		'Transcript' => 'File',
		'Proposal' => 'File',
		'Meeting' => 'Meeting',
		'Type' => 'Type',
		'Topic' => 'Topic'
	);

	public static $has_many = array(
		'Videos' => 'Video'
	);

	public static $many_many = array(
		'Speakers' => 'Member',
		'RelatedSessions' => 'MeetingSession'
	);
	public static $defaults = array(
		'URLSegment' => null
		);

	public static $summary_fields = array(
		'Title',
		'Date',
		'Meeting.Title'
	);

	public static $field_labels = array(
		'Meeting.Title' => 'Meeting'
		);

	static $searchable_fields = array(
		'Title',
		'Tags',
		'Content',
		'TranscriptContent'
	);

	// fields to return
	static $return_fields = array(
		'Title',
		'Content',
		'TranscriptContent',
		'URLSegment'
	);

	// set index
	public static $indexes = array(
		"fulltext (Title, Tags, Content, TranscriptContent)"
    );

    // REQUIRED: object table must be set to MyISAM
	static $create_table_options = array(
	    'MySQLDatabase' => 'ENGINE=MyISAM'
	);

	public function getCMSFields() {
		$fields = new FieldList();

		$mainTab = new Tab('Main');
		$transcriptTab = new Tab('Transcript');
		$filesTab = new Tab('Files');
		$videosTab = new Tab('Videos');
		$speakersTab = new Tab('View Speakers');
		$addSpeakersTab = new Tab('Add Speakers');
		$sessionsTab = new Tab('RelatedSessions');
		
		$tabset = new TabSet("Root",
			$mainTab,
			$transcriptTab,
			$filesTab,
			$videosTab,
			$addSpeakersTab,
			$speakersTab,
			$sessionsTab
		);
		$fields->push( $tabset );

		$mainTab->push(new TextField('Title', 'Title'));
		$mainTab->push(new TextField('ProposalLink', 'Link to Proposal'));
		$mainTab->push($date = new DateField('Date', 'Date'));
		$date->setConfig('showcalendar', true);
		$date->setConfig('jQueryUI.changeMonth', true);
		$date->setConfig('jQueryUI.changeYear', true);
		$date->setAttribute('placeholder', 'eg. Jan 1, 1999');

		$types = Type::get()->sort('Name');
		if($types->Count()) {
			$mainTab->push(new DropdownField('TypeID', 'Type', $types->map()));			
		}

		// tags
		$tags = $this->allTagsArray();
		asort($tags);
		$mainTab->push(ListboxField::create('Tags', 'Tags (pre-defined)')
			->setMultiple(true)
			->setSource($tags)
		);

		$mainTab->push(new TextField('NewTags', 'New Tags (adds to pre-defined list, comma seperated eg tag1,tag2,tag3)'));

		$topics = Topic::get()->sort('Name');
		if($topics->Count()) {
			$mainTab->push(new DropdownField('TopicID', 'Topic', $topics->map()));			
		}

		$meetings = Meeting::get();
		if($meetings->count() != 0) {
			$mainTab->push(new DropdownField('MeetingID', 'Meeting', $meetings->map('ID', 'getYearLocation')));
		}
		$mainTab->push(new HTMLEditorField('Content', 'Content'));

		$transcriptTab->push(new HTMLEditorField('TranscriptContent', 'TranscriptContent'));

		$filesTab->push(new UploadField('Transcript', 'Transcript'));
		$filesTab->push(new UploadField('Proposal', 'Proposal'));

		if($this->ID) {
			$group = $this;
			$gridFieldConfig = new GridFieldConfig_RecordEditor();
			$list = $this->Videos();
			$gridField = new GridField('Videos', 'Videos', $list, $gridFieldConfig);
			$videosTab->push($gridField);
		}

		$addSpeakersTab->push(new TextField('FirstName', 'First Name'));
		$addSpeakersTab->push(new TextField('Surname', 'Surname'));
		$addSpeakersTab->push(new TextField('Bio', 'Link to Bio'));

		if($this->ID) {
			$group = $this;
			$config = new GridFieldConfig_RelationEditor();
			$config->getComponentByType('GridFieldAddExistingAutocompleter')
				->setResultsFormat('$Title ($Email)')->setSearchFields(array('FirstName', 'Surname', 'Email'));
			$memberList = GridField::create('Speakers',false, $this->Speakers(), $config);
			$speakersTab->push($memberList);
		}

		if($this->ID) {
			$group = $this;
			$config = new GridFieldConfig_RelationEditor();
			$sessionList = GridField::create('RelatedSessions',false, $this->RelatedSessions(), $config);
			$sessionsTab->push($sessionList);
		}

		return $fields;
	}

	public function onBeforeWrite() {

		parent::onBeforeWrite();

		//Create member from add speaker fields
		if(array_key_exists('FirstName', $this->record) && $this->record['FirstName'] != null && array_key_exists('Surname', $this->record) && $this->record['Surname'] != null){
			if(Member::get()->filter(array('FirstName' => $this->record['FirstName'], 'Surname' => $this->record['Surname']))->Count() > 0){
				$member = Member::get()->filter(array('FirstName' => $this->record['FirstName'], 'Surname' => $this->record['Surname']))->First();
				$this->Speakers()->add($member);
				if(!$member->inGroup('Speakers')){
					$member->addToGroupByCode('speaker');
				}
			} else{
				$member = new Member();
				$member->FirstName = $this->record['FirstName'];
				$member->Surname = $this->record['Surname'];
				$username = substr($this->record['Surname'], 0, 5);
				$username .= substr($this->record['FirstName'], 0, 3);
				$member->Username = strtolower($username);
				$member->Email = 'speaker@igf.com';
				$member->Password = 'igf123';
				$member->BioLink = $this->record['Bio'];
				$member->write();
				$member->addToGroupByCode('speakers');
				$this->Speakers()->add($member);
			}
		}
		

		
		if($this->NewTags) {
			if($this->Tags != null){
				$this->Tags .= ',' . $this->NewTags;
			} else {
				$this->Tags = $this->NewTags;
			}
			$this->NewTags = null;

			$this->write();
		}

		if(!$this->URLSegment || $this->URLSegment = 'session/0') {
			$this->URLSegment = $this->Link();
		}

		if(!Topic::get()->byID($this->TopicID)){
			$this->TopicID = Topic::get()->filter(array('Name' => 'Other'))->First()->ID;
		}
		if(!Type::get()->byID($this->TypeID)){
			$this->TypeID = Type::get()->filter(array('Name' => 'Other'))->First()->ID;
		}
		
	}


	public function Link($action = null) {
		return Controller::join_links('session', $this->ID, $action);
	}

	public function TagsCollection() {
		$tags = preg_split("*,*", trim($this->Tags));
		$output = new ArrayList();
		
		$link = "";
		if($page = SessionsHolder::get()->First()) {
			$link = $page->Link('tag');
		}

		foreach($tags as $tag) {
			if($tag != ''){
				$output->push(new ArrayData(array(
					'Tag' => $tag,
					'Link' => $link . '/' . urlencode($tag),
					'URLTag' => urlencode($tag)
				)));
			}
		}
		
		if($this->Tags) {
			return $output;
		}
	}

	public function allTagsArray() {
		$sessions = MeetingSession::get();
		$list = array();	
		foreach($sessions as $session) {
			$tags = preg_split("*,*", trim($session->Tags));
			foreach($tags as $tag) {
				if($tag != "") {
					$tag = strtolower($tag);
					$list[$tag] = $tag;
				}
			}
		}
		return $list;
	}

	public function getVideo(){
		if($this->Videos()->Count() != 0) {
			$vid = $this->Videos()->first();
			return '<iframe width="100%" height="100%" class="youtube-player" type="text/html" src="http://www.youtube.com/v/'.$vid->YouTubeID.'?controls=0&showinfo=0&html5=1" frameborder="0"></iframe>';
		}
    }

    public function getVideoThumb(){
    	if($this->Videos()->Count() != 0) {
			$vid = $this->Videos()->first();
			return '<img width="100%" height="100%" class="thumb" src="http://img.youtube.com/vi/'.$vid->YouTubeID.'/0.jpg" />';
   		}
    }

    public function getSpeakers(){
    	return $this->Speakers();
    }

    public function getRelatedSessions(){

    	$list = new ArrayList();
    	if($this->RelatedSessions()){
    		foreach($this->RelatedSessions() as $related){
    			$list->push($related);
    		}
    	} 
   
    	if($list->Count() < 3){
			$sessions = MeetingSession::get()->leftJoin('MeetingSession_Speakers', 'MeetingSession.ID = MeetingSession_Speakers.MeetingSessionID');
			if($this->Speakers()->Count() != 0){
				$speakers = $this->Speakers();
				$speakerArray = array();
				foreach($speakers as $speaker){
					array_push($speakerArray, $speaker->Name);
				}
				$stSessions = $sessions->filter(array('TopicID' => $this->TopicID, 'MemberID' => $speakerArray));
				foreach($stSessions as $session){
					if($session->ID != $this->ID){
						$list->push($session);
					}
				}
				if($list->Count() < 3){
					$remainder = 3 - $list->Count();
					$topicSessions = $sessions->filter(array('TopicID' => $this->TopicID));
					for($i = 0; $i < $remainder; $i++){
						foreach($topicSessions as $tSession){
								if($tSession->ID != $this->ID){
									$list->push($tSession);
								}	
						}
					}
				}
			}
			
		}

    	return $list->limit(3);
    }

    // static tag functions
    public static function get_unique_tags($filter = null) {
    	$sessions = MeetingSession::get();
    	if($filter) {
    		$sessions = $sessions->filter('MeetingID', $filter);
    	}
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
		return $uniqueTagsArray;
    }

    public static function get_all_tags($filter = null) {
    	$sessions = MeetingSession::get();
    	if($filter) {
    		$sessions = $sessions->filter('MeetingID', $filter);
    	}
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
