<?php

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
			if($this->request->param('Action') != 'CustomSearchForm'){
				return $this->httpError(404);
			}
		}
	}

	public function getMeeting() {
		return $this->meeting;
	}

	public function getTitle() {
		return $this->meeting->Title;
	}

	public function getClassName() {
		return 'MeetingController';
	}

	

}
