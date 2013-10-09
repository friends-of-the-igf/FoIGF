<?php

class RNMeeting extends DataObject{

	static $singular_name = 'Regional/National Meeting';
	
	static $db = array(
		'Title' => 'Text',
		'Website' => 'Text'
		);

	static $has_one = array(
		'Type' => 'RNType',
		'Region' => 'RNRegion'
		);

	public function getCMSFields(){
		$fields = new FieldList();
		$fields->push( new TextField('Title', 'Title'));
		$fields->push( new TextField('Website', 'Website'));

		$types = RNType::get()->sort('Title');
		if($types->Count()) {
			$fields->push(new DropdownField('TypeID', 'Type', $types->map('ID', 'Title')));			
		}

		$regions = RNRegion::get()->sort('Title');
		if($regions->Count()) {
			$fields->push(new DropdownField('RegionID', 'Region', $regions->map('ID', 'Title')));			
		}	


		return $fields;


	}



}