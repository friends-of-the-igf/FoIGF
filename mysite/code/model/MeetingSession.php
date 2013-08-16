<?php
class MeetingSession extends DataObject {

	public static $db = array(
		'Title' => 'Text',
		'Date' => 'Date',
		'Tags' => 'Text',
		'Views' => 'Int',
		'Content' => 'HTMLText',
		'TranscriptContent' => 'HTMLText'
	);

	public static $has_one = array(
		'Transcript' => 'File',
		'Proposal' => 'File',
		'Meeting' => 'Meeting',
		'Type' => 'Type'
	);

	public static $has_many = array(
		'Videos' => 'Video'
	);

	public static $many_many = array(
		'Speakers' => 'Member'
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
		$tabset = new TabSet("Root",
			$mainTab,
			$transcriptTab,
			$filesTab,
			$videosTab,
			$speakersTab
		);
		$fields->push( $tabset );

		$mainTab->push(new TextField('Title', 'Title'));
		$mainTab->push($date = new DateField('Date', 'Date'));
		$date->setConfig('showcalendar', true);

		$types = Type::get()->sort('Name');
		if($types->Count()) {
			$mainTab->push(new DropdownField('TypeID', 'Type', $types->map()));			
		}	

		$mainTab->push(new TextField('Tags', 'Tags (comma seperated)'));
		$meetings = Meeting::get();
		if($meetings->count() != 0) {
			$mainTab->push(new DropdownField('MeetingID', 'Meeting', $meetings->map()));
		}
		$mainTab->push(new HTMLEditorField('Content', 'Content'));

		$transcriptTab->push(new HTMLEditorField('TranscriptContent', 'TranscriptContent'));

		$filesTab->push(new UploadField('Transcript', 'Transcript'));
		$filesTab->push(new UploadField('Proposal', 'Proposal'));

		$gridFieldConfig = new GridFieldConfig_RecordEditor();
		$list = $this->Videos();
		$gridField = new GridField('Videos', 'Videos', $list, $gridFieldConfig);
		$videosTab->push($gridField);

		if($this->ID) {
			$group = $this;
			$config = new GridFieldConfig_RelationEditor();
			$config->getComponentByType('GridFieldAddExistingAutocompleter')
				->setResultsFormat('$Title ($Email)')->setSearchFields(array('FirstName', 'Surname', 'Email'));
			$memberList = GridField::create('Speakers',false, $this->Speakers(), $config);
			$speakersTab->push($memberList);
		}

		return $fields;
	}

	public function Link($action = null) {
		return Controller::join_links('session', $this->ID, $action);
	}

	public function TagsCollection() {
		$tags = preg_split(" *, *", trim($this->Tags));
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

	public function getVideo(){
		if($this->Videos()->Count() != 0) {
			$vid = $this->Videos()->first();
			return '<iframe width="100%" height="100%" src="http://www.youtube.com/v/'.$vid->YouTubeID.'?controls=0&showinfo=0" frameborder="0"></iframe>';
		}
    }

    public function getRelatedSessions(){
    	return MeetingSession::get()->limit(3);
    }

 

}
