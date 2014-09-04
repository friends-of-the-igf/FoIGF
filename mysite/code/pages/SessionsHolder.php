<?php
/**
* A page which displays and filters Meetings Sessions
*
* @package FoIGF
*/
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
		'changePage',
		'setFormCookie'	

	);

	public function init() {
		parent::init();
	
		Requirements::javascript('themes/igf/thirdparty/bootstrap-typeahead.js');
		Requirements::javascript('themes/igf/javascript/sessionholder.js');
		
		
	}

	/**
	* Gets a Filter form for Meeting Sessions. 
	* 
	* @return Form.
	*/
	public function FilterForm(){
		$fields = new FieldList();

		$fields->push($topic = new CheckboxSetField('Topic', 'by Topic', Topic::get()->sort('Name', 'ASC')->map('ID', 'Name')));
		if(isset($_GET['Topic']) && $_GET['Topic'] != null){
			$topic->setValue($_GET['Topic']);
		}
		

		$fields->push($m = new DropdownField('Meeting', 'by Meeting', Meeting::get()->sort('StartDate','DESC')->map('ID', 'getYearLocation')));
		if(isset($_GET['Location']) && $_GET['Location'] != null){
			$m->setValue(Location::get()->byID($_GET['Location'])->Meetings()->First()->ID);
		} 
		if(isset($_GET['Meeting']) && $_GET['Meeting'] != null){
			$m->setValue(Meeting::get()->byID($_GET['Meeting'])->ID);
		}
		
		$m->setEmptyString('-select-');

		$fields->push($t = new DropdownField('Type', 'by Type', Type::get()->map('ID', 'Name')));
		if(isset($_GET['Type']) && $_GET['Type'] != null){
			$t->setValue($_GET['Type']);
		}
		
		$t->setEmptyString('-select-');


		$fields->push($d = new DropdownField('Day', 'by Day', array(
			'0' => 'Day 0',
			'1' => 'Day 1',
			'2' => 'Day 2',
			'3' => 'Day 3',
			'4' => 'Day 4'
			)));
		if(isset($_GET['Day']) && $_GET['Day'] != null){
			$d->setValue($_GET['Day']);
		}
		
		$d->setEmptyString('-select-');

		$fields->push($s = new TextField('Speaker', 'by Speaker'));
		if(isset($_GET['Speaker']) && $_GET['Speaker'] != null){
			$s->setValue(Member::get()->byID($_GET['Speaker'])->Name);
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
		if(isset($_GET['Sort']) && $_GET['Sort'] != null){
			$field = $_GET['Sort']['Field'];
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
			if (isset($query_elements['Tag'])){
				$fields->push(new HiddenField('CurrentTag', 'CurrentTag', $query_elements['Tag']));
			}
		}

		if(isset($this->filters['Post']['CurrentTag'])){
			$fields->push(new HiddenField('CurrentTag', 'CurrentTag', $this->filters['Post']['CurrentTag']));
		}
		if(isset($_GET['CurrentTag'])){
			$fields->push(new HiddenField('CurrentTag', 'CurrentTag', $_GET['CurrentTag']));
		}

		if(isset($_GET['Search'])){
			$fields->push(new HiddenField('Keyword', 'Keyword', $_GET['Search']));
		} elseif (isset($_POST['Keyword'])){
			$fields->push(new HiddenField('Keyword', 'Keyword', $_POST['Keyword']));
		}



		$actions = new FieldList($button = new FormAction('doSearch', 'Filter Results'), $reset = new ResetFormAction('clear', 'Clear Filter'));
		$button->addExtraClass('btn');
		$button->addExtraClass('btn-primary');

		$reset->addExtraClass('btn');
		$reset->addExtraClass('btn-sm');

		$form = new Form($this, 'FilterForm', $fields, $actions);
		$form->setAttribute('data-url', $this->Link());
	
		if(isset($_POST)){
			$form->loadDataFrom($_POST);
		}
		
		return $form;
	}

	/**
	* Performs a filter operation before redirecting back to the page with the results. 
	* @param $data An Array of form data
	* @param $form The form object
	*
	* @return SessionHolder
	*/
	public function doSearch($data, $form){

		$sub = new FilterSubmission();
		$sub->absorb($data);

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

		/*---Keyword---*/
		if(isset($data['Keyword'])){
			$this->filters['Keyword'] = $data['Keyword'];
		}

		//We need to set them in the session, so we can use them in the Search Form. 
		Session::set('Filters', $this->filters);
		
		return Controller::curr()->customise(array('getSessions' => $this->getSessions($this->filters)));

	}

	/**
	* Performs a filter operation before redirecting back to the page with the results. 
	* @param $filters An array of filters GET, POST and Tags
	* @param $offset An offset for the returned list of Meeting Sessions
	* @param $tag The current tag being filtered on
	*
	* @return ArrayList or DataList
	*/
	public function getSessions($filters = null, $offset = null, $tag = null){

		$keywords = isset($_GET['Search']) ? $_GET['Search'] : false;

		if(!$keywords){
			$keywords = (isset($filters['Keyword'])) ? $filters['Keyword'] : false;
		}
		
		
		if($keywords){
			$query = new SearchQuery();
			$query->search($keywords);

			$query->start(0);
			$query->limit(9999);

			$results = singleton('MyIndex')->search($query);

			$matches = $results->Matches;
			
			$list = $matches->getList()->filter('ClassName', 'MeetingSession');
			$idMatch = $list->map();
		} else {
			$idMatch = array();
		}
		
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
    	if(isset($_GET['Topic']) && $_GET['Topic'] != null){
    		$getFilter['TopicID'] = $_GET['Topic'];
    	}

    	//Type
    	if(isset($_GET['Type']) && $_GET['Type'] != null){
    		$getFilter['TypeID'] = $_GET['Type'];   		
    	}

    	//Meeting
    	if(isset($_GET['Meeting']) && $_GET['Meeting'] != null){
    		$getFilter['MeetingID'] = $_GET['Meeting'];	
    	}

    	//Day
    	if(isset($_GET['Day']) && $_GET['Day'] != null){
    		$getFilter['Day'] = $_GET['Day'];
    	}

    	//Speaker
    	if(isset($_GET['Speaker']) && $_GET['Speaker'] != null){
    		$speaker['MemberID'] = $_GET['Speaker'];
    	}

		//Sort
    	if(isset($_GET['Sort']) && $_GET['Sort'] != null){
    		$sort = $_GET['Sort'];
    	}

    	//If sort not set make default
    	if(!isset($sort)){
    		$sort['Field'] = 'Created';
    		$sort['Direction'] = 'ASC';
    	}
    	
    	if(!empty($speaker)){	
    	//If speaker isn't empty join on speaker table, filter if there is a filter, filter speakers. 
    		if(!empty($getFilter)){
    			$sessions = MeetingSession::get()->filter($getFilter)->leftJoin('MeetingSession_Speakers', 'MeetingSession.ID = MeetingSession_Speakers.MeetingSessionID')->sort($sort['Field'], $sort['Direction']);
    		}else{
    			$sessions = MeetingSession::get()->leftJoin('MeetingSession_Speakers', 'MeetingSession.ID = MeetingSession_Speakers.MeetingSessionID')->sort($sort['Field'], $sort['Direction']);
    		}
    		$sessions = $sessions->filter($speaker);
    	} else {
    	//If speaker is empty, do not join for filter
    		if(!empty($getFilter)){
    			$sessions = MeetingSession::get()->filter($getFilter)->sort($sort['Field'], $sort['Direction']);
    		}else{
    			$sessions = MeetingSession::get()->sort($sort['Field'], $sort['Direction']);
    		}
    	}

		if((isset($_GET['Tag']) && $_GET['Tag'] != null)){
			$tagID = $_GET['Tag'];
			$tag = Tag::get()->byID($tagID);
			$map = $tag->Sessions()->map('ID', 'ID')->toArray();
			$list = $sessions->filter('ID', $map);
			$sessions = $list;
		}

		if((isset($_GET['CurrentTag']) && $_GET['CurrentTag'] != null)){
			$tagID = $_GET['CurrentTag'];
			$tag = Tag::get()->byID($tagID);
			$map = $tag->Sessions()->map('ID', 'ID')->toArray();
			$list = $sessions->filter('ID', $map);
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
				$tagID = $postFilters['CurrentTag'];
				$tag = Tag::get()->byID($tagID);
				$map = $tag->Sessions()->map('ID', 'ID')->toArray();
				$list = $sessions->filter('ID', $map);
				$sessions = $list;
			}

		} 

		
		//--- TAG FILTER ----//
		if(!empty($filters) && array_key_exists('Tag', $filters)){
			$tagID  = $filters['Tag'];
			$tag = Tag::get()->byID($tagID);
			if($tag){
				$list = $tag->Sessions();
		        $sessions = $list;
		    }
		}

		//--- KEY WORD FILTER --- //
		$sessions = (!empty($idMatch)) ? $sessions->filter('ID', array_keys($idMatch)) : $sessions;

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

	/**
	* Arranges a list of Meeting Sessions into three columns. 
	* @param $sessions Mixed. ArrayList or DataList
	* @param $index An offset for the returned list of Meeting Sessions
	*
	* @return ArrayList
	*/
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

	/**
	* Gets a count of Sessions and Meetings 
	*
	* @return ArrayData
	*/
	public function getCount(){
		$data = array(
			'Sessions' => $this->sessionCount,
			'Meetings' => $this->meetingCount
			);

		return new ArrayData($data);
	}


	/**
	* Gets JSON encoded string of all Speakers
	*
	* @return String
	*/
	public function getSpeakers(){
		$group = Group::get()->filter('Title', 'Speakers')->First();
		if($group){
			$speakers = $group->Members();
			$speakers = $speakers->map('ID', 'Name')->toArray();
			return json_encode($speakers);
		} else {
			return false;
		}
	}

	/**
	* Gets a list of all topics sorted by Names
	*
	* @return DataList
	*/
	public function getTopics(){
		return Topic::get()->sort('Name', 'ASC');
	}

	/**
	* Filters Meeting Sessions and returns Page with filtered list. 
	*
	* @return SessionHolder
	*/
	public function tag(){
        $params = Controller::curr()->getURLParams();
        $tag = $params['ID'];

        $this->filters['Tag'] = $tag;

		$sessions = $this->getSessions($this->filters);
        
        return $this->customise(array('getSessions' => $sessions));
    }

    /**
	* Gets a count of pages of Meeting Sessions. 
	*
	* @return Int
	*/
    public function PageCount(){
    	return $this->pages;
    }

    /**
	* Returns URL for next page or False if last page. 
	*
	* @return Mixed. Boolean or String
	*/
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
	 						$query_elements['Meeting'] = $top_filter['MeetingID'];
					    }
					    if(isset($top_filter['TypeID']) && $top_filter['TypeID'] != null){
					    	$query_elements['Type'] = $top_filter['TypeID'];
					    }
					    if(isset($top_filter['Day']) && $top_filter['Day'] != null){
					    	$query_elements['Day'] = $top_filter['Day'];
					    }
					    if(isset($top_filter['TopicID']) && $top_filter['TopicID'] != null){
					    	$query_elements['Topic'] = $top_filter['TopicID'];
					    }
				    }
				    if(isset($post_filters['Speaker'])){
				    	$speak_filter = $post_filters['Speaker'];
				    	if(isset($speak_filter['MemberID']) && $speak_filter['MemberID'] != null){
					    	$query_elements['Speaker'] = $speak_filter['MemberID'];
					    }
				    }
				    if(isset($post_filters['Sort'])){
				    	$sort_filter = $post_filters['Sort'];
				    	if(isset($sort_filter['Field']) && isset($sort_filter['Direction'])){
					    	$query_elements['Sort'] = $post_filters['Sort'];
					    }
				    }
				     if(isset($post_filters['CurrentTag'])){
				    	$query_elements['Tag'] = $post_filters['CurrentTag'];
				    }
				}
				if(isset($this->filters['Keyword'])){
					$query_elements['Search'] = $this->filters['Keyword'];
				}
				if(isset($this->filters['Tag'])){
					$query_elements['Tag'] = $this->filters['Tag'];
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

    /**
	* Returns URL for previous page.
	*
	* @return String
	*/
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

    /**
	* Returns URL for current page.
	*
	* @return String
	*/
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

	public function getCurrentKeyword(){
		if(isset($_GET['Search'])){ 
			return $_GET['Search'];
		} else if(isset($_POST['Keyword'])){  
			return $_POST['Keyword'];
		}else{ 
			return false;
		}
	}

	public function getCurrentTag(){
		if(isset($_GET['Tag'])){ 
			$tag = Tag::get()->byID($_GET['Tag']);
			if($tag){
				return $tag->Title;
			}
		} else if(isset($_GET['CurrentTag']) && $_GET['CurrentTag'] != null){
			$tag = Tag::get()->byID($_GET['CurrentTag']);
			if($tag){
				return $tag->Title;
			}
		} else if(isset($this->filters['Post']['CurrentTag'])){
			$tag = Tag::get()->byID($this->filters['Post']['CurrentTag']);
			if($tag){
				return $tag->Title;
			}
		} else {
			$params = $this->getURLParams();
			if(isset($params['Action']) && isset($params['ID'])){
				if($params['Action'] == 'tag'){
					$tag = Tag::get()->byID($params['ID']);
					if($tag){
						return $tag->Title;
					}
				}
			}
		 	return false;
		}
	}

  

}