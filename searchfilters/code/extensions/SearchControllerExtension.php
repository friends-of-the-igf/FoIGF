<?php
/**
* Extends 
*/
class SearchControllerExtension extends Extension {
	static $allowed_actions = array(
		'CustomSearchForm',
		'customResults'
	);

	public function CustomSearchForm() {
		

		if($this->owner->request && $this->owner->request->getVar('Search')) {
			$searchText = $this->owner->request->getVar('Search');
		} else {
			$searchText = null;
		}

		$filters = CustomSearchFilter::get()->map('ID', 'Title');

		$search = new TextField('Search', 'Search', $searchText);
		$search->setAttribute('placeholder', 'Search for Sessions, Meetings and Tags...');
		$fields = new FieldList(
			$search
		);
		
		if(count($filters) > 0) {
			$fields->push(new DropdownField('SearchFilters', 'Search Filters', $filters, null, null, 'Select a Category'));
		}

		$actions = new FieldList(
			$button = new FormAction('customResults', _t('SearchForm.Search', 'Search'))
		);

		$button->addExtraClass('btn');
		$button->addExtraClass('btn-primary');


		$form = new CustomSearchForm($this->owner, 'CustomSearchForm', $fields, $actions);
		$form->classesToSearch(FulltextSearchable::get_searchable_classes());
		$form->addExtraClass('form-search');
		$form->loadDataFrom($this->owner->request->getVars());
		return $form;
	}

	public function customResults($data, $form, $request) {
		
	
		$data = array(
			'Results' => $form->getResults(20),
			'Query' => $form->getSearchQuery(),
			'Title' => _t('SearchForm.SearchResults', 'Search Results')
		);

		return $this->owner->customise($data)->renderWith(array('Page_results', 'Page'));
	}

}