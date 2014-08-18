<?php 

class QuestionnaireSubmission extends DataObject{
	
	static $db = array(
		'SecurityID' => 'Varchar',
		'Purpose' => 'Varchar',
		'Information' => 'Text',
		'Question' => 'Text',
		'Topic' => 'Text',
		'Research' => 'Text'
		);

	static $summary_fields = array(
		'SecurityID',
		'Purpose' ,
		'Information',
		'Question',
		'Topic',
		'Research'
		);

	public function getCMSFields(){
		$fields = new FieldList();

		foreach(self::$db as $field => $type){
			$fields->push(new ReadonlyField($field, $field));
		}

		return $fields;
	}

}