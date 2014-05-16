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
		$pXML = 
		'<c:params xmlns:c="http://s.opencalais.com/1/pred/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">
			<c:processingDirectives c:contentType="text/html" c:enableMetadataType="GenericRelations,SocialTags" c:outputFormat="text/simple" >
			</c:processingDirectives>
			<c:userDirectives c:allowDistribution="false" c:allowSearch="false" >
			</c:userDirectives>
			<c:externalMetadata></c:externalMetadata>
		</c:params>';

		$params['paramsXML'] = $pXML;

		//Set content
		$params['content'] = $this->content;

		$this->setQueryString($params);

		$response = $this->request();
		$xml = $response->SimpleXML()->CalaisSimpleOutputFormat;
		Debug::dump($response);
		return $this->getEntities($xml);
	}

	public function setContent(String $content){
		$this->content = $content;
	}


	/**
	*
	*/
	public function getEntities($xml){
		//Entities
		$types = array();
		foreach($xml->children() as $child){
			$types[$child->getName()] = $child->getName();
		}
		$typeCount = array();
		foreach($types as $type){
			$typeCount[$type] = count($xml->$type);
		}
		foreach($types as $type){
			$entities = array();
			$count = $typeCount[$type];
			for($i = 0; $i < $count; $i++){
				$entityList = $xml->$type;
				$entity = $entityList[$i];
				$attributes = $entity->attributes();
				$entityData = array(
					'Type' => $entity->getName(),
					'Value' => (string)$entity,
					'Normalized' => str_replace(array('normalized', '=', '"'), '', $attributes['normalized']),
					'Relevance' => floatval(str_replace(array('relevance', '=', '"'), '', $attributes['relevance'])),
					'Count' => (int)(str_replace(array('count', '=', '"'), '', $attributes['count'])),
				);
				$entities[$entityData['Value']] = $entityData;
			}
			$types[$type] = $entities;
		}

		//Topic
		$topics = $xml->Topics;
		if($topics){
			$topicArray = array();
			foreach($topics->Topic as $topic){
				$score = $topic->Attributes();
				$topicArray[] = array(
					'Topic' => str_replace('_', ' and ', $topic->asXML()),
					'Value' => (string)$topic,
					'Score' => floatval(str_replace(array('Score', '=', '"'), '', $score['Score']->asXML()))
					);
			}
		
		} else {
			$topicArray = 'No topics were able to be extracted from the content';
		}
		$types['Topics'] = $topicArray;

		//Social Tags
		$tags = $xml->SocialTags;
		if($tags){
			$tagsArray = array();
			foreach($tags->SocialTag as $tag){
				$importance = $tag->Attributes();
				$tagsArray[] = array(
					'Tag' => (string)$tag,
					'Importance' => floatval(str_replace(array('importance', '=', '"'), '', $importance['importance']->asXML()))
					);
			}
		} else {
			$tagsArray = 'No social tags were able to be extracted from the content';
		}
		$types['SocialTags'] = $tagsArray;


		return $types;
	}

	public function prepareContent(){
		
	}
}
