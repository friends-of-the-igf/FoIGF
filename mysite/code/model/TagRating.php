<?php

class TagRating extends DataObject{
	
	static $db = array(
		'Relevant' => 'Boolean',
		'Rater' => 'Text'
		);

	static $has_one = array(
		'Tag' => 'Tag',
		'Session' => 'MeetingSession'
		);


	public function setProperties($raterID = null, $tagID, $sessionID, $relevant){
 		$this->Rater = $raterID;
		$this->TagID = $tagID;
		$this->SessionID = $sessionID;
		$this->Relevant = $relevant;
	}



}