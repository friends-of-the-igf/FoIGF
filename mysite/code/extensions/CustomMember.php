<?php
class CustomMember extends DataExtension {

	static $db = array(
		'Username' => 'Text',
		'BioLink' => 'Text'
		);

	static $has_one = array(
		'ProfilePhoto' => 'Image'
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
	}

}
