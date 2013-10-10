<?php 

class AboutPage extends Page{

	static $db = array(
		'IntroText' => 'Text',
		'ContactText' => 'Text',
		'WhoIGF' => 'HTMLText',
		'WhatIGF' => 'HTMLText',
		'Explanation' => 'HTMLText'
		);

	public function getCMSFields(){
		$fields = parent::getCMSFields();
		
		$fields->insertBefore(new TextareaField('IntroText', 'Introduction Text'), 'Content');
		$fields->insertBefore(new TextareaField('ContactText', 'Contact Text'), 'Content');
		$fields->removeByName('Content');
		$fields->addFieldToTab('Root.Who', new HTMLEditorField('WhoIGF', 'Who are the Friends of the IGF'));
		$fields->addFieldToTab('Root.What', new HTMLEditorField('WhatIGF', 'What is the IGF'));
		$fields->addFieldToTab('Root.InternetGoverneance', new HTMLEditorField('Explanation', 'Explanation Text'));
		

		return $fields;
	}
	
}

class AboutPage_Controller extends Page_Controller{

	static $allowed_actions = array(
		);

	public function getTopicCount(){
		return Topic::get()->Count();

	}

	public function getSpeakerCount(){
		return Group::get()->filter(array('Title' => 'Speakers'))->First()->Members()->Count();
	}

	public function getSessionCount(){
		return MeetingSession::get()->Count();
	}

	public function getCountryCount(){
		return 87;
	}
	

}