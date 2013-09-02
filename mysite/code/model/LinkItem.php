<?php
class LinkItem extends DataObject {

	public static $db = array(
		'Title' => 'Text',
		'Url' => 'Text',
		'Type' => 'Text',
		'Content' => 'HTMLText'
	);

	public static $has_one = array(
		'Meeting' => 'Meeting'
	);

 	public function getCMSFields() {
		$fields = new FieldList();
		
		$fields->push(new TextField('Title', 'Title'));
		$fields->push(new OptionsetField('Type', 'Select Type', array('URL' => 'URL', 'Text' => 'Text')));
		$fields->push(new LabelField('Note', 'Create Item to add URL or Text'));
		if($this->ID){
			if($this->Type == 'URL'){
				$fields->push($f = new TextField('Url', 'URL'));
				$f->setAttribute('placeholder', 'http://');
			} elseif($this->Type == 'Text') {
				$fields->push(new HTMLEditorField('Content', 'Text'));	
			}
		}
					
		return $fields;
	}

}
