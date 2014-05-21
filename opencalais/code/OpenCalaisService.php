<?php

class OpenCalaisService extends RestfulService{
	

	public function __construct() {
		parent::__construct('http://api.opencalais.com/enlighten/rest/', 3600);
	}

	/**
	* This function accepts a string of content as a parameter and then calls the Open Calais API to process it. 
	*	@param $content - String (1000 characters max)
	*	@return RestfulService_Response Object
	*/
	public function callAPI(String $content){

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
	* This is the primary function. It accepts a string of the content that needs to be processed and returns an array of extracted entities.
	*	@param $content - String
	*	@return array
	*/
	public function processContent(String $content){
		$content = $this->prepareContent($content);
		if(is_array($content)){
			$result = $this->processChunks($content);
		} else {
			$response = $this->callAPI($content);
			$xml = $response->SimpleXML()->CalaisSimpleOutputFormat;
			$result = $this->getEntities($xml);
		}
		return $result;
	}

	/**
	* This function takes the xml object of entities and turns them into an array.
	*	@param $xml - SimpleXMLElement
	*	@return array
	*/
	public function getEntities(SimpleXMLElement $xml){
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

	/**
	* This function takes a string of content and determines if it is too large to be processed. If it, it breaks it into approx.
	* 1000 word chunks to the nearest full stop and are returned as an array.
	*	@param $content - String
	*	@return mixed. An array or a string.
	*/
	public function prepareContent(String $content){
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
					if($nextPeriod < 0){
						$nextPeriod = $length;
					}
				}
			}
			return $contentChunks;

		} else {
			return $content;
		}
	}

	/**
	* This function will process an array of strings, process them with the Open Calais service
	* and return a single array with all the extracted metadata.
	*	@param $content - Array
	*	@return array
	*/	
	public function processChunks(Array $content){
		$result = array();
		foreach($content as $chunk){
			$chunkResponse = $this->callAPI($chunk);
			$chunkXML = $chunkResponse->SimpleXML()->CalaisSimpleOutputFormat;
			$chunkResult = $this->getEntities($chunkXML);
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
									$result[$chunkEntityType] = $existingEntities;
								}
							}
						} else {
							if(is_array($entities)){
								//Topics
								if($chunkEntityType == 'Topics'){
									foreach($entities as $index => $metadata){
										if(is_array($result['Topics'])){
											$inArray = false;
											foreach($result['Topics'] as $topic){
												if($topic['Value'] == $metadata['Value']){
													$inArray = true;
													if($topic['Score'] < $metadata['Score']){
														$topic['Score'] = $metadata['Score'];
													}
												}
											}
											if(!$inArray){
												array_push($result['Topics'], $metadata);
											}
										} else {
											$result['Topics'] = array($metadata);
										}
									}
								} else {
									//Social Tags
									foreach($entities as $index => $metadata){
										if(is_array($result['SocialTags'])){
											$inArray = false;
											foreach($result['SocialTags'] as $socialTag){
												if($socialTag['Tag'] == $metadata['Tag']){
													$inArray = true;
													if($socialTag['Importance'] < $metadata['Importance']){
														$socialTag['Importance'] = $metadata['Importance'];
													}
												}
											}
											if(!$inArray){
												array_push($result['SocialTags'], $metadata);
											}
										} else {
											$result['SocialTags'] = array($metadata);
										}
									}
								}
							}
						}
					} else {
						$result[$chunkEntityType] = $entities;
					}
				}
			}
		}
		return $result;
	}
}
