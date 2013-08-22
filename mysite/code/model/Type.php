<?php
class Type extends DataObject {

	public static $db = array(
		'Name' => 'Text'
	);

	public static $has_many = array(
		'MeetingSessions' => 'MeetingSession'
	);

	public function getCMSFields() {
		$fields = new FieldList();
		$fields->push(new TextField('Name', 'Name'));
		return $fields;
	}

	public function Link(){
		$link = "";
		if($page = SessionsHolder::get()->First()) {
			$link = $page->Link('type/').$this->ID;
		}

		return $link;
	}

}
