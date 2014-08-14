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
		$query = isset($_GET['Search']) && $_GET['Search'] ? $_GET['Search'] : false;

		if(!$query){
			$query = (isset($_POST['Keyword'])) ? $_POST['Keyword'] : '';
		}
	
		$searchField = new TextField("Search", "Search", $query);
		$searchField->setAttribute('placeholder', "Search for Sessions");

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

		
		$sessionHolder = SessionsHolder::get()->First();
		$sessionHolderLink = $sessionHolder->AbsoluteLink();

		return $this->owner->redirect($sessionHolderLink.'?Search='.$data['Search']);
	}

}