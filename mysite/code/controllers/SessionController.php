<?php

class SessionController extends Page_Controller {
	
	public static $url_handlers = array(
		'$ID!/$Action' => 'handleAction'
	);

	public static $allowed_actions = array(
        'TagForm',
        'saveTags',
        'getTags'
	);

	protected $meetingsession = null;

	public function init() {
		parent::init();

		Requirements::javascript('themes/igf/javascript/sessioncontroller.js');
		Requirements::javascript('themes/igf/thirdparty/bootstrap-typeahead.js');

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
			if($this->request->param('Action') == 'SearchForm' || $this->request->param('Action') == 'TagForm' || $this->request->param('Action') == 'getTags'){
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

	public function TagForm(){
		$fields = new FieldList();

		$fields->push($t = new TextField('Tags', 'Add Tags'));
		$t->setAttribute('placeholder', 'Enter tags separated by commas');
		$t->setAttribute('class', 'typeahead');
		$t->setAttribute('data-provide', 'typeahead');
		$t->setAttribute('autocomplete', 'off');

		$actions = new FieldList($b = new FormAction('saveTags', 'Save'));
		$b->addExtraClass('btn');
		$b->addExtraClass('btn-primary');

		$form = new Form($this, 'TagForm', $fields, $actions);
		$form->setAttribute('data-url', $this->Link());

		return $form;
	}

	public function saveTags($data, $form){
		$meetingsession = Session::get('CurrentSession');
		if($data['Tags'] != null && isset($data['Tags'])){
			//make an array of the tags currently attached to sesseion		
			$oldTagList = array();	
			$oldTags = preg_split("*,*", trim($meetingsession->Tags));
			foreach($oldTags as $tag) {
				if($tag != "") {
					$tag = strtolower($tag);
					$oldTagList[$tag] = $tag;
				}
			}

			$newTagList = array();	
			$newTags = preg_split("*,*", trim($data['Tags']));
			foreach($newTags as $tag) {
				$tag = trim($tag);
				if($tag != "") {
					$tag = strtolower($tag);
					$newTagList[$tag] = $tag;
				}
			}

			$tagsToAdd = array_diff($newTagList, $oldTagList);
		
			if(!empty($tagsToAdd)){
				foreach($tagsToAdd as $tag){
					
					if($meetingsession->Tags != null){
						$meetingsession->Tags .= ','.$tag;
					} else {
						$meetingsession->Tags = $tag;
					}
					$meetingsession->write();
				}
			}
		}
		return $this->redirectBack();
	}

	public function getTags() {
		$sessions = MeetingSession::get();
		$list = array();	
		foreach($sessions as $session) {
			$tags = preg_split("*,*", trim($session->Tags));
			foreach($tags as $tag) {
				if($tag != "") {
					$tag = strtolower($tag);
					$list[$tag] = $tag;
				}
			}
		}
		return json_encode($list);
	}


}
