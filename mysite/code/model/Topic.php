<?php
class Topic extends DataObject {

	public static $db = array(
		'Name' => 'Text'
	);

	public static $has_many = array(
		'MeetingSessions' => 'MeetingSession'
	);

	public function getCMSFields() {
		$fields = new FieldList();
		$fields->push(new TextField('Name', 'Name'));
		return $fields;
	}
}
