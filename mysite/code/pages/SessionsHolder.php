<?php
class SessionsHolder extends Page {

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
class SessionsHolder_Controller extends Page_Controller {

	public $sessions;

	public $sessionCount;
	public $meetingCount;

	public static $allowed_actions = array (
		'FilterForm',
		'doSearch',
		'getSpeakers'
	);

	public function init() {
		parent::init();
			
		$this->sessions = MeetingSession::get();

		Requirements::javascript('themes/igf/thirdparty/bootstrap-typeahead.js');
		Requirements::javascript('themes/igf/javascript/sessionholder.js');
		
	}

	public function FilterForm(){
		$fields = new FieldList();

		$fields->push($m = new DropdownField('Meeting', 'Meeting', Meeting::get()->map('ID', 'getYearLocation')));
		$m->setEmptyString('-select-');
		$fields->push($t = new DropdownField('Type', 'Session type', Type::get()->map('ID', 'Name')));
		$t->setEmptyString('-select-');
		$fields->push($s = new TextField('Speaker', 'Speaker'));
		$s->setAttribute('autocomplete', 'off');
		$s->setAttribute('data-provide', 'typeahead');
		$s->setAttribute('class', 'typeahead');

		$fields->push(new CheckboxSetField('Topic', 'Sessions on there topics', Topic::get()->map('ID', 'Name')));
		$fields->push(new OptionSetField('Sort', 'Sort Sessions by', array('Latest' => 'Latest', 'Oldest' => 'Oldest', 'Most viewed' => 'Most viewed', 'Most shared' => 'Most shared')));


		$actions = new FieldList($button = new FormAction('doSearch', 'Refine Results'));
		$button->addExtraClass('btn');
		$button->addExtraClass('btn-primary');

		$form = new Form($this, 'FilterForm', $fields, $actions);
		$dat = Session::get('Form');
		if(isset($dat)){
			$form->loadDataFrom($dat);
			Session::clear('Form');
		}

		return $form;
	}

	public function doSearch($data, $form){

		
		$filter = array();
		$speaker = array();

		if(isset($data['Meeting']) && $data['Meeting'] != null){
				$filter['MeetingID'] = $data['Meeting'];
		}
		if(isset($data['Type']) && $data['Type'] != null){
				$filter['TypeID'] = $data['Type'];		
		}
		if(isset($data['Topic']) && $data['Topic'] != null){
			$filter['TopicID'] = $data['Topic'];
		}
		if(isset($data['Speaker']) && $data['Speaker'] != null){
			foreach(Member::get() as $member){
				if($member->Name == $data['Speaker']){
					$speaker['MemberID'] = $member->ID;
				} else {
					$speaker['MemberID'] = 0;
				}
			}
		}
		if(isset($data['Sort']) && $data['Sort'] != null){
			$sort = $data['Sort'];
		}
		

		if(!empty($filter)){		
			if(isset($sort)){
				$sessions = MeetingSession::get()->filter($filter)->leftJoin('MeetingSession_Speakers', 'MeetingSession.ID = MeetingSession_Speakers.MeetingSessionID')->sort($sort, 'DESC');
			} else {
				$sessions = MeetingSession::get()->filter($filter)->leftJoin('MeetingSession_Speakers', 'MeetingSession.ID = MeetingSession_Speakers.MeetingSessionID')->sort('Created', 'DESC');
			}
		} else {
			$sessions = MeetingSession::get()->leftJoin('MeetingSession_Speakers', 'MeetingSession.ID = MeetingSession_Speakers.MeetingSessionID')->sort('Created', 'DESC');
		}
		
		if(!empty($speaker)){	
			$sessions = $sessions->filter($speaker);
		}

		//Do counts
		$this->sessionCount = $sessions->Count();

		foreach($sessions as $sesh){
			$meetings[$sesh->Meeting()->ID] = $sesh->Meeting()->ID;
		}

		if(isset($meetings)){
			$this->meetingCount = count($meetings);
		} else {
			$this->meetingCount = 0;
		}

		
		//Paginate
		$sessions = $this->makeColumns($sessions);

			Session::set('Search', True);

		return $this->customise(array('getSessions' => $sessions));


	}


	public function getSessions(){
		
		$sessions = $this->sessions;
		
		return $this->makeColumns($sessions);
	}

	public function makeColumns($sessions){

		
		// $total = $sessions->Count();
		// $pages = ceil($total/18);
		// $pagesList = new ArrayList();
		// $pageIndex = 1;
		// $sessionIndex = 0;


		$list = new ArrayList();

		$col1 = new ArrayList();
		$col2 = new ArrayList();
		$col3 = new ArrayList();

		$sessionIndex = 0;
		$j = 1;

		while ($sessionIndex <= 17) {
			

			$session = $sessions->limit(1, $sessionIndex)->first();
			
			if($session) {
				switch ($j) {
					case 1:
						$col1->push($session);
						$j++;
						break;
					case 2:
						$col2->push($session);
						$j++;
						break;
					case 3:
						$col3->push($session);
						$j=1;
						break;	
				}
			}
			$sessionIndex++;
		}

		$list->push(new ArrayData(array('Columns' => $col1)));
		$list->push(new ArrayData(array('Columns' => $col2)));
		$list->push(new ArrayData(array('Columns' => $col3)));


		return $list;	

	}

	public function getCount(){
		$data = array(
			'Sessions' => $this->sessionCount,
			'Meetings' => $this->meetingCount
			);

		return new ArrayData($data);
	}

	public function hasSessions(){
		return $this->sessions->Count() > 0;
	}

	public function isSearch(){
		if(Session::get('Search')){	
			Session::clear('Search');
			return true;
		} else {
			return false;
		}
	}

	public function getSpeakers(){
		
		foreach(Member::get() as $member){
			if($member->inGroup('Speakers')){
				$speakers[$member->ID] = $member->Name;
			}
		}
		return json_encode($speakers);
	}

}