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
		$fields->push(new TextField('Text', 'Text'));

		$actions = new FieldList($button = new FormAction('doSearch', 'Search'));
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
		$list = new ArrayList();
		for($i = -1; $i < 20; $i += 8){
			$columns = new ArrayList();
			if($i == -1){
				$col = MeetingSession::get()->sort('Created', 'DESC')->limit(7);
			} else
			{
				$col = MeetingSession::get()->sort('Created', 'DESC')->limit(7, $i);
			}
			foreach($col as $session){
				$columns->push($session);
			}
			$list->push(new ArrayData(array('Columns' => $columns)));
		}
		return $list;
	}

}