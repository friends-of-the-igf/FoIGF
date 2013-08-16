<?php
class Page extends SiteTree {

	public static $db = array(
	);

	public static $has_one = array(
	);

	public function LinkingMode() {
        if($this->isCurrent()) {
            return 'active';
        } elseif($this->isSection()) {
            return 'active';
        } else {
            return false;
        }
    }

}
class Page_Controller extends ContentController {

	public static $allowed_actions = array (
		'SearchForm',
		'doSearch'
	);

	public function init() {
		parent::init();
	}

	public function SearchForm(){
		$fields = new FieldList($input = new TextField('Search', 'Search'));

		$input->setAttribute('placeholder', 'Search for Sessions, Meetings and Speakers...');

		$actions = new FieldList($button = new FormAction('doSearch', 'Search'));
		$button->addExtraClass('btn');
	
		$button->addExtraClass('btn-primary');

		$form = new SearchForm($this, 'SearchForm', $fields, $actions);
	
		$form->addExtraClass('form-search');

		return $form;
	}

	public function doSearch($data, $form, $request) {
		$data = array(
            'Results' => $form->getResults(),
            'Query' => $form->getSearchQuery(),
            'Title' => _t('SearchForm.SearchResults', 'Search Results')
        );

        var_dump($form->getSearchQuery());
        // return $this->owner->customise($data)->renderWith(array('Page_results', 'Page'));
	}

	public function sessionLink(){
		if($page = SessionsHolder::get()->First()) {
			return $page->Link();
		}
	}


}