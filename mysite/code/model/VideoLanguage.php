<?php
/**
* A Meeting Session Topic to categorise Meeting Sessions
*
* @package FoIGF
*/
class VideoLanguage extends DataObject {

	public static $singular_name = 'Language';

	public static $db = array(
		'Name' => 'Text',
		'SortOrder' => 'Int'
	);
	
	public static $default_sort='SortOrder';

	public static $has_many = array(
		'Videos' => 'Video'
	);

	static $searchable_fields = array(
		'Name'
	);

	// fields to return
	static $return_fields = array(
		'Name',
		'ID'
	);

	// set index
	public static $indexes = array(
		"fulltext (Name)"
    );

    // REQUIRED: object table must be set to MyISAM
	static $create_table_options = array(
	    'MySQLDatabase' => 'ENGINE=MyISAM'
	);

	public function getCMSFields() {
		$fields = new FieldList();
		$fields->push(new TextField('Name', 'Name'));
		return $fields;
	}

}