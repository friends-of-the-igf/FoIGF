<?php

class FilterSubmission extends DataObject{
	
	static $db = array(
		'Topics' => 'Text',
		'Meeting' => 'Text',
		'Type' => 'Text',
		'Day' => 'Text',
		'Speaker' => 'Text',
		'Sort' => 'Text'
		);


	static $summary_fields = array(
		'Speaker',
		'Meeting',
		'Type',
		'Topics',
		'Day',
		'Sort'
		);

	public function absorb(Array $sub){

		$this->Day = isset($sub['Day']) ? $sub['Day'] : null;
		$this->Sort = isset($sub['Sort']) ? $sub['Sort'] : null;
		$this->Meeting = ($sub['Meeting'] != null) ? Meeting::get()->byID($sub['Meeting'])->getYearLocation(): null;
		$this->Type = ($sub['Type'] != null ) ? Type::get()->byID($sub['Type'])->Title : null;
		if($sub['Topic'] != null){
			$topics = array();
			foreach($sub['Topic'] as $topic){
				$topics[] = Topic::get()->byID($topic)->Title;
			}

			$this->Topics = implode(', ', $topics);
		}

		$this->Speaker = isset($sub['Speaker']) ? $sub['Speaker'] : null;

		$this->write();
	}

	public function getCMSFields(){
		return new FieldList(array(
			new ReadonlyField('Topics'),
			new ReadonlyField('Meeting'),
			new ReadonlyField('Type'),
			new ReadonlyField('Day'),
			new ReadonlyField('Speaker'),
			new ReadonlyField('Sort')
			)
		);
	}


}