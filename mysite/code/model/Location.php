<?php
class Location extends DataObject {

	public static $db = array(
		'City' => 'Text',
		'Country' => 'Text'
	);

	public static $has_many = array(
		'Meetings' => 'Meeting'
	);

	public static $summary_fields = array(
		'City',
		'Country'	
	);

	public function getCMSFields() {
		$fields = new FieldList();
		$fields->push(new TextField('City', 'City'));
		$fields->push(new TextField('Country', 'Country'));
		return $fields;
	}

	public function Name(){
		return $this->City.', '.$this->Country;
	}

	public function Link(){
		$link = "";
		if($page = SessionsHolder::get()->First()) {
			$link = $page->Link('location/').$this->ID;
		}

		return $link;
	}
}
