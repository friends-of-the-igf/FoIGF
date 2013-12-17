<?php
/**
* Controller to display Reports belonging to a Meeting Session
*
* @package FoIGF
*/
class ReportController extends Page_Controller {
	
	public static $url_handlers = array(
		'$ID!/$Action' => 'handleAction'
	);

	public static $allowed_actions = array(
	);

	protected $meetingsession = null;

	public function init() {
		parent::init();

		$id = (int)$this->request->param('ID');
		if($meetingsession = MeetingSession::get()->ByID($id)) {
			$this->meetingsession = $meetingsession;
		} else {
			return $this->httpError(404);
		}
	}

	/**
	 * Gets current Meeting Session
	 * 
	 * @return MeetingSession.
	 */
	public function getMeetingSession() {
		return $this->meetingsession;
	}

	/**
	 * Gets title for current Meeting Session
	 * 
	 * @return String.
	 */
	public function getTitle() {
		return $this->meetingsession->Title;
	}

	/**
	 * Returns a class name for the Controller
	 * 
	 * @return String.
	 */
	public function getClassName() {
		return 'TranscriptController';
	}

}