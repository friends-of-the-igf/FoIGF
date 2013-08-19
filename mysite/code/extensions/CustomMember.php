<?php
class CustomMember extends DataExtension {

	static $db = array(
		'Username' => 'Text'
		);

	public static $belongs_many_many = array(
		'MeetingSessions' => 'MeetingSession'
	);

	public function updateCMSFields(FieldList $fields){
		$fields->removeByName('UserName');
		$fields->insertBefore(new TextField('Username', 'Username'), 'Email');

	}

}
