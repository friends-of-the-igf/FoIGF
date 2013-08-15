<?php
class Location extends DataObject {

	public static $db = array(
		'Name' => 'Text'
	);

	public static $has_many = array(
		'Meetings' => 'Meeting'
	);

	public function getCMSFields() {
		$fields = new FieldList();
		$fields->push(new TextField('Name', 'Name'));
		return $fields;
	}
}
