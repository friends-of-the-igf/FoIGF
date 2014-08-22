<?php
/**
* Controller to display Meeting Sessions
*
* @package FoIGF
*/
class SessionController extends Page_Controller {
	
	public static $url_handlers = array(
		'$ID!/$Action' => 'handleAction'
	);

	public static $allowed_actions = array(
        'TagForm',
        'submitTag',
        'approveTag' => '->isCurator',
        'denyTag' => '->isCurator',
        'rateTag',
        'OpenCalaisForm',
        'setFormCookie'
	);

	protected $meetingsession = null;

	public function init() {
		parent::init();
		
		Requirements::javascript('themes/igf/javascript/sessioncontroller.js');
		Requirements::javascript('themes/igf/thirdparty/bootstrap-typeahead.js');

		$id = (int)$this->request->param('ID');
		if($meetingsession = MeetingSession::get()->ByID($id)) {

			// view counter
			$config = SiteConfig::current_site_config();
			if($config->ViewCheck) {
				if($viewed_sessions = Session::get('ViewedSessions')) {
					if(!in_array($meetingsession->ID, $viewed_sessions)) {
						$viewed_sessions[] = $meetingsession->ID;
						Session::set('ViewedSessions', $viewed_sessions);
						$meetingsession->Views = $meetingsession->Views + 1;
						$meetingsession->write();
					}
				} else {
					Session::set('ViewedSessions', array($meetingsession->ID));
					$meetingsession->Views = $meetingsession->Views + 1;
					$meetingsession->write();
				}
			}
			Session::set('CurrentSession', $meetingsession);
			$this->meetingsession = $meetingsession;
		} else {
			if($this->request->param('Action') == 'SearchForm' 
				|| $this->request->param('Action') == 'TagForm' 
				|| $this->request->param('Action') == 'getTags' 
				|| $this->request->param('Action') == 'OpenCalaisForm'
				|| $this->request->param('Action') == 'setFormCookie'){
				return;
			}else{
				return $this->httpError(404);
			}
		}
	}

	/**
	 * Gets current MeetingSession
	 * 
	 * @return MeetingSession.
	 */
	public function getMeetingSession() {
		return $this->meetingsession;
	}
	
	/**
	 * Gets ClassName for the Controller
	 * 
	 * @return String.
	 */
	public function getClassName() {
		return 'SessionController';
	}

	/**
	 * Gets Titile of current Meeting Session
	 * 
	 * @return String.
	 */
	public function Title(){
		if($this->meetingsession){
			return $this->meetingsession->Title;
		}
	}


	public function OpenCalaisForm(){

		$fields = new FieldList(array(
			new CheckboxSetField('ContentSelection', 'Select what areas of content you would like to process:', array('Transcripts' => 'Transcript', 'Agenda' =>  'Agenda', 'Proposal' =>  'Proposal', 'Report' =>  'Report')),	
			)
		);
		if($this->meetingsession){
			$fields->push(new HiddenField('MeetingSessionID', 'MeetingSessionID', $this->meetingsession->ID));
		}

		$required = new RequiredFields(array('ContentSelection'));

		$actions = new FieldList(new FormAction('processSession', 'Extract Entities'));

		return new Form($this, 'OpenCalaisForm', $fields, $actions, $required);
	}

	public function processSession($data, $form){
		$id = $data['MeetingSessionID'];

		$page = OpenCalaisPage::get()->First();
		if($page){
			$link = $page->Link().'openCalaisSession?ID='.$id;
			foreach($data['ContentSelection'] as $area){
				$link.='&area[]='.$area;
			}
			return $this->redirect($link);	
		}

	}

	public function TagForm(){
		$fields = new FieldList();
		$fields->push(new TextField('Tag', 'Suggest a new tag'));
		if($this->meetingsession){
			$fields->push(new HiddenField('MSID', 'MSID', $this->meetingsession->ID));
		}
		
		$actions = new FieldList($btn = new FormAction('submitTag', ''));
		$btn->setUseButtonTag(true);
		$btn->setButtonContent('<i class="fa fa-plus"></i>');
		$btn->addExtraClass('btn');
		$btn->addExtraClass('btn-primary');
		$validator = new RequiredFields('Tag');
		return new Form($this, 'TagForm', $fields, $actions);
	}

	public function submitTag($data, $form){
		/* ------- Cookie Validation Time! --------- */
		$cookie = Cookie::get('RaterCookie');
		//Missing Cookie
		if(!$cookie){
			return json_encode(array(
			'Status' => 'Failure',
			'Content' => 'Please enable cookies to rate tags.'
			));
		}

		//Make our data easy to use. 
		$cookieData = explode(',', $cookie);
		$userID = $cookieData[0];
		$timestamp = $cookieData[1];
		$hash = $cookieData[2];
		$now = time();

		//Authenticate the cookies hash
		$rehash = crypt($userID.$timestamp, COOKIE_SALT);
		if($hash != $rehash){
			return json_encode(array(
				'Status' => 'Failure',
				'Content' => 'Sorry, we could not authenticate your cookie'
				));
		}

		//Validate the cookies extistence for more than 5 min.
		$timeElapsed = ($now - $timestamp)/60;
		if($timeElapsed < 5){
			return json_encode(array(
				'Status' => 'Failure',
				'Content' => "To help us avoid spam, you can't add new tags until you've been on the website for at least 5 minutes, please try again in a few minutes time."
				));
		}

		//Validate that there have not been more than 60 ratings by this user in the last 5 minutes.
		$usersRatings = TagRating::get()->filter(array('Rater' => $userID));
		if($usersRatings->Count() > 60){
			$fiveMinutesAgo = date('Y-m-d H:i:s', $now - 300);
			$recentRatings = $usersRatings->filter('Created:GreaterThan', $fiveMinutesAgo);
			if($recentRatings->Count() >= 60){
				return json_encode(array(
					'Status' => 'Failure',
					'Content' => 'Sorry, you have rated 60 tags in less than 5 minutes. Take a little break.'
					));
			}
		}
		/*----- You have successfully passed cookie validation -----*/	

		$session = MeetingSession::get()->byID($data['MSID']);

		$tag = Tag::get()->filter(array('Title' => strtolower(trim($data['Tag']))))->First();
		$newTag = false;
		if(!$tag){
			$newTag = true;
			$tag = new Tag();
			$tag->Title = strtolower(trim($data['Tag']));
			$tag->Provenance = 'Crowd';
			$tag->Status = 'Pending';
			$tag->write();
		} else if($tag->Status == 'Pending'){
			$newTag = true;
		}

		$session->Tags()->add($tag);

		$rating = new TagRating(); 
		$rating->setProperties($userID, $tag->ID, $session->ID, true);
		$rating->write();

		if($this->getRequest()->isAjax()){
			if($newTag){
				return json_encode(array(
					'Status' => 'Pending',
					'Content' => 'Thank you, your tag has been submitted for approval.'
					));

			} else {
				$data = new ArrayData(array(
					'Tag' => $tag,
					'MeetingSession' => $session,
					'Rating' => 1
					));

				return json_encode(array(
					'Status' => 'Success',
					'Content' => $data->renderWith('Tag')
					));
			}
		} else {
			return $this->redirectBack();
		}
	}

	public function getTags(){
		$tags = $this->meetingsession->Tags()->filter('Status', 'Approved');
		$list = new ArrayList();
		foreach($tags as $tag){
			$netRatings = $this->calculateRating($tag->ID, $this->meetingsession->ID);

			$tagData = new ArrayData(array(
				'Tag' => $tag,
				'Rating' => $netRatings
				));
			$list->push($tagData);
		}
		$list = $list->Sort('Rating', 'DESC');
		$list->limit(10);
		return $list;
	}

	public function getPendingTags(){
		return $this->meetingsession->Tags()->filter('Status', 'Pending');
	}


	/**
	* Approves a pending tag
	*/
	public function approveTag(){
		$id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;

		$tag = Tag::get()->byID($id);
		if(!$tag){
			return;//Send back error;
		}

		$session = (isset($_REQUEST['session'])) ? $_REQUEST['session'] : null;
		$session = MeetingSession::get()->byID($session);
		//Missing Session ID from Requesr
		if(!$session){
			return;//TO DO: Missing shit
		}

		$tag->Status = 'Approved';
 		$tag->write();

 		if($this->getRequest()->isAjax()){
 			$data = new ArrayData(array(
				'Tag' => $tag,
				'MeetingSession' => $session,
				'Rating' => 1
				));

			return json_encode(array(
				'ID' => $id,
				'Status' => 'Approved',
				'Content' => $data->renderWith('Tag')
				));
		} else {
			return $this->redirectBack();
		} 
	}	

	/**
	* Removes a pending tag
	*/
	public function denyTag(){
		$id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;

		$tag = Tag::get()->byID($id);
		if(!$tag){
			return;//TO DO: Missing Shit
		}

		$session = (isset($_REQUEST['session'])) ? $_REQUEST['session'] : null;
		$session = MeetingSession::get()->byID($session);
		//Missing Session ID from Requesr
		if(!$session){
			return;//TO DO: Missing shit
		}

		$tag->delete();

		$ratings = TagRating::get()->filter('TagID', $id);
		foreach($ratings as $rating){
			$rating->delete;
		}
 
 		if($this->getRequest()->isAjax()){
			return json_encode(array(
				'ID' => $id,
				'Status' => 'Denied'
				));
		} else {
			return $this->redirectBack();
		} 
	}

	public function rateTag(){
		//Get our variables and make sure we have what we need.
		$relevant = isset($_REQUEST['r']);

		$id = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : null;
		//Missing Tag ID from request
		if(!$id){
			return;//TO DO: Missing shit
		}

		$session = (isset($_REQUEST['session'])) ? $_REQUEST['session'] : null;
		//Missing Session ID from Requesr
		if(!$session){
			return;//TO DO: Missing shit
		}

		/* ------- Cookie Validation Time! --------- */
		$cookie = Cookie::get('RaterCookie');
		//Missing Cookie
		if(!$cookie){
			return json_encode(array(
			'Status' => 'Failure',
			'Content' => 'Please enable cookies to rate tags.'
			));
		}

		//Make our data easy to use. 
		$cookieData = explode(',', $cookie);
		$userID = $cookieData[0];
		$timestamp = $cookieData[1];
		$hash = $cookieData[2];
		$now = time();

		//Authenticate the cookies hash
		$rehash = crypt($userID.$timestamp, COOKIE_SALT);
		if($hash != $rehash){
			return json_encode(array(
				'Status' => 'Failure',
				'Content' => 'Sorry, we could not authenticate your cookie'
				));
		}

		//Validate the cookies extistence for more than 5 min.
		$timeElapsed = ($now - $timestamp)/60;
		if($timeElapsed < 5){
			return json_encode(array(
				'Status' => 'Failure',
				'Content' => "To help us avoid spam, you can't add new tags until you've been on the website for at least 5 minutes, please try again in a few minutes time."
				));
		}

		//Validate that this tag has not been rated in the last 24 hours.
		$latestRating = TagRating::get()->filter(array('Rater' => $userID, 'TagID' => $id, 'SessionID' => $session))->Sort('Created', 'DESC')->First();
		if($latestRating){

			$created = date_create_from_format('Y-m-d H:i:s', (string)$latestRating->Created);
			if($created){
				$created = $created->getTimestamp();
			} else {
				$created = 0;
			}
			$difference = ($now - $created)/(60 * 60 * 24);
			if($difference < 24){
				return json_encode(array(
					'Status' => 'Failure',
					'Content' => 'You can only rate a particular tag once every 24 hours.'
					));
			}
		}

		//Validate that there have not been more than 60 ratings by this user in the last 5 minutes.
		$usersRatings = TagRating::get()->filter(array('Rater' => $userID));
		if($usersRatings->Count() > 60){
			$fiveMinutesAgo = date('Y-m-d H:i:s', $now - 300);
			$recentRatings = $usersRatings->filter('Created:GreaterThan', $fiveMinutesAgo);
			if($recentRatings->Count() >= 60){
				return json_encode(array(
					'Status' => 'Failure',
					'Content' => 'Sorry, you have rated 60 tags in less than 5 minutes. Take a little break.'
					));
			}
		}
		/*----- You have successfully passed cookie validation -----*/


		$rating = new TagRating();
		$rating->setProperties($userID, $id, $session, $relevant);
		$rating->write();

		$netRating = $this->calculateRating($id, $session);

		if($this->getRequest()->isAjax()){
			return json_encode(array(
					'ID' => $id,
					'Rating' => $netRating
					));
		} else {
			return $this->redirectBack();
		} 
	}

	

	public function calculateRating($tag, $session){
		$q = DB::query('SELECT COUNT(*) FROM TagRating WHERE TagID='.$tag.' AND SessionID='.$session.' AND Relevant=1 ');
		$positiveRatings = (int)$q->value();

		$q = DB::query('SELECT COUNT(*) FROM TagRating WHERE TagID='.$tag.' AND SessionID='.$session.' AND Relevant=0');
		$negativeRatings = (int)$q->value();

		return  $positiveRatings - $negativeRatings;
	}


	public function setFormCookie(){
		Cookie::set('HideForm', true, 7);
	}

	




}
