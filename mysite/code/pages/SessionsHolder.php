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

	public static $allowed_actions = array (
	);

	public function init() {
		parent::init();
	}

	public function FilterForm(){
		$fields = new FieldList();

		$fields->push(new DropdownField('Meeting', 'Meeting', Meeting::get()->map('ID', 'getYearLocation')));
		$fields->push(new DropdownField('Type', 'Session type', Type::get()->map('ID', 'Name')));
		$fields->push(new DropdownField('Speaker', 'Speaker', Member::get()->map('ID', 'Name')));
		$fields->push(new CheckboxSetField('Topic', 'Sessions on there topics', Topic::get()->map('ID', 'Name')));
		$fields->push(new CheckboxSetField('Sort', 'Sort Sessions by', array('Latest' => 'Latest', 'Oldest' => 'Oldest', 'Most viewed' => 'Most viewed', 'Most shared' => 'Most shared')));


		$actions = new FieldList($button = new FormAction('doSearch', 'Refine Results'));
		$button->addExtraClass('btn');
		$button->addExtraClass('btn-primary');

		$form = new Form($this, 'FilterForm', $fields, $actions);

		return $form;
	}

	// public function getSessions() {
	// 	$sessions = MeetingSession::get();
	// 	return $sessions;
	// }

	public function getSessions(){
		// $list = new ArrayList();
		// for($i = -1; $i < 18; $i += 7){
		// 	$columns = new ArrayList();
		// 	if($i == -1){
		// 		$col = MeetingSession::get()->sort('Created', 'DESC')->limit(6);
		// 	} else
		// 	{
		// 		$col = MeetingSession::get()->sort('Created', 'DESC')->limit(6, $i);
		// 	}
		// 	foreach($col as $session){
		// 		$columns->push($session);
		// 	}
		// 	$list->push(new ArrayData(array('Columns' => $columns)));
		// }
		// return $list;

		$list = new ArrayList();

		$col1 = new ArrayList();
		$col2 = new ArrayList();
		$col3 = new ArrayList();

		$i = 0;
		$j = 1;
		while ($i <= 17) {
			$session = MeetingSession::get()->sort('Created', 'DESC')->limit(1, $i)->first();
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
			$count = $tagsList->Count();
			$filteredList = $tagsList->filter('Tag', $tag);
			$weight = $filteredList->Count();
			$percent = ($weight / $count) * 100;

			if($percent <= 20) {
				$size = "12px";
			} elseif($percent <= 40) {
				$size = "14px";
			} elseif($percent <= 60) {
				$size = "16px";
			} elseif($percent <= 80) {
				$size = "18px";
			} elseif($percent <= 100) {
				$size = "20px";
			}

			$output->push(new ArrayData(array(
				'Tag' => $tag,
				'Link' => $link . '/' . urlencode($tag),
				'URLTag' => urlencode($tag),
				'Weight' => $percent,
				'Size' => $size
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