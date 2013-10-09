<?php 

class AboutPage extends Page{

	static $db = array(
		'IntroText' => 'Text',
		'Explanation' => 'HTMLText'
		);

	public function getCMSFields(){
		$fields = parent::getCMSFields();


		$fields->insertBefore(new TextareaField('IntroText', 'Introduction Text'), 'Content');
		$fields->addFieldToTab('Root.Explanation', new HTMLEditorField('Explanation', 'Explanation Text'));
	

		return $fields;
	}
	
}

class AboutPage_Controller extends Page_Controller{

	static $allowed_actions = array(
		);

	public function getTopicCount(){
		return Topics::get()->Count();

	}

	public function getSpeakerCount(){
		return Group::get()->filter(array('Title' => 'Speakers'))->First()->Members()->Count();
	}

	public function getSessionCount(){
		return MeetingSession::get()->Count();
	}

	public function getCountryCount(){
		//????????
		return 87;
	}
	

}