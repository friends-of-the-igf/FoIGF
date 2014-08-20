<?php

/**
*
* This is a controller to handle the requests for tag extraction from content. 
*
*/
class OpenCalaisController extends Controller{

	static $allowed_actions = array(
		'suggestTags',
		'addTag',
		'setFormCookie'
		);

	/**
	* Extracts semantic tags from a Session and presents them in a table
	*/
	public function suggestTags(){

		//Make sure we have what we need
		$id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
		$session = ($id) ? MeetingSession::get()->byID($id) : null;
		if($session){
			//Add all the contents of the session to an array
			$contents[] = (strlen($session->Content)) ? $session->Content : null;

			if($session->Transcripts()->filter(array('TranscriptType' => 'Text'))->First()){
				$contents[] = $session->Transcripts()->filter(array('TranscriptType' => 'Text'))->First()->Content;
			}

			$contents[] = ($session->ReportType == 'Text' && strlen($session->ReportContent) > 0) ? $session->ReportContent : null;
			$contents[] = ($session->ProposalType == 'Text' && strlen($session->ProposalContent) > 0) ? $session->ProposalContent : null;

			//Remove any null values
			$contents = array_filter($contents);

			//Make an Open Calais service
			$oc = new OpenCalaisService();
			$tags = array();
			$existingTags = $session->Tags()->map()->toArray();
			//Iterate through all the content, extract the tags and then add them an array if they don't already exists on the session. 
			foreach($contents as $content){
				$extraction = $oc->processContent($content);
				foreach($extraction as $type => $entity){
					$entity = reset($entity);
					if($type != 'URL' && $type != 'Phone Number'){
						switch($type){
							case 'SocialTags':
								$tag = strtolower($entity['Tag']);
								if(!in_array($tag, $existingTags)){
									$tags[$entity['Tag']] = array('Title' => $tag);
								}
								break;
							case 'Topics':
								$tag = simplexml_load_string(strtolower($entity['Topic']));
								$tag = (string)$tag[0];
								if(!in_array($tag, $existingTags)){
									$tags[$entity['Topic']] = array('Title' => $tag);
								}
								break;
							default:
								$tag = strtolower($entity['Value']);
								if(!in_array($tag, $existingTags)){
									$tags[$entity['Value']] = array('Title' => $tag);
								}
								break;
						}
					}
				}
			}

			//Return the Tags in a table.
			$tags = new ArrayList($tags);
			$data = new ArrayData(array('SuggestedTags' => $tags));
			
			return $data->renderWith('SuggestedTags');
		} else {
			//TO DO: Return a message about missing shit
		}	
	}

	/**
	* Adds a chosen tag to the session
	*/
	public function addTag(){
		$tag = (isset($_REQUEST['tag'])) ? $_REQUEST['tag'] : null;
		$id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;

		if(!$tag){
		   return 'error'; //TO DO: some missing shit
		}

		$tagObj = Tag::get()->filter('Title', $tag)->First();
		if(!$tagObj){
			$tagObj = new Tag();
			$tagObj->Title = $tag;
			$tagObj->Provenance = 'OpenCalais';
			$tagObj->write();
		} 

		$session = ($id) ? MeetingSession::get()->byID($id) : null;
		if($session){
			$session->Tags()->add($tagObj);
			return 'success';
		} else {
			//TO DO: some missing shit
		}
	}
}