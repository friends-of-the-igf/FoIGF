<?php

class CustomSearchFilter extends DataObject {
	static $db = array(
		'Title' => 'Text',
		'SearchClass' => 'Text'
	);

	static $has_one = array(
		'SearchPage' => 'SiteTree'
	);

	static $summary_fields = array(
		'Title',
		'SearchPage.Title'
	);

	static $dataobjects_to_search = array();

	function getCMSFields() {
		$fields = parent::getCMSFields();

		$fields->push(new TextField('Title', 'Filter Title'));
		$fields->push(new TreeDropdownField('SearchPageID', 'Page to use as Search Category', 'SiteTree'));

		if(count($this->get_search_objects()) != 0) {
			$fields->push(new LiteralField('break', '<p>or</p>'));

			$classes = null;
			foreach($this->get_search_objects() as $class) {
				$classes[$class] = $class;
			}
			$fields->push(new DropdownField('SearchClass', 'Class to use as a Search Category', $classes, null, null, '- Please Set -'));
		}

		return $fields;
	}

	public static function set_search_objects($objects = array()) {
		self::$dataobjects_to_search = $objects;
	}

	public static function get_search_objects() {
		return self::$dataobjects_to_search;
	}
}