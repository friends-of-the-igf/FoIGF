<?php
/**
* A page that lists Meetings
*
* @package FoIGF
*/
class MeetingsHolder extends Page {

	public static $db = array(
	);

	public static $has_one = array(
	);

	public function getCMSFields() {
		$fields = parent::getCMSFields();

		$fields->removeFieldFromTab('Root.Main', 'Content');

		return $fields;
	}

}
class MeetingsHolder_Controller extends Page_Controller {

	public static $allowed_actions = array(
		'setFormCookie',
	);

	public function init() {
		parent::init();
	}

	/**
	* Gets list of all meetings sorted by Date. 
	* 
	* @return DataList.
	*/
	public function getMeetings() {
		$meetings = Meeting::get()->Sort('StartDate', 'DESC');
		return $meetings;
	}

}