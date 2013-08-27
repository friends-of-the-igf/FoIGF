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

	public static $url_handlers = array(
		'$Action/$ID' => 'handleAction'
	);

	public $sessions;

	public $sessionCount;
	public $meetingCount;

	public static $allowed_actions = array (
		'FilterForm',
		'doSearch',
		'getSpeakers',
		'tag'	

	);

	public function init() {
		parent::init();
			
		$this->loadSessions();

		Requirements::javascript('themes/igf/thirdparty/bootstrap-typeahead.js');
		Requirements::javascript('themes/igf/javascript/sessionholder.js');
		
	}

	public function FilterForm(){
		$fields = new FieldList();

	
		

		$fields->push($m = new DropdownField('Meeting', 'Meeting', Meeting::get()->sort('StartDate','DESC')->map('ID', 'getYearLocation')));
		if(isset($_GET['location']) && $_GET['location'] != null){
			$m->setValue(Location::get()->byID($_GET['location'])->Meetings()->First()->ID);
		}
		if(isset($_GET['meeting']) && $_GET['meeting'] != null){
			$m->setValue(Meeting::get()->byID($_GET['meeting'])->ID);
		}
		$m->setEmptyString('-select-');
		$fields->push($t = new DropdownField('Type', 'Session type', Type::get()->map('ID', 'Name')));
		if(isset($_GET['type']) && $_GET['type'] != null){
			$t->setValue($_GET['type']);
		}
		$t->setEmptyString('-select-');
		$fields->push($s = new TextField('Speaker', 'Speaker'));

		$s->setAttribute('placeholder', 'Start typing to select from list');
		$s->setAttribute('autocomplete', 'off');
		$s->setAttribute('data-provide', 'typeahead');
		$s->setAttribute('class', 'typeahead');

		$fields->push($topic = new CheckboxSetField('Topic', 'Topics', Topic::get()->sort('Name', 'ASC')->map('ID', 'Name')));
		if(isset($_GET['topic']) && $_GET['topic'] != null){
			$topic->setValue($_GET['topic']);
		}

		$sortOptions = array(
			'Latest' => 'Latest', 
			'Oldest' => 'Oldest', 
			);

		if(SiteConfig::current_site_config()->ViewCheck){
			$sortOptions['Views'] = 'Most Viewed';
		}

		$fields->push(new OptionSetField('Sort', 'Sort Sessions by', $sortOptions));


		$actions = new FieldList($button = new FormAction('doSearch', 'Refine Results'));
		$button->addExtraClass('btn');
		$button->addExtraClass('btn-primary');

		$form = new Form($this, 'FilterForm', $fields, $actions);
		$form->setAttribute('data-url', $this->Link().'getSpeakers');
	
		if(isset($_POST)){
			$form->loadDataFrom($_POST);
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
			switch($data['Sort']){
				case 'Latest':
					$sort['Field'] = 'Created';
					$sort['Direction'] = 'DESC' ;
					break;
				case 'Oldest':
					$sort['Field'] = 'Created';
					$sort['Direction'] = 'ASC';
					break;
				case 'Views':
					$sort['Field'] = 'Views';
					$sort['Direction'] = 'DESC';
					break;
			}
		}
		
		if(!empty($filter)){		
			if(isset($sort)){
				$sessions = MeetingSession::get()->filter($filter)->leftJoin('MeetingSession_Speakers', 'MeetingSession.ID = MeetingSession_Speakers.MeetingSessionID')->sort($sort['Field'], $sort['Direction']);
			} else {
				$sessions = MeetingSession::get()->filter($filter)->leftJoin('MeetingSession_Speakers', 'MeetingSession.ID = MeetingSession_Speakers.MeetingSessionID')->sort('Created', 'ASC');
			}
		} else {
			if(isset($sort)){
				$sessions = MeetingSession::get()->leftJoin('MeetingSession_Speakers', 'MeetingSession.ID = MeetingSession_Speakers.MeetingSessionID')->sort($sort['Field'], $sort['Direction']);
			} else {
				$sessions = MeetingSession::get()->leftJoin('MeetingSession_Speakers', 'MeetingSession.ID = MeetingSession_Speakers.MeetingSessionID')->sort('Created', 'ASC');
			}
		}
		
		if(!empty($speaker)){	
			$sessions = $sessions->filter($speaker);
		}

		//Do counts
		$this->sessionCount = $sessions->Count();

		foreach($sessions as $sesh){
			if($sesh->Meeting()->ID != 0){
				$meetings[$sesh->Meeting()->ID] = $sesh->Meeting()->ID;
			}
		}

		if(isset($meetings)){
			$this->meetingCount = count($meetings);
		} else {
			$this->meetingCount = 0;
		}

		
		//Paginate
		$sessions = $this->makeColumns($sessions);

			// Session::set('Search', True);

		return $this->customise(array('getSessions' => $sessions));


	}


	public function getSessions(){
		
		$sessions = $this->sessions;
		
		return $this->makeColumns($sessions);
	}

	public function makeColumns($sessions){

		
		$total = $sessions->Count(); 
		$pages = ceil($total/18); 

		$sessionIndex = 0;

		$pageList = new ArrayList();



		for($p = 1; $p <= $pages; $p++){

			$pageLimit = $p*18;
			


			$list = new ArrayList();

			$col1 = new ArrayList();
			$col2 = new ArrayList();
			$col3 = new ArrayList();


			$j = 1;

			while ($sessionIndex < $pageLimit) {
				

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

			$list->push(new ArrayData(array('Column' => $col1)));
			$list->push(new ArrayData(array('Column' => $col2)));
			$list->push(new ArrayData(array('Column' => $col3)));




			$pageList->push(new ArrayData(array('Page' => $list)));	
		}
		return $pageList;

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

	// public function isSearch(){
	// 	if(Session::get('Search')){	
	// 		Session::clear('Search');
	// 		return true;
	// 	} else {
	// 		return false;
	// 	}
	// }

	public function getSpeakers(){
		
		foreach(Member::get() as $member){
			if($member->inGroup('Speakers')){
				$speakers[$member->ID] = $member->Name;
			}
		}
		return json_encode($speakers);
	}

	public function tag(){
        $params = Controller::curr()->getURLParams();
        $tag = $params['ID'];
        $sessions = new ArrayList();
        $fullSessions = MeetingSession::get();
        foreach($fullSessions as $sesh){
        	if(strpos($sesh->Tags, $tag) !== false){
        		$sessions->push($sesh);
        	}
        }
        $sessions = $this->makeColumns($sessions);
        
        return Controller::curr()->customise(array('getSessions' => $sessions));
    }

    public function loadSessions(){

    	$this->sessions = MeetingSession::get();

    	if(isset($_GET['topic']) && $_GET['topic'] != null){
    		$this->sessions = MeetingSession::get()->filter(array('TopicID' => $_GET['topic']));
    	}
    	if(isset($_GET['location']) && $_GET['location'] != null){
			$sessions = new ArrayList();
	        $meetings = Location::get()->byID($_GET['location'])->Meetings();
	        foreach($meetings as $meeting){
	        	foreach($meeting->MeetingSessions() as $session){
	        		$sessions->push($session);
	        	}
	        }
	        $this->sessions = $sessions;
    	}
    	if(isset($_GET['type']) && $_GET['type'] != null){
    		$this->sessions = MeetingSession::get()->filter(array('TypeID' => $_GET['type']));
    	}

    	if(isset($_GET['meeting']) && $_GET['meeting'] != null){
    		$meeting = Meeting::get()->byID($_GET['meeting']);
    		$this->sessions = $meeting->MeetingSessions();
    	}

		$this->sessionCount = $this->sessions->Count();

		foreach($this->sessions as $sesh){
			if($sesh->Meeting()->ID != 0){
				$meetings[$sesh->Meeting()->ID] = $sesh->Meeting()->ID;
			}
		}

		if(isset($meetings)){
			$this->meetingCount = count($meetings);
		} else {
			$this->meetingCount = 0;
		}
    }

}