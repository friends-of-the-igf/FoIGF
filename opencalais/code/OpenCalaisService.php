<?php

class OpenCalaisService extends RestfulService{
	
	/**
	*
	*/
	protected $content;
	/**
	*
	*/
	public function __construct($content = null) {
		$this->content = $content;

		parent::__construct('http://api.opencalais.com/enlighten/rest/', 3600);
	}

	/**
	*
	*/
	public function processContent(){

		//Get and set the API key from settings. Throw error if it hasn't been entered.
		$key = SiteConfig::current_site_config()->OpenCalaisAPIKey;
		if($key == null){
			user_error('Please set an Open Calais API Key in the CMS Settings', E_USER_ERROR);
		}
		$params['licenseID'] = $key;

		//Set paramsXML
		$pXML = "<c:params xmlns:c=\"http://s.opencalais.com/1/pred/\" " . 
			"xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"> " .
			"<c:processingDirectives c:contentType=\"text/html\" " .
			"c:outputFormat=\"text/simple\"".
			" c:omitOutputtingOriginalText=\"TRUE\"></c:processingDirectives> " .
			"<c:userDirectives c:allowDistribution=\"true\" " .
			"c:allowSearch=\"true\" c:externalID=\" \" " .
			"></c:userDirectives> " .
			"</c:params>";

		$params['paramsXML'] = $pXML;

		//Set content
		$params['content'] = $this->content;

		$this->setQueryString($params);

		return $this->request();
	}

	public function setContent(String $content){
		$this->content = $content;
	}

}
