<?php
/**
* Controller to display Transcript belonging to a Meeting Session
*
* @package FoIGF
*/
class TranscriptController extends Page_Controller {
	
	public static $url_handlers = array(
		'$ID!/$Action' => 'handleAction'
	);

	public static $allowed_actions = array(
	);

	protected $meetingsession = null;
	protected $transcript = null;

	public function init() {
		parent::init();

		$id = (int)$this->request->param('ID');
		if($transcript = SessionTranscript::get()->ByID($id)) {
			$this->transcript = $transcript;
			$this->meetingsession = $transcript->MeetingSession();
		} else {
			return $this->httpError(404);
		}
		
	}

	/**
	 * Gets current MeetingSession
	 * 
	 * @return MeetingSession.
	 */
	public function getMeetingSession() {
		return $this->meetingsession;
	}

	/**
	 * Gets current MeetingSession Transcript
	 * 
	 * @return SessionTranscript.
	 */
	public function getTranscript(){
		return $this->transcript;
	}

	/**
	 * Gets current MeetingSession title
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

	public function otherLanguages(){
		$session = $this->meetingsession;
		$transcripts = $session->Transcripts()->exclude(array('ID' => $this->transcript->ID));
		return $transcripts;
	}

}
