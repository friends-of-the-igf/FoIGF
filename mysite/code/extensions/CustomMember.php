<?php
class CustomMember extends DataExtension {

	static $db = array(
		'Username' => 'Text',
		'Speaker' => 'Boolean',
		'URLSegment' => 'Varchar(255)'
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

	}


	public function onBeforeWrite() {
		parent::onBeforeWrite();

		if($this->owner->inGroup('Speakers') && !$this->owner->Speaker){
			$this->owner->Speaker = true;
		}

		// if(!$this->URLSegment) {
		// 	$this->URLSegment = $this->Link();
		// 	$this->write();
		// }
	}

}
