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
}