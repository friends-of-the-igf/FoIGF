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

	protected $sessionCount;
	protected $meetingCount;

	public static $allowed_actions = array (
		'FilterForm',
		'doSearch'
	);

	public function init() {
		parent::init();
	}

	public function FilterForm(){
		$fields = new FieldList();

		$fields->push($m = new DropdownField('Meeting', 'Meeting', Meeting::get()->map('ID', 'getYearLocation')));
		$m->setEmptyString('-select-');
		$fields->push($t = new DropdownField('Type', 'Session type', Type::get()->map('ID', 'Name')));
		$t->setEmptyString('-select-');
		$fields->push(new TextField('Speaker', 'Speaker'));
		$fields->push(new CheckboxSetField('Topic', 'Sessions on there topics', Topic::get()->map('ID', 'Name')));
		$fields->push(new OptionSetField('Sort', 'Sort Sessions by', array('Latest' => 'Latest', 'Oldest' => 'Oldest', 'Most viewed' => 'Most viewed', 'Most shared' => 'Most shared')));


		$actions = new FieldList($button = new FormAction('doSearch', 'Refine Results'));
		$button->addExtraClass('btn');
		$button->addExtraClass('btn-primary');

		$form = new Form($this, 'FilterForm', $fields, $actions);

		return $form;
	}

	public function doSearch($data, $form){

		$queryString = '';

		if(isset($data['Meeting']) && $data['Meeting'] != null){
			if(strlen($queryString) > 0){
				$queryString .= '&meeting='.$data['Meeting'];
			} else {
				$queryString .= '?meeting='.$data['Meeting'];
			}
		}
		if(isset($data['Type']) && $data['Type'] != null){
			if(strlen($queryString) > 0){
				$queryString .= '&type='.$data['Type'];
			} else {
				$queryString .= '?type='.$data['Type'];
			}
		}
		// if(isset($data['Topic']) && $data['Topic'] != null){
		// 	$i = 0;
		// 	if(strlen($queryString) > 0){
		// 		foreach($data['Topic'] as $topic){
		// 			$queryString .= '&topic='.$data['Topic'];
		// 		}
		// 	} else {
		// 		foreach($data['Topic'] as $topic){
		// 		if($i == 0){
		// 			$queryString .= '?topic='.$data['Topic'];
		// 		} else {
		// 			$queryString .= '&topic='.$data['Topic'];
		// 		}
		// 	}
		// }
		if(isset($data['Speaker']) && $data['Speaker'] != null){
			if(strlen($queryString) > 0){
				$queryString .= '&speaker='.$data['Speaker'];
			} else {
				$queryString .= '?speaker='.$data['Speaker'];
			}
		}
		if(isset($data['Sort']) && $data['Sort'] != null){
			if(strlen($queryString) > 0){
				$queryString .= '&sort='.$data['Sort'];
			} else {
				$queryString .= '?sort='.$data['Sort'];
			}
		}
		
		
		return $this->redirect($this->Link().$queryString);


	}



	public function getSessions(){

		$filter = array();
		if(isset($_REQUEST['meeting']) && $_REQUEST['meeting'] != null){
			$filter['MeetingID'] = $_REQUEST['meeting'];
		}
		if(isset($_REQUEST['type']) && $_REQUEST['type'] != null){
			$filter['TypeID'] = $_REQUEST['type'];
		}
		if(isset($_REQUEST['topic']) && $_REQUEST['topic'] != null){
			$filter['TopicID'] = $_REQUEST['topic'];
		}
		if(isset($_REQUEST['speaker']) && $_REQUEST['speaker'] != null){
			$speakerID = $_REQUEST['speaker'];
		}
		if(isset($_REQUEST['sort']) && $_REQUEST['sort'] != null){
			$sort = $_REQUEST['sort'];
		}
		

		$list = new ArrayList();

		$col1 = new ArrayList();
		$col2 = new ArrayList();
		$col3 = new ArrayList();

		$i = 0;
		$j = 1;
		while ($i <= 17) {

			if(!empty($filter)){
				if(isset($sort)){
					$session = MeetingSession::get()->filter($filter)->sort($sort, 'DESC')->limit(1, $i)->first();
				} else {
					$session = MeetingSession::get()->filter($filter)->sort('Created', 'DESC')->limit(1, $i)->first();
				}
			} else {
				$session = MeetingSession::get()->sort('Created', 'DESC')->limit(1, $i)->first();
			}
			// if(isset($sort)){
			// 	$session->sort($sort, 'DESC');
			// } else {
			// 	$session->sort('Created', 'DESC');
			// }	

			

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
			$i++;
		}

		$list->push(new ArrayData(array('Columns' => $col1)));
		$list->push(new ArrayData(array('Columns' => $col2)));
		$list->push(new ArrayData(array('Columns' => $col3)));

		return $list;
	}



	public function allTags() {
		$sessions = MeetingSession::get();

		$uniqueTagsArray = array();
		foreach($sessions as $session) {
			$tags = preg_split("*,*", trim($session->Tags));
			foreach($tags as $tag) {
				if($tag != "") {
					$tag = strtolower($tag);
					$uniqueTagsArray[$tag] = $tag;
				}
			}
		}

		$output = new ArrayList();
		$link = "";
		if($page = SessionsHolder::get()->First()) {
			$link = $page->Link('tag');
		}

		foreach($uniqueTagsArray as $tag) {
			$tagsList = $this->allTagsList();
			$filteredList = $tagsList->filter('Tag', $tag);
			$weight = $filteredList->Count();

			$output->push(new ArrayData(array(
				'Tag' => $tag,
				'Link' => $link . '/' . urlencode($tag),
				'URLTag' => urlencode($tag),
				'Weight' => $weight
			)));
		}
		
		return $output;
	}

	public function allTagsList() {
		$sessions = MeetingSession::get();
		$tagsList = new ArrayList();
		foreach($sessions as $session) {
			$tags = preg_split("*,*", trim($session->Tags));
			foreach($tags as $tag) {
				if($tag != "") {
					$tag = strtolower($tag);
					$tagsList->push(new ArrayData(array(
						'Tag' => $tag
					)));
				}
			}
		}
		return $tagsList;
	}


}