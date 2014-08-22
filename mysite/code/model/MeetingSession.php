<?php
/**
* Meeting Session. Represents a Meeting Session's data.
*
* @package FoIGF
*/
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
		'URLSegment' => 'Varchar(255)',
		'ProposalContent' => 'HTMLText',
		'TranscriptType' => 'Text',
		'ProposalType' => 'Text',
		'Day' => 'Text',
		'ReportContent' => 'HTMLText',
		'ReportType' => 'Text'


	
	);

	public static $default_sort='SortOrder';

	public static $has_one = array(
		'Transcript' => 'File',
		'Proposal' => 'File',
		'Report' => 'File',
		'Meeting' => 'Meeting',
		'Type' => 'Type',
		'Topic' => 'Topic',
		'Organiser' => 'Member'

	);

	public static $has_many = array(
		'Videos' => 'Video',
		'Transcripts' => 'SessionTranscript',
		'TagRatings' => 'TagRating'
	);

	public static $many_many = array(
		'Speakers' => 'Member',
		'RelatedSessions' => 'MeetingSession',
		'Tags' => 'Tag'
	);
	public static $defaults = array(
		'URLSegment' => null
		);

	public static $summary_fields = array(
		'ID',
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
		'TranscriptContent',
		'ProposalContent'
	);

	// fields to return
	static $return_fields = array(
		'Title',
		'Content',
		'TranscriptContent',
		'ProposalContent',
		'URLSegment'
	);

	// set index
	public static $indexes = array(
		"fulltext (Title, Tags, Content, TranscriptContent, ProposalContent)"
    );

    // REQUIRED: object table must be set to MyISAM
	static $create_table_options = array(
	    'MySQLDatabase' => 'ENGINE=MyISAM'
	);

	public function getCMSFields() {
		$fields = new FieldList();

		$mainTab = new Tab('Main');
		$transcriptTab = new Tab('Transcript');
		$proposalTab = new Tab('Proposal');
		$videosTab = new Tab('Videos');
		$speakersTab = new Tab('Speakers');
		$sessionsTab = new Tab('RelatedSessions');
		$reportTab = new Tab('Report');
		$tagTab = new Tab('Tags');
		$ocTab = new Tab('OpenCalais');
		
		$tabset = new TabSet("Root",
			$mainTab,
			$transcriptTab,
			$proposalTab,
			$videosTab,
			$speakersTab,
			$sessionsTab,
			$reportTab,
			$tagTab
		);
		$fields->push( $tabset );

		$mainTab->push(new TextField('Title', 'Title'));
		
		$mainTab->push($date = new DateField('Date', 'Date'));
		$date->setConfig('showcalendar', true);
		$date->setConfig('jQueryUI.changeMonth', true);
		$date->setConfig('jQueryUI.changeYear', true);
		$date->setAttribute('placeholder', 'eg. Jan 1, 1999');

		$types = Type::get()->sort('Name');
		if($types->Count()) {
			$mainTab->push(new DropdownField('TypeID', 'Type', $types->map()));			
		}

		$topics = Topic::get()->sort('Name');
		if($topics->Count()) {
			$mainTab->push(new DropdownField('TopicID', 'Topic', $topics->map()));			
		}

		if($this->ID) {
			$group = $this;
			$gridFieldConfig = new GridFieldConfig_RecordEditor();
			$list = $this->Transcripts();
			$gridField = new GridField('Transcripts', 'Transcripts', $list, $gridFieldConfig);
			$transcriptTab->push($gridField);
		}



		$meetings = Meeting::get();
		if($meetings->count() != 0) {
			$mainTab->push(new DropdownField('MeetingID', 'Meeting', $meetings->map('ID', 'getYearLocation')));
		}
		$mainTab->push(new HTMLEditorField('Content', 'Content'));

		$proposalTab->push(new OptionsetField('ProposalType', 'Proposal Type (Select one and click save)', array('URL' => 'URL', 'File' => 'File', 'Text' => 'Text')));
		if($this->ProposalType == 'URL'){
			$proposalTab->push(new TextField('ProposalLink', 'Link to Proposal'));
		} elseif($this->ProposalType =='File'){
			$proposalTab->push(new UploadField('Proposal', 'Proposal'));
		} elseif($this->ProposalType == 'Text'){
			$proposalTab->push(new HTMLEditorField('ProposalContent', 'Proposal Content'));
		}

		$reportTab->push(new OptionsetField('ReportType', 'Report Type (Select one and click save)', array('File' => 'File', 'Text' => 'Text')));
		if($this->ReportType =='File'){
			$reportTab->push(new UploadField('Report', 'Report'));
		} elseif($this->ReportType == 'Text'){
			$reportTab->push(new HTMLEditorField('ReportContent', 'Report Content'));
		}

		if($this->ID) {
			$group = $this;
			$gridFieldConfig = new GridFieldConfig_RecordEditor();
			$list = $this->Videos();
			$gridField = new GridField('Videos', 'Videos', $list, $gridFieldConfig);
			$videosTab->push($gridField);
		}


		if($this->ID) {
			$group = $this;
			$config = new GridFieldConfig_RelationEditor();
			$config->getComponentByType('GridFieldAddExistingAutocompleter')
				->setResultsFormat('$Title ($Email)')->setSearchFields(array('FirstName', 'Surname', 'Email'));
			$config->removeComponent($config->getComponentByType('GridFieldAddNewButton'));
			$config->removeComponent($config->getComponentByType('GridFieldAddExistingAutocompleter'));
			$memberList = GridField::create('Speakers',false, $this->Speakers(), $config);

			//existing
 			$speakersTab->push(new HeaderField('ExistingSpeakers', 'Add Existing Speakers'));

			// $speakers = Group::get()->filter(array('Title' => 'Speakers'))->First()->Members();

			$meeting = $this->Meeting();
			$sib_sessions = $meeting->MeetingSessions();
			$list = new ArrayList();
			foreach($sib_sessions as $session){
				$s_speakers = $session->Speakers();
				foreach($s_speakers as $speak){
					$list->push($speak);
				}
			}
			$speakers = $list->map();
			asort($speakers);
			$speakersTab->push(ListboxField::create('ExistSpeakers', 'Speakers To Add')
				->setMultiple(true)
				->setSource($speakers)
			);

			//new
			$speakersTab->push(new HeaderField('NewSpeaker', 'Add New Speaker'));
			$speakersTab->push(new TextField('FirstName', 'First Name'));
			$speakersTab->push(new TextField('Surname', 'Surname'));
			$speakersTab->push(new TextField('Bio', 'Link to Bio'));

			//added
			$speakersTab->push(new HeaderField('AddedSpeakers', 'Added Speakers'));
			$speakersTab->push($memberList);

			//organiser
		}

		if($this->ID) {
			$group = $this;
			$config = new GridFieldConfig_RelationEditor();
			$sessionList = GridField::create('RelatedSessions',false, $this->RelatedSessions(), $config);
			$sessionsTab->push($sessionList);
		}

		if($this->ID){
			$tagTab->push(new GridField('Tags', 'Tags', $this->Tags(), GridFieldConfig_RelationEditor::create()));
		}

		$config = SiteConfig::current_site_config();

		if($this->ID && $config->OpenCalaisAPIKey){
			$ocTab->push($button = new FormAction('extractTags', 'Suggest Tags'));
			$button->setAttribute('data-base', Director::baseURL());
			$button->setAttribute('data-id', $this->ID);

			$ocTab->push($button = new LiteralField('table-holder', '<div id="table-holder"><img class="loading" style="display:none;" src="mysite/images/ajax-loader.gif"></div></div>'));
			$tabset->push($ocTab);
		}

		Requirements::javascript('mysite/javascript/opencalais.js');
		Requirements::css('mysite/css/opencalais.css');

		return $fields;
	}

	public function onBeforeWrite() {
		
		parent::onBeforeWrite();

		//Create member from add speaker fields
		if(array_key_exists('FirstName', $this->record) && $this->record['FirstName'] != null && array_key_exists('Surname', $this->record) && $this->record['Surname'] != null){
			if(Member::get()->filter(array('FirstName' => $this->record['FirstName'], 'Surname' => $this->record['Surname']))->Count() > 0){
				$member = Member::get()->filter(array('FirstName' => $this->record['FirstName'], 'Surname' => $this->record['Surname']))->First();
				$this->Speakers()->add($member);
				$member->BioLink = $this->record['Bio'];
				if(!$member->inGroup('Speakers')){
					$member->addToGroupByCode('speaker');

				}
				$member->write();
			} else{
				$member = new Member();
				$username = substr($this->record['Surname'], 0, 5);
				$username .= substr($this->record['FirstName'], 0, 3);
				if(Member::get()->filter(array('Username' => strtolower($username)))->Count() == 0){
					$member->Username = strtolower($username);
					$member->FirstName = $this->record['FirstName'];
					$member->Surname = $this->record['Surname'];
					$member->Email = 'speaker@igf.com';
					$member->Password = 'igf123';
					$member->BioLink = $this->record['Bio'];
					$member->write();
					$member->addToGroupByCode('speakers');
					$this->Speakers()->add($member);
				} else {
					$member = Member::get()->filter(array('Username' => strtolower($username)))->First();
					if($this->record['Bio'] != null){
						$member->BioLink = $this->record['Bio'];
					}
					$member->write();
					$member->addToGroupByCode('speakers');
					$this->Speakers()->add($member);
				}
			}
		}

		if(array_key_exists('ExistSpeakers', $this->record) && $this->record['ExistSpeakers'] != null){
			$existing_new = explode(',', $this->record['ExistSpeakers']);
			if(!empty($existing_new)){
				foreach($existing_new as $speakerID){
					if(strlen($speakerID) > 0){
						$this->Speakers()->add(Member::get()->byID($speakerID));
					}
					
				}
			}
		}

		//save a url segment
		if(!$this->URLSegment || $this->URLSegment = 'session/0') {
			$this->URLSegment = $this->Link();
		}

		//default topics and types
		if(!Topic::get()->byID($this->TopicID)){
			$this->TopicID = Topic::get()->filter(array('Name' => 'Other'))->First()->ID;
		}
		if(!Type::get()->byID($this->TypeID)){
			$this->TypeID = Type::get()->filter(array('Name' => 'Other'))->First()->ID;
		}

		if($this->Day == null){
			//determine day of meeting
			$day0 = date('Y-m-d', strtotime($this->Meeting()->StartDate.'-1 day'));
			$day1 = date('Y-m-d', strtotime($this->Meeting()->StartDate));
			$day2 = date('Y-m-d', strtotime($this->Meeting()->StartDate.'+1 day'));
			$day3 = date('Y-m-d', strtotime($this->Meeting()->StartDate.'+2 day'));
			$day4 = date('Y-m-d', strtotime($this->Meeting()->StartDate.'+3 day'));
			
			$date = date('Y-m-d', strtotime($this->Date));
			switch($date){
				case $day0:
					$this->Day = 0;
					break;
				case $day1:
					$this->Day = 1;
					break;
				case $day2:
					$this->Day = 2;
					break;
				case $day3:
					$this->Day = 3;
					break;
				case $day4:
					$this->Day = 4;
					break;
			}
		}	
	}


	public function Link($action = null) {
		return Controller::join_links('session', $this->ID, $action);
	}


	/**
	 * Gets the first video attached to this Meeting Session. 
	 * 
	 * @return Video.
	 */
	public function getVideo(){
		if($this->Videos()->Count() != 0) {
			$vid = $this->Videos()->first();
			return $vid;
		}
    }

    public function LangVideos(){
    	$videos = Video::get()->filter(array('MeetingSessionID' => $this->ID));
    	$languages = array();
    	foreach($videos as $video){
    		$languages[$video->Language()->ID] = $video->Language()->Name;
    	}
    	$languages = array_unique($languages);
    	$list = new ArrayList();
    	foreach($languages as $id => $language){
    		$data['Language'] = $language;
    		$data['Videos'] = $videos->filter(array('LanguageID' => $id)); 
    		$list->push($data);
    	}
    	return $list;
    }

    /**
	 * Gets the thumbnail for the first video attached to this Meeting Session if the video is Youtube. 
	 * 
	 * @return String.
	 */
    public function getVideoThumb(){
    	if($this->Videos()->Count() != 0) {
			$vid = $this->Videos()->first();
			if($vid->YouTubeID != '' && $vid->YouTubeID != null){
				return '<img alt="'.$this->Title.'" width="100%" height="100%" class="thumb" src="http://img.youtube.com/vi/'.$vid->YouTubeID.'/0.jpg" />';
			}
   		}
    }

    /**
	 * Gets a list of this Meeting Session's speakers. 
	 * 
	 * @return ManyManyList.
	 */
    public function getSpeakers(){
    	return $this->Speakers();
    }

    /**
	 * Gets a list of 3 releated sessions based on manually added sessions, topics and speakers. 
	 * 
	 * @return ArrayList.
	 */
    public function getRelatedSessions(){

    	$list = new ArrayList();
    	if($this->RelatedSessions()){
    		foreach($this->RelatedSessions() as $related){
    			$list->push($related);
    		}
    	} 

    	if($list->Count() < 3){
    		if($this->Speakers()->Count() > 0){
    			$speakers = $this->Speakers();
    			$speakerSessions = new ArrayList();
				foreach($speakers as $speaker){
					foreach($speaker->MeetingSessions() as $session){
						if($session->ID != $this->ID){
							$speakerSessions->push($session);
						}
					}
				}
				foreach($speakerSessions as $speakerSession){
					if($speakerSession->TopicID == $this->TopicID){
						$list->push($speakerSession);
					}
				}
    		} 
    	}

    	if($list->Count() < 3){
    		foreach($this->Topic()->MeetingSessions() as $session){
    			if($session->ID != $this->ID){
    				$list->push($session);
    			}
    		}
    	}
    	
    	$list->removeDuplicates();

    	return $list->limit(3);
    }

    public function hasTags(){
    	return ($this->Tags()->Count() > 0) ? true : false;
    }

    public function hasPendingTags(){
    	return ($this->Tags()->filter('Status', 'Pending')->Count() > 0) ? true : false;
    }

    public function getApprovedTags(){
    	return $this->Tags()->filter('Status', 'Approved');
    }






}
