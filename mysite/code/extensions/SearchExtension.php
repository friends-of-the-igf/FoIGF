<?php
class SearchExtension extends Extension {

	public function SearchForm() {
		$searchField = new TextField("Search", "");
		$searchField->setAttribute('placeholder', "Search for Sessions, Meetings and Tags...");
		$fields = new FieldList($searchField);
		$form = new Form($this->owner, 'SearchForm', $fields, new FieldList(
			$action = new FormAction('results', 'Search')
		));
		$action->addExtraClass('btn');
		$action->addExtraClass('btn-primary');
		$form->setFormMethod('get');
		$form->disableSecurityToken();
		return $form;
	}

	public function results($data, $form, $request) {
		if(!isset($data)) $data = $_REQUEST;

		$keywords = $data['Search'];
		$start = isset($_GET['start']) ? (int) $_GET['start'] : 0;

		$query = new SearchQuery();
		$query->search($keywords);

		$query->start($start);

		$results = singleton('MyIndex')->search($query);

		$matches = $results->Matches;
		$parser = ShortcodeParser::get();
		foreach($matches as $match) {
			$match->Content = $parser->parse($match->Content);
		}

		return $this->owner->customise(array(
			'Results' => $matches,
			'Query' => $keywords,
			'Suggestions' => null //$results->Suggestion
		))->renderWith(array(
			'Page_results',
			'Page'
		));
	}

}