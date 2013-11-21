<?php
/**
* Extension to Search for text extraction
*
* @package FoIGF
*/
class SearchExtension extends Extension {

	/**
	 * Returns a custom SearchForm
	 * 
	 * @return Form.
	 */
	public function SearchForm() {
		$query = isset($_GET['Search']) && $_GET['Search'] ? $_GET['Search'] : '';

		$searchField = new TextField("Search", "Search", $query);
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

	/**
	 * Form action for Search Form
	 * @param $data Array of form data.
	 * @param $form Form object
	 * @param $request An SS_HTTPRequest
	 * @return Mixed.
	 */
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