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

		$fields->push($topic = new CheckboxSetField('Topic', 'by Topic', Topic::get()->sort('Name', 'ASC')->map('ID', 'Name')));
		if(isset($_GET['topic']) && $_GET['topic'] != null){
			$topic->setValue($_GET['topic']);
		}
		

		$fields->push($m = new DropdownField('Meeting', 'by Meeting', Meeting::get()->sort('StartDate','DESC')->map('ID', 'getYearLocation')));
		if(isset($_GET['location']) && $_GET['location'] != null){
			$m->setValue(Location::get()->byID($_GET['location'])->Meetings()->First()->ID);
		} 
		if(isset($_GET['meeting']) && $_GET['meeting'] != null){
			$m->setValue(Meeting::get()->byID($_GET['meeting'])->ID);
		}
		
		$m->setEmptyString('-select-');

		$fields->push($t = new DropdownField('Type', 'by Type', Type::get()->map('ID', 'Name')));
		if(isset($_GET['type']) && $_GET['type'] != null){
			$t->setValue($_GET['type']);
		}
		
		$t->setEmptyString('-select-');


		$fields->push($d = new DropdownField('Day', 'by Day', array(
			'0' => 'Day 0',
			'1' => 'Day 1',
			'2' => 'Day 2',
			'3' => 'Day 3',
			'4' => 'Day 4'
			)));
		if(isset($_GET['day']) && $_GET['day'] != null){
			$d->setValue($_GET['day']);
		}
		
		$d->setEmptyString('-select-');

		


		
		$fields->push($s = new TextField('Speaker', 'by Speaker'));
		if(isset($_GET['speaker']) && $_GET['speaker'] != null){
			$s->setValue(Member::get()->byID($_GET['speaker'])->Name);
		}
		
		$s->setAttribute('placeholder', 'Start typing...');
		$s->setAttribute('autocomplete', 'off');
		$s->setAttribute('data-provide', 'typeahead');
		$s->setAttribute('class', 'typeahead');


		$sortOptions = array(
			'Latest' => 'Latest', 
			'Oldest' => 'Oldest', 
			);

		if(SiteConfig::current_site_config()->ViewCheck){
			$sortOptions['Views'] = 'Most Viewed';
		}

		$fields->push($sor = new OptionSetField('Sort', 'Sort Sessions by', $sortOptions));
		if(isset($_GET['sort']) && $_GET['sort'] != null){
			$field = $_GET['sort']['Field'];
			if($field == 'Views'){
				$sor->setValue('Views');
			} else if($field == 'Created'){
				if($_GET['sort']['Direction'] == 'ASC'){
					$sor->setValue('Oldest');
				} else {
					$sor->setValue('Latest');
				}
			}

		}
		if(isset($this->filters['Tag'])){
			$fields->push(new HiddenField('CurrentTag', 'CurrentTag', $this->filters['Tag']));
		}
		$url = $this->curPageURL();
	    $url_elements = parse_url($url);
	    if(isset($url_elements['query'])){
		    $query = $url_elements['query'];
			parse_str($query, $query_elements);
			if (isset($query_elements['tag'])){
				$fields->push(new HiddenField('CurrentTag', 'CurrentTag', $query_elements['tag']));
			}
		}
		if(isset($this->filters['Post']['CurrentTag'])){
			$fields->push(new HiddenField('CurrentTag', 'CurrentTag', $this->filters['Post']['CurrentTag']));
		}



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
		if(isset($data['Day']) && $data['Day'] != null){
				$filter['Day'] = $data['Day'];		
		}
		if(isset($data['Topic']) && $data['Topic'] != null){
			$filter['TopicID'] = $data['Topic'];
		}
		if(isset($data['CurrentTag']) && $data['CurrentTag'] != null){
			$filterList['CurrentTag'] = $data['CurrentTag'];
		}

		if(count($filter > 0)){
			$filterList['Filter'] = $filter;
		}
		if(isset($data['Speaker']) && $data['Speaker'] != null){
			foreach(Member::get() as $member){
				if($member->Name == $data['Speaker']){
					$speaker['MemberID'] = $member->ID;
				} 
			}
			if(!empty($speaker)){
				$filterList['Speaker'] = $speaker;
			}else{
				$speaker['MemberID'] = 0;
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
		//Location
    	if(isset($_GET['location']) && $_GET['location'] != null){
			$sessions = new ArrayList();
	        $meetings = Location::get()->byID($_GET['location'])->Meetings();
	        foreach($meetings as $meeting){
	        	foreach($meeting->MeetingSessions() as $session){
	        		$sessions->push($session);
	        	}
	        }
    	}

		//Topic
    	if(isset($_GET['topic']) && $_GET['topic'] != null){
    		$getFilter['TopicID'] = $_GET['topic'];
    	}

    	//Type
    	if(isset($_GET['type']) && $_GET['type'] != null){
    		$getFilter['TypeID'] = $_GET['type'];   		
    	}

    	//Meeting
    	if(isset($_GET['meeting']) && $_GET['meeting'] != null){
    		$getFilter['MeetingID'] = $_GET['meeting'];	
    	}

    	//Day
    	if(isset($_GET['day']) && $_GET['day'] != null){
    		$getFilter['Day'] = $_GET['day'];
    	}


    	if(isset($_GET['speaker']) && $_GET['speaker'] != null){
    		$speaker['MemberID'] = $_GET['speaker'];
    	}

		//Sort
    	if(isset($_GET['sort']) && $_GET['sort'] != null){
    		$sort = $_GET['sort'];
    	}

    	if(!empty($getFilter)){		
				if(isset($sort)){
					$sessions = MeetingSession::get()->filter($getFilter)->leftJoin('MeetingSession_Speakers', 'MeetingSession.ID = MeetingSession_Speakers.MeetingSessionID')->sort($sort['Field'], $sort['Direction']);
				} else {
					$sessions = MeetingSession::get()->filter($getFilter)->leftJoin('MeetingSession_Speakers', 'MeetingSession.ID = MeetingSession_Speakers.MeetingSessionID')->sort('Created', 'ASC');
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

		if((isset($_GET['tag']) && $_GET['tag'] != null)){
			$tag = $_GET['tag'];
			$list = new ArrayList();
			foreach ($sessions as $session) {
				if(strpos($session->Tags, $tag) !== false){
		    		$list->push($session);
		    	}
			}
			$sessions = $list;
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
			//Post Tags
			if(array_key_exists('CurrentTag', $postFilters)){
				$list = new ArrayList();
				$tag = $postFilters['CurrentTag'];
				foreach ($sessions as $session) {
					if(strpos($session->Tags, $tag) !== false){
			    		$list->push($session);
			    	}
				}
				$sessions = $list;
			}

		} 

		
		//--- TAG FILTER ----//
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
		
		//-------PAGINATION------//

    	if(isset($_GET['page']) && $_GET['page'] != null){
    		$page = $_GET['page'];
    		$offset = $page*18;
    	}

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

	public function getTopics(){
		return Topic::get()->sort('Name', 'ASC');
	}

	public function tag(){
        $params = Controller::curr()->getURLParams();
        $tag = $params['ID'];

        $this->filters['Tag'] = $tag;

		$sessions = $this->getSessions($this->filters);
        
        return $this->customise(array('getSessions' => $sessions));
    }


    public function PageCount(){
    	return $this->pages;
    }

    public function nextPage(){
    	
    	if($this->sessionCount > 18){
	    	$url = $this->curPageURL();
	    	$url_elements = parse_url($url);
	    	if(isset($url_elements['path'])){
	    		$path_elements = explode('/', $url_elements['path']);
	    		$element_count = count($path_elements);
	    		$last_index = $element_count - 1;
	    		if($path_elements[$last_index] == 'FilterForm'){
	    			unset($path_elements[$last_index]);
	    		}
	    		$second_last_index = $element_count - 2;
	    		if($path_elements[$second_last_index] == 'tag'){
	    			unset($path_elements[$second_last_index]);
	    			unset($path_elements[$last_index]);
	    		}
	    		$url_elements['path'] = implode('/', $path_elements);
	    	}
	    	if(isset($url_elements['query'])){
		    	$query = $url_elements['query'];
		    	parse_str($query, $query_elements);
		    	if(isset($query_elements['page'])){
		    		$baseZero_pageCount = $this->pages - 1;
		    		if($query_elements['page'] >= $baseZero_pageCount){
		    			return false;
		    		} else {
		    			$query_elements['page']++;
		    		}	
			    } else {
			    	$query_elements['page'] = 1;
			    }
			    $newQuery = http_build_query($query_elements);    
			} else {
				if(isset($this->filters['Post'])){
					$post_filters = $this->filters['Post'];
				    if(isset($post_filters['Filter'])){
				    	$top_filter = $post_filters['Filter'];
					    if(isset($top_filter['MeetingID']) && $top_filter['MeetingID'] != null){
	 						$query_elements['meeting'] = $top_filter['MeetingID'];
					    }
					    if(isset($top_filter['TypeID']) && $top_filter['TypeID'] != null){
					    	$query_elements['type'] = $top_filter['TypeID'];
					    }
					    if(isset($top_filter['Day']) && $top_filter['Day'] != null){
					    	$query_elements['day'] = $top_filter['Day'];
					    }
					    if(isset($top_filter['TopicID']) && $top_filter['TopicID'] != null){
					    	$query_elements['topic'] = $top_filter['TopicID'];
					    }
				    }
				    if(isset($post_filters['Speaker'])){
				    	$speak_filter = $post_filters['Speaker'];
				    	if(isset($speak_filter['MemberID']) && $speak_filter['MemberID'] != null){
					    	$query_elements['speaker'] = $speak_filter['MemberID'];
					    }
				    }
				    if(isset($post_filters['Sort'])){
				    	$sort_filter = $post_filters['Sort'];
				    	if(isset($sort_filter['Field']) && isset($sort_filter['Direction'])){
					    	$query_elements['sort'] = $post_filters['Sort'];
					    }
				    }
				     if(isset($post_filters['CurrentTag'])){
				    	$query_elements['tag'] = $post_filters['CurrentTag'];
				    }
				}
				if(isset($this->filters['Tag'])){
					$query_elements['tag'] = $this->filters['Tag'];
				}
				$query_elements['page'] = 1;
			}
			$newQuery = http_build_query($query_elements);
			
			$url_elements['query'] = $newQuery;	
			$newUrl = $url_elements['scheme'].'://'.$url_elements['host'].$url_elements['path'].'?'.$url_elements['query'];
	    	return $newUrl;
	    } else {
	    	return false;
	    }
    }

    public function previousPage(){
    	$url = $this->curPageURL();
    	$url_elements = parse_url($url);
    	if(isset($url_elements['query'])){
	    	$query = $url_elements['query'];
	    	parse_str($query, $query_elements);
	    	if(isset($query_elements['page'])){
	    		if($query_elements['page'] > 0){
	    			$query_elements['page']--;
	    		} else{
	    			return false;
	    		}
		    } else {
		    	return false;
		    }
		    $newQuery = http_build_query($query_elements);
		} else {
			return false;
		}
		$newQuery = http_build_query($query_elements);
		$url_elements['query'] = $newQuery;	
		$newUrl = $url_elements['scheme'].'://'.$url_elements['host'].$url_elements['path'].'?'.$url_elements['query'];
    	return $newUrl;
    }


     static function curPageURL() { 
      $pageURL = 'http'; 
      if (Director::protocol() == 'https') {$pageURL .= "s";} 
      $pageURL .= "://"; 
      if ($_SERVER["SERVER_PORT"] != "80") { 
         $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
      } else { 
         $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]; 
      } 
      return $pageURL; 
   }

  

}