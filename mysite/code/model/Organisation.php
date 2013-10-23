<?php

class Organisation extends DataObject{
	
	
	static $db = array(
		'Title' => 'Text',
	
		);

	static $has_many = array(
		'Organisers' => 'Member'
		);


	public function getCMSFields(){
		$fields = new FieldList();

		$fields->push(new TextField('Title', 'Title'));

		return $fields;

	}



}