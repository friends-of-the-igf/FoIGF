<?php

class SessionController extends Page_Controller {
	
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
			if($this->request->param('Action') != 'CustomSearchForm'){
				return $this->httpError(404);
			}
		}
	}

	public function getMeetingSession() {
		return $this->meetingsession;
	}

	public function getTitle() {
		return $this->meetingsession->Title;
	}

	public function getClassName() {
		return 'SessionController';
	}

}
