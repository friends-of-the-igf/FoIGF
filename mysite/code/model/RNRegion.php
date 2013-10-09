<?php

class RNRegion extends DataObject{
	static $singular_name = 'Regional/National Meeting Region';
	
	static $db = array(
		'Title' => 'Text',
	
		);

	static $has_many = array(
		'Meetings' => 'RNMeetings'
		);

	public function getCMSFields(){
		$fields = new FieldList();

		$fields->push(new TextField('Title', 'Title'));

		return $fields;

	}



}