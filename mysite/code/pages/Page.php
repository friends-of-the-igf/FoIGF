<?php
/**
* Base Page type
*
* @package FoIGF
*/
class Page extends SiteTree {

	public static $db = array(
	);

	public static $has_one = array(
	);

	public function LinkingMode() {
        if($this->isCurrent()) {
            return 'active';
        } elseif($this->isSection()) {
            return 'active';
        } else {
            return false;
        }
    }

}
class Page_Controller extends ContentController {

	public static $allowed_actions = array(
		'SearchForm',
		'results',
		'QuestionnaireForm',
		'setFormCookie'
	);

	public function init() {
		parent::init();

		//Start timer for Questionnaire pop up
		$start = Session::get('SessionStart');
		if($start == null){
			Session::set('SessionStart', time());
		}

	}


	/**
	* Returns time elapsed since Questionnaire timer started.
	*/
	public function SessionLength(){
		$start = Session::get('SessionStart');
		if($start != null){
			$start = Session::get('SessionStart');
		    $now = time();
		    return $now - $start;
		} else {
			return 0;
		}
	}

	public function QuestionnaireCookie(){
		$cookie = Cookie::get('HideForm');
		return ($cookie == null) ? false : true;
	}

	/**
	 * Returns a link to the first Session Holder page
	 * 
	 * @return String.
	 */
	public function sessionLink(){
		if($page = SessionsHolder::get()->First()) {
			return $page->Link();
		}
	}

	/**
	 * Create tag cloud object for front end weighting
	 * 
	 * @param $limit if ommited will return all tags.
	 * @param $sort TRUE or FALSE. if TRUE will sort tags by weight, high to low.
	 * @param $filter Meeting->ID or FALSE. if Meeting->ID will filter MeetingSession->Tags by Meeting.
	 * @return ArrayList.
	 */
	public function AllTags($limit = null, $sort = null, $filter = null) {
		$tags = Tag::get();

		$count = DB::query('SELECT COUNT(*) FROM MeetingSession_Tags');
		$count = $count->value();

		$output = new ArrayList();
		foreach($tags as $tag) {
			$weight = DB::query('SELECT COUNT(*) FROM MeetingSession_Tags WHERE TagID ='.$tag->ID);
			$weight = $weight->value();

			error_log($weight);
			$percent = ($weight / $count) * 100;

			if($percent <= 1) {
				$size = "14px";
			} elseif($percent <= 2) {
				$size = "16px";
			} elseif($percent <= 3) {
				$size = "18px";
			} elseif($percent <= 5) {
				$size = "20px";
			} elseif($percent <= 10) {
				$size = "22px";
			} else {
				$size = "23px";
			}

			$output->push(new ArrayData(array(
				'Title' => $tag->Title,
				'Link' => $tag->Link(),
				'Weight' => $percent,
				'Size' => $size
			)));
		}
		if($sort) {
			$output->sort('Weight', 'DESC');
		}

		if($limit) {
			return new ArrayList(array_slice($output->items, 0, $limit));
		}
		return $output;
	}

	/**
	 * Gets all meetings in order of most recent
	 * 
	 * @return Datalist.
	 */
	public function getMeetings() {
		$meetings = Meeting::get()->Sort('StartDate', 'DESC');
		return $meetings;
	}

	/**
	 * Returns a link to the first Meetings Holder page
	 * 
	 * @return String.
	 */
	public function meetingsLink(){
		return MeetingsHolder::get()->First()->Link();

	}

	/**
	* Returns whether there is a Researcher logged in. 
	*/
	public function isResearcher(){
		$member = Member::CurrentUser();
		$group = SiteConfig::current_site_config()->ResearchGroup();
		if($group && $member){
			return $member->inGroup($group->Code);
		} else {
			return false;
		}	
	}

	public function OpenCalaisPage(){
		if($pages = OpenCalaisPage::get()){
			return $pages->First();
		}
	}

	/**
	* Questionnaire Form for Content Enrichment.
	*/
	public function QuestionnaireForm(){
		$fields = new FieldList();

		$options = array(
			'Information' => "I'm trying to find a specific piece of information",
			'Question' => "I'm looking for the answer to a particular question",
			'Topic' => "I'm researching a particular topic",
			"I'm just browsing"
			);

		$fields->push(new OptionsetField('Purpose', 'What is the purpose of your visit to the IGF website today?', $options));

		// $fields->push()

		$fields->push($field = new TextAreaField('Information', 'What information are you looking for?'));
		$fields->push($field = new TextAreaField('Question', 'What question are you trying to find an answer to?'));
		$fields->push($field = new TextAreaField('Topic', 'What topic are you researching?'));
		$fields->push($field = new TextAreaField('Research', 'What is the purpose of your research?'));
		

		$actions = new FieldList(new FormAction('submit', 'Next'));

		return new Form($this, 'QuestionnaireForm', $fields, $actions);
	}

	public function submit($data, $form){
		$submission = new QuestionnaireSubmission();
		$form->saveInto($submission);
		$submission->write();

		return $this->redirectBack();
	}

	public function setFormCookie(){
		Cookie::set('HideForm', true, 7);
	}
}