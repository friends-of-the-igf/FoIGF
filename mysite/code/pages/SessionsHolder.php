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

	public $filters;

	public $sessionCount;
	public $meetingCount;
	public $pages;

	public static $allowed_actions = array (
		'FilterForm',
		'doSearch',
		'getSpeakers',
		'tag',
		'changePage'	

	);

	public function init() {
		parent::init();

		$this->getSessions();
	
	
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
		$form->setAttribute('data-url', $this->Link());
	
		if(isset($_POST)){
			$form->loadDataFrom($_POST);
		}
		
		return $form;
	}

	public function doSearch($data, $form){


		$filter = array();
		$filterList = array();
		$speaker = array();
		$sort = array();

		if(isset($data['Meeting']) && $data['Meeting'] != null){
				$filter['MeetingID'] = $data['Meeting'];
		}
		if(isset($data['Type']) && $data['Type'] != null){
				$filter['TypeID'] = $data['Type'];		
		}
		if(isset($data['Topic']) && $data['Topic'] != null){
				$filter['TopicID'] = $data['Topic'];
		}
		if(count($filter > 0)){
			$filterList['Filter'] = $filter;
		}
		if(isset($data['Speaker']) && $data['Speaker'] != null){
			foreach(Member::get() as $member){
				if($member->Name == $data['Speaker']){
					$speaker['MemberID'] = $member->ID;
				} else {
					$speaker['MemberID'] = 0;
				}
			}
			if(count($speaker > 0)){
				$filterList['Speaker'] = $speaker;
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
			if(count($sort > 0)){
				$filterList['Sort'] = $sort;
			}		
		}

		$this->filters['Post'] = $filterList;

		return Controller::curr()->customise(array('getSessions' => $this->getSessions($this->filters)));

	}


	public function getSessions($filters = null, $offset = null, $tag = null){
		

		$sessions = MeetingSession::get();

		//------GET FILTERS------//

		if(!empty($filters) && array_key_exists('Get', $filters)){
			
			$getFilter = $filters['Get'];

			if(array_key_exists('Topic', $getFilter)){
	    		$sessions = MeetingSession::get()->filter(array('TopicID' => $getFilter['Topic']));
	    	}
	    	if(array_key_exists('Location', $getFilter)){
				$sessions = new ArrayList();
		        $meetings = Location::get()->byID($getFilter['Location'])->Meetings();
		        foreach($meetings as $meeting){
		        	foreach($meeting->MeetingSessions() as $session){
		        		$sessions->push($session);
		        	}
		        }
	    	}
	    	if(array_key_exists('Type', $getFilter)){
	    		$sessions = MeetingSession::get()->filter(array('TypeID' => $getFilter['Type']));
	    	}

	    	if(array_key_exists('Meeting', $getFilter)){
	    		$meeting = Meeting::get()->byID($getFilter['Meeting']);
	    		$sessions = $meeting->MeetingSessions();
	    	}


    	} else {

			$getFilter = array();

	    	if(isset($_GET['topic']) && $_GET['topic'] != null){
	    		$getFilter['Topic'] = $_GET['topic'];
	    		$sessions = MeetingSession::get()->filter(array('TopicID' => $_GET['topic']));
	    	}
	    	if(isset($_GET['location']) && $_GET['location'] != null){
	    		$getFilter['Location'] = $_GET['location'];
				$sessions = new ArrayList();
		        $meetings = Location::get()->byID($_GET['location'])->Meetings();
		        foreach($meetings as $meeting){
		        	foreach($meeting->MeetingSessions() as $session){
		        		$sessions->push($session);
		        	}
		        }
	    	}
	    	if(isset($_GET['type']) && $_GET['type'] != null){
	    		$getFilter['Type'] = $_GET['type'];
	    		$sessions = MeetingSession::get()->filter(array('TypeID' => $_GET['type']));

	    	}

	    	if(isset($_GET['meeting']) && $_GET['meeting'] != null){
	    		$getFilter['Meeting'] = $_GET['meeting'];
	    		$meeting = Meeting::get()->byID($_GET['meeting']);
	    		$sessions = $meeting->MeetingSessions();
	    	}

	    	if(!empty($getFilter)){
	    		$this->filters['Get'] = $getFilter;
	    	}
	    }

    	

    	//------POST FILTERS------//
    	
    	if(!empty($filters) && array_key_exists('Post', $filters)){
    	
    		
    		$postFilters = $filters['Post'];

    		if(array_key_exists('Filter', $postFilters)){
    			$filter = $postFilters['Filter'];
    		}
    		if(array_key_exists('Speaker', $postFilters)){
	    		$speaker = $postFilters['Speaker'];
	    	}
    		if(array_key_exists('Sort', $postFilters)){
	    		$sort = $postFilters['Sort'];
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
		} 

		//------TAG FILTER--------//

		if(!empty($filters) && array_key_exists('Tag', $filters)){
			$tag = $filters['Tag'];
			$sessions = new ArrayList();
        	$fullSessions = MeetingSession::get();
       		foreach($fullSessions as $sesh){
		    	if(strpos($sesh->Tags, $tag) !== false){
		    		$sessions->push($sesh);
		    	}
	        }
		}

		//------COUNTS-------//

		$this->sessionCount = $sessions->Count();
		$this->pages = ceil($this->sessionCount/18);

		foreach($sessions as $session){
			if($session->MeetingID != null && $session->MeetingID != 0){
				$meetingArray[$session->MeetingID] = $session->MeetingID;
			}
		}

		if(isset($meetingArray)){
			$this->meetingCount = count($meetingArray);
		} else {
			$this->meetingCount = 0;
		}
		
		//-------FORMATING------//

		if($offset != null){
			$sessions = $sessions->limit(18, $offset);

			return $this->makeColumns($sessions, $offset);
		} else {

			$sessions = $sessions->limit(18);
			return $this->makeColumns($sessions, 0);
		}
		
	}

	public function makeColumns($sessions, $index){

		if(get_class($sessions) == 'ArrayList'){
			$sessionIndex = 0;
		} else {
			$sessionIndex = $index;
		}
		
		
		$limit = $sessionIndex+17;

		$list = new ArrayList();

		$col1 = new ArrayList();
		$col2 = new ArrayList();
		$col3 = new ArrayList();


		$j = 1;

		while ($sessionIndex <= $limit) {
			
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

		return $list;

	}

	public function getCount(){
		$data = array(
			'Sessions' => $this->sessionCount,
			'Meetings' => $this->meetingCount
			);

		return new ArrayData($data);
	}



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

        $this->filters['Tag'] = $tag;

		$sessions = $this->getSessions($this->filters);
        
        return $this->customise(array('getSessions' => $sessions));
    }

    public function changePage(){
    	$page = $_REQUEST['pager'];
    	if(array_key_exists('filter', $_REQUEST)){
    		$filter = $_REQUEST['filter'];
    	} else {
    		$filter = null;
    	}

    	$offset = $page*18;
       	
    	$sessions = $this->getSessions($filter, $offset);
    	
    	$sessionData =  new ArrayData(array("Sessions" => $sessions));
    	return $sessionData->renderWith('SessionFilterPage');
    }

    public function PageCount(){
    	return $this->pages;
    }

    public function getFilter(){
    	return json_encode($this->filters);
    } 

}