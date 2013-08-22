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
		'ProposalLink' => 'Text'
	);

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

	public static $summary_fields = array(
		'Title',
		'Date'
	);

	public function getCMSFields() {
		$fields = new FieldList();

		$mainTab = new Tab('Main');
		$transcriptTab = new Tab('Transcript');
		$filesTab = new Tab('Files');
		$videosTab = new Tab('Videos');
		$speakersTab = new Tab('Speakers');
		$sessionsTab = new Tab('RelatedSessions');
		
		$tabset = new TabSet("Root",
			$mainTab,
			$transcriptTab,
			$filesTab,
			$videosTab,
			$speakersTab,
			$sessionsTab
		);
		$fields->push( $tabset );

		$mainTab->push(new TextField('Title', 'Title'));
		$mainTab->push(new TextField('ProposalLink', 'Link to Proposal'));
		$mainTab->push($date = new DateField('Date', 'Date'));
		$date->setConfig('showcalendar', true);

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

	public function onAfterWrite() {
		parent::onAfterWrite();

		if($this->NewTags) {
			$this->Tags .= ',' . $this->NewTags;
			$this->NewTags = null;
			$this->write();
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
			$output->push(new ArrayData(array(
				'Tag' => $tag,
				'Link' => $link . '/' . urlencode($tag),
				'URLTag' => urlencode($tag)
			)));
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
			return '<iframe width="100%" height="100%" src="http://www.youtube.com/v/'.$vid->YouTubeID.'?controls=0&showinfo=0" frameborder="0"></iframe>';
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

}
