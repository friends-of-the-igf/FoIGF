<?php

class SessionController extends Page_Controller {
	
	public static $url_handlers = array(
		'$ID!/$Action' => 'handleAction'
	);

	public static $allowed_actions = array(
        'TagForm',
        'saveTags'
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
			Session::set('CurrentSession', $meetingsession);
			$this->meetingsession = $meetingsession;
		} else {
			if($this->request->param('Action') == 'CustomSearchForm' || $this->request->param('Action') == 'TagForm'){
				return;
			}else{
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

	// public function TagForm(){
	// 	$fields = new FieldList();

	// 	$fields->push($t = new TextField('Tags', 'Add Tags'));
	// 	$t->setAttribute('placeholder', 'Enter tags separated by commas');

	// 	$actions = new FieldList($b = new FormAction('saveTags', 'Save'));
	// 	$b->addExtraClass('btn');
	// 	$b->addExtraClass('btn-primary');

	// 	$form = new Form($this, 'TagForm', $fields, $actions);

	// 	return $form;
	// }

	// public function saveTags($data, $form){
	// 	$meetingsession = Session::get('CurrentSession');
		
	// 	if($data['Tags'] != null && isset($data['Tags'])){
	// 		if($meetingsession->Tags != null){
				
	// 			$meetingsession->Tags .= ',' . $data['Tags'];
	// 		} else {
	// 			$meetingsession->Tags = $data['Tags'];
	// 		}
	// 		$meetingsession->write();
	// 	}
	// 	return $this->redirectBack();

	// }


}
