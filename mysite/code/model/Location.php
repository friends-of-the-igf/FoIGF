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

	static $searchable_fields = array(
		'City',
		'Country'
	);

	// fields to return
	static $return_fields = array(
		'City',
		'Country',
		'ID'
	);

	// set index
	public static $indexes = array(
		"fulltext (City, Country)"
    );

    // REQUIRED: object table must be set to MyISAM
	static $create_table_options = array(
	    'MySQLDatabase' => 'ENGINE=MyISAM'
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
			$link = $page->Link('?location=').$this->ID;
		}

		return $link;
	}
}
