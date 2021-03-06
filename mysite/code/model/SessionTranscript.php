<?php

class SessionTranscript extends DataObject{
	
	public static $db = array(
		'TranscriptType' => 'Text',
		'Language' => 'Text',
		'Content' => 'HTMLText'
		);

	public static $has_one = array(
		'Transcript' => 'File',
		'Language' => 'VideoLanguage',
		'MeetingSession' => 'MeetingSession'
		);

	public static $summary_fields = array(
        'TranscriptType',
        'Language.Name'
    );

    public static $field_labels = array(
        'Language.Name' => 'Language'
    );

	 // REQUIRED: object table must be set to MyISAM
	static $create_table_options = array(
	    'MySQLDatabase' => 'ENGINE=MyISAM'
	);

	public function getCMSFields(){

		$fields = new FieldList();

		$langs = VideoLanguage::get()->map()->toArray();
        if($langs){
            $fields->push(new DropdownField('LanguageID', 'Language', $langs));
        }

		$fields->push(new OptionsetField('TranscriptType', 'Transcript Type (Select one and click save)',
		array('File' => 'File', 'Text' => 'Text')));

		if($this->TranscriptType == 'Text'){
			$fields->push(new HTMLEditorField('Content', 'Transcript Content'));
		} elseif($this->TranscriptType == 'File' ){
			$fields->push(new UploadField('Transcript', 'Transcript'));
		}

		return $fields;
	}

	public function Link($action = null) {
		return Controller::join_links('transcript', $this->ID, $action);
	}
}