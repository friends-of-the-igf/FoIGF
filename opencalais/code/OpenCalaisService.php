<?php

class OpenCalaisService extends RestfulService{
	
	/**
	*
	*/
	public function __construct() {
		

		parent::__construct('http://api.opencalais.com/enlighten/rest/', 3600);
	}

	//This is called to process content, multiple times for chunked
	public function callAPI($content){

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
		$params['content'] = $content;

		$this->setQueryString($params);

		return $this->request();
	}

	/**
	*
	*/
	public function processContent($content){
		//check if the content needs to be chunked
		$content = $this->prepareContent($content);
		//if it did then it will be an array
		if(is_array($content)){
			//set the result holder
			$result = array();
			foreach($content as $chunk){
				$chunkResponse = $this->callAPI($chunk);
				$chunkXML = $chunkResponse->SimpleXML()->CalaisSimpleOutputFormat;
				$chunkResult = $this->getEntities($chunkXML);
				Debug::dump($chunkResult);
				if(empty($result)){
					$result = $chunkResult;
				} else {
					foreach($chunkResult as $chunkEntityType => $entities){

						if(array_key_exists($chunkEntityType, $result)){

							$existingEntities = $result[$chunkEntityType];

							if($chunkEntityType != 'Topics' && $chunkEntityType != 'SocialTags'){

								foreach($entities as $entity => $metadata){

									if(array_key_exists($entity, $existingEntities)){

										$existingEntity = $existingEntities[$entity];

										$existingEntity['Count'] = $existingEntity['Count'] + $metadata['Count'];

										if($existingEntity['Relevance'] < $metadata['Relevance']) {
											
											$existingEntity['Relevance'] = 	$metadata['Relevance'];
										} 

									} else {
										$existingEntities[$entity] = $metadata;
									}
								}
							} else {
								//merge topics and social tags
							}
						} else {
							$result[$chunkEntityType] = $entities;
						}
					}
				}
			}
		} else {
			$response = $this->callAPI($content);
			$xml = $response->SimpleXML()->CalaisSimpleOutputFormat;
			$result = $this->getEntities($xml);
		}
		return $result;
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

	public function prepareContent($content){
		if(strlen($content) > 1000){
			$length = strlen($content);
			$chunks = floor(strlen($content)/1000) + 1;
			$start = 0;
			$end = 1000;
			$nextPeriod = strpos($content, '.', $end)+1 - $start;
			$contentChunks = array();
			for($i = 0; $i < $chunks; $i++){				
				$contentChunks[] = substr($content, $start, $nextPeriod);
				$start = $start + $nextPeriod;
				$end = $end + 1000;
				if($end > $length){
					$nextPeriod = $length;
				} else{
					$nextPeriod = strpos($content, '.', $end)+1-$start;
				}
			}
			// Debug::dump($contentChunks);
			return $contentChunks;
		} else {
			return $content;
		}
	}
}
