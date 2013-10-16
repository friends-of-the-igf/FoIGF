<?php
class CustomMember extends DataExtension {

	static $db = array(
		'Username' => 'Text',
		'BioLink' => 'Text',
		'Tagger' => 'Boolean'
		);

	static $has_one = array(
		'ProfilePhoto' => 'Image'
		);

	static $has_many = array(
		'OrganisedSessions' => 'MeetingSession'
		);


	public static $belongs_many_many = array(
		'MeetingSessions' => 'MeetingSession'
	);

	static $searchable_fields = array(
		'FirstName',
		'Surname'
	);

	// fields to return
	static $return_fields = array(
		'FirstName',
		'Surname'
	);

	// set index
	public static $indexes = array(
		"fulltext (FirstName, Surname)"
    );

    // REQUIRED: object table must be set to MyISAM
	static $create_table_options = array(
	    'MySQLDatabase' => 'ENGINE=MyISAM'
	);

	public function updateCMSFields(FieldList $fields){
		$fields->removeByName('UserName');
		$fields->insertBefore(new TextField('Username', 'Username'), 'Email');
		$fields->removeByName('BioLink');
		$fields->insertBefore(new TextField('BioLink', 'Link to Bio'), 'Email');
		$fields->removeByName('Tagger');
		$fields->insertBefore(new CheckboxField('Tagger', 'Can add tags to their Sessions'), 'Email');


		$config = new GridFieldConfig_RelationEditor();
		$config->getComponentByType('GridFieldAddExistingAutocompleter')
			->setResultsFormat('$Title $Date')->setSearchFields(array('Title', 'Date'));
		$sessionList = GridField::create('OrganisedSessions', 'Organised Sessions', $this->owner->OrganisedSessions(), $config);
		$fields->addFieldToTab('Root.OrganisedSessions', $sessionList);

	}

	public function updateSummaryFields(&$fields){
		$fields['BioLink'] = 'Bio Link';
	}

	public function Link(){
			$link = "";
		if($page = SessionsHolder::get()->First()) {
			$link = $page->Link('?speaker=').$this->owner->ID;
		}

		return $link;
	}

}
