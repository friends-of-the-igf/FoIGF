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

			// view counter
			$config = SiteConfig::current_site_config();
			if($config->ViewCheck) {
				if($viewed_sessions = Session::get('ViewedSessions')) {
					if(!in_array($meetingsession->ID, $viewed_sessions)) {
						$viewed_sessions[] = $meetingsession->ID;
						Session::set('ViewedSessions', $viewed_sessions);
						$meetingsession->Views = $meetingsession->Views + 1;
						$meetingsession->write();
					}
				} else {
					Session::set('ViewedSessions', array($meetingsession->ID));
					$meetingsession->Views = $meetingsession->Views + 1;
					$meetingsession->write();
				}
			}
			
			$this->meetingsession = $meetingsession;
		} else {
			return $this->httpError(404);
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
