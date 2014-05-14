<?php

class OpenCalaisXMLParser{
	
	protected $output;

	public function __construct(RestfulService_Response $result) {
		$this->output = $result->SimpleXML()->CalaisSimpleOutputFormat;
	}

	public function getCategories(){
		$entityTypes = array();
		foreach($this->output->children() as $child){
			$entityTypes[$child->getName()] = $child->getName();
		}

		return $entityTypes;
	}






	

}