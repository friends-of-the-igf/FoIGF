<?php
/**
* Controller to display Meeting objects
*
* @package FoIGF
*/
class MeetingController extends Page_Controller {
	
	public static $url_handlers = array(
		'$ID!/$Action' => 'handleAction'
	);

	public static $allowed_actions = array(
	);

	protected $meeting = null;

	public function init() {
		parent::init();

		$id = (int)$this->request->param('ID');
		if($meeting = Meeting::get()->ByID($id)) {
			$this->meeting = $meeting;
		} else {
			if($this->request->param('Action') != 'SearchForm'){
				return $this->httpError(404);
			}
		}

		Requirements::javascript('themes/igf/javascript/meetingcontroller.js');
	}

	/**
	 * Gets current Meeting
	 * 
	 * @return Meeting.
	 */
	public function getMeeting() {
		return $this->meeting;
	}

	/**
	 * Gets title for current Meeting.
	 * 
	 * @return String.
	 */
	public function getTitle() {
		return $this->meeting->Title;
	}

	/**
	 * Returns a class name for the Controller
	 * 
	 * @return String.
	 */
	public function getClassName() {
		return 'MeetingController';
	}

	

}
