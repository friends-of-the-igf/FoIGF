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

		//list box field for selected "organised sessions"
		$sessions = MeetingSession::get();
		$ses_arr = array();
		$list = new ArrayList();
		foreach($sessions as $session){
			$ses_arr[$session->ID] = $session->Title.' - '.$session->Date;
		}	
		asort($ses_arr);
		$fields->addFieldToTab('Root.OrganisedSessions', ListboxField::create('OrganisedSessions', 'Select Sessions this member has organised and press save to add to list. ')
			->setMultiple(true)
			->setSource($ses_arr)
		);

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

	// public function onBeforeWrite(){
	// 	foreach ($this->owner->record as $key => $value) {
	// 		error_log($key.'->'.$value);
	// 	}

	// 	// error_log($this->owner->record['OrgSessions[]']);
	// 	// if(array_key_exists('OrgSessions', $this->owner->record) && $this->owner->record['OrgSessions'] != null){
	// 	// 	error_log($this->owner->record['OrgSessions']);
	// 	// }

	// 	parent::onBeforeWrite();
	// }

}
