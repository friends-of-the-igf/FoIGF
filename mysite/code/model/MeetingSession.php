<?php
class MeetingSession extends DataObject {

	public static $db = array(
		'Title' => 'Text',
		'Date' => 'Date',
		'Type' => 'Text',
		'Location' => 'Text',
		'Tags' => 'Text',
		'Views' => 'Int',
		'Content' => 'HTMLText',
		'YouTubeID' => 'VarChar'
	);

	public static $has_one = array(
		'Transcript' => 'File',
		'Proposal' => 'File',
		'Meeting' => 'Meeting'
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
		$filesTab = new Tab('Files');
		$speakersTab = new Tab('Speakers');
		$tabset = new TabSet("Root",
			$mainTab,
			$filesTab,
			$speakersTab
		);
		$fields->push( $tabset );

		$mainTab->push(new TextField('Title', 'Title'));
		$mainTab->push($date = new DateField('Date', 'Date'));
		$date->setConfig('showcalendar', true);
		$mainTab->push(new TextField('Type', 'Type'));	
		$mainTab->push(new TextField('Location', 'Location'));
		$mainTab->push(new TextField('Tags', 'Tags (comma seperated)'));
		$mainTab->push(new TextField('YouTubeID', 'YouTube ID (can be ID or full URL)'));
		$meetings = Meeting::get();
		if($meetings->count() != 0) {
			$mainTab->push(new DropdownField('MeetingID', 'Meeting', $meetings->map()));
		}
		$mainTab->push(new HTMLEditorField('Content', 'Content'));

		$filesTab->push(new UploadField('Transcript', 'Transcript'));
		$filesTab->push(new UploadField('Proposal', 'Proposal'));

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

	public function onAfterWrite(){
		parent::onAfterWrite();

		$this->formatYouTubeID();
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

	public function formatYouTubeID(){

		$url = $this->YouTubeID;
         
        $params = explode('?',$url);

        if(count($params) > 1) {

            $paras = explode('&',$params[1]);
            foreach($paras as $para) {
                $type = substr($para,0,2);
                if($type == 'v=') {
                    $str = explode('=',$para);
                    $v = $str[1];
                    if($v != $url) {
                    	error_log($v);
                        $this->YouTubeID = $v;
                        $this->write();
                    }
                }
            }
        }
        $params = explode('be/',$url);
            if(count($params) > 1) {
            $paras = explode('?',$params[1]);
            if(count($paras) == 1){
                if($paras[0] != $url){
                	error_log($paras[0]);
                    $this->YouTubeID = $paras[0];
                    $this->write();
                }
            } 
        }  
	}

	  public function getVideo(){
        if($this->YouTubeID != null){
            return '<iframe width="192" height="130" src="http://www.youtube.com/v/'.$this->YouTubeID.'?controls=0&showinfo=0" frameborder="0"></iframe>';
        
        }
    }

}
