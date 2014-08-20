<?php

class ConvertTagsToObjectsTask extends BuildTask{
	
	protected $title = "Convert Tags To Objects";
	
	protected $description = "Convert string based tags to objects";

	function run($request){
		$sessions = MeetingSession::get();

		foreach($sessions as $session){
			echo 'Converting tags for Session: '. $session->Title . '<br>';
			$tagsString = $session->Tags;
			if($tagsString != null){
				$tagsArray = explode(',', $tagsString);
				foreach($tagsArray as $tag){
					$tag = trim($tag);
					if(strlen($tag) > 0){
						echo 'Converting "'. $tag .'"... ';
						$tagObj = Tag::get()->filter('Title', $tag)->First();
						if(!$tagObj){
							$tagObj = new Tag();
							$tagObj->Title = $tag;
							$tagObj->write();
							echo 'creating new tag(' . $tagObj->ID . ').<br>';
						} else {
							echo 'tag found(' . $tagObj->ID . ').<br>';
						}
						$session->Tags()->add($tagObj);
					}
				}
			}
		}
	}

}