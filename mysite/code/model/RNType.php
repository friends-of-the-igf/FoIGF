<?php
/**
* An Regional National Meeting type to categorise RNMeetings
*
* @package FoIGF
*/
class RNType extends DataObject{
	static $singular_name = 'Regional/National Meeting Type';
	
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