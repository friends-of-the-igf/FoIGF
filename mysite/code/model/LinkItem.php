<?php
class LinkItem extends DataObject {

	public static $db = array(
		'Title' => 'Text',
		'Url' => 'Text'
	);

	public static $has_one = array(
		'Meeting' => 'Meeting'
	);

 	public function getCMSFields() {
		$fields = new FieldList();
		
		$fields->push(new TextField('Title', 'Title'));
		$fields->push($f = new TextField('Url', 'URL'));
		$f->setAttribute('placeholder', 'http://');
					
		return $fields;
	}

}
