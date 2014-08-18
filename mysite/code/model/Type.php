<?php
/**
* A Meeting Session Type to categorise Meeting Sessions
*
* @package FoIGF
*/
class Type extends DataObject {

	public static $db = array(
		'Name' => 'Text',
		'SortOrder' => 'Int'
	);

	public static $default_sort='SortOrder';

	public static $has_many = array(
		'MeetingSessions' => 'MeetingSession'
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

	/**
	 * Gets a link to the Session holder page with the type id as a parameter in the query string. 
	 * 
	 * @return String.
	 */
	public function Link(){
		$link = "";
		if($page = SessionsHolder::get()->First()) {
			$link = $page->Link('?Type=').$this->ID;
		}

		return $link;
	}

}
