<?php
/**
* Administration for Meeting Sessions
*
*/
class MeetingSessionAdmin extends ModelAdmin {

	public static $managed_models = array(
		'MeetingSession',
		'Type',
		'Topic'
	);

	static $url_segment = 'sessions';

	static $menu_title = 'Sessions';

	static $model_importers = array();

	public function getEditForm($id = null, $fields = null){
		$form = parent::getEditForm($id, $fields);
		$gridField = $form->Fields()->fieldByName($this->sanitiseClassName($this->modelClass));
		$gridField->getConfig()->addComponent(new GridFieldSortableRows('SortOrder'));
        return $form;
	}

}
