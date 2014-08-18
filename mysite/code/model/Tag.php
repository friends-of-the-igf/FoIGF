<?php

class Tag extends DataObject{
	
	static $db = array(
		'Title' => 'Varchar',
		'Provenance' => 'Enum("Manual, Crowd, OpenCalais")'
		);

	static $belongs_many_many = array(
		'Sessions' => 'MeetingSession'
		);

	static $summary_fields = array(
		'Title',
		'Provenance'
		);

	public function getCMSField(){
		$fields = new FieldList();

		$fields->add(new TextField('Title', 'Title'));

		return $fields;
	}

	public function Link(){
		$link = "";
		if($page = SessionsHolder::get()->First()) {
			$link = $page->Link('tag');
		}
		return $link . '/' . urlencode($this->ID);
	}


}