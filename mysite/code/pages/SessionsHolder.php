<?php
class SessionsHolder extends Page {

	public static $db = array(
	);

	public static $has_one = array(
	);

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

}