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




	public function absorb(Array $sub){

		
		$this->Day = $sub['Day'];
		$this->Sort = $sub['Sort'];
		$this->Meeting = Meeting::get()->byID($sub['Meeting'])->getYearLocation();
		$this->Type = Type::get()->byID($sub['Type'])->Title;
		$topics = array();
		foreach($sub['Topic'] as $topic){
			$topics[] = Topic::get()->byID($topic)->Title;
		}

		$this->Topics = implode(', ', $topics);
		$this->Speaker = $sub['Speaker'];
		

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