<?php

class OpenCalaisPage extends Page{
	

}

class OpenCalaisPage_Controller extends Page_Controller{

	public static $allowed_actions = array(
		'test'
	);


	public function test(){
		$ocs = new OpenCalaisService();
		$ocs->setContent("Matt Gunn");
		$result = $ocs->processContent();


		$extractor = new OpenCalaisXMLParser($result);

	
		return Debug::dump($extractor->getCategories());
		
		
	}
}