<?php
class Topic extends DataObject {

	public static $db = array(
		'Name' => 'Text'
	);

	public static $belongs_many_many = array(
		'Meetings' => 'Meeting'
	);

	public function getCMSFields() {
		$fields = new FieldList();
		$fields->push(new TextField('Name', 'Name'));
		return $fields;
	}
}
