<?php
/**
* A Regional National Region to categorise RNMeetings
*
* @package FoIGF
*/
class RNRegion extends DataObject{
	static $singular_name = 'Regional/National Meeting Region';
	
	static $db = array(
		'Title' => 'Text',
	
		);

	static $many_many = array(
		'Meetings' => 'RNMeeting'
		);


	public function getCMSFields(){
		$fields = new FieldList();

		$fields->push(new TextField('Title', 'Title'));

		return $fields;

	}



}