<?php
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
		'CustomSearchForm',
		'customResults',
	);

	public function init() {
		parent::init();
	}


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
	public function popularTags($limit = null, $sort = null, $filter = null) {
		$uniqueTags = MeetingSession::get_unique_tags($filter);
		$allTags = MeetingSession::get_all_tags($filter);
		$list = GroupedList::create($allTags);
		$list = $list->GroupedBy('Tag', 'Tags');

		$count = $allTags->Count();
		$output = new ArrayList();
		$link = (SessionsHolder::get()->First() ? SessionsHolder::get()->First()->Link('tag') : "");

		foreach($uniqueTags as $tag) {
			$item = $list->find('Tag', $tag);
			$weight = $item->Tags->Count();
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
			}

			$output->push(new ArrayData(array(
				'Tag' => $tag,
				'Link' => $link . '/' . urlencode($tag),
				'URLTag' => urlencode($tag),
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

	public function getMeetings() {
		$meetings = Meeting::get()->Sort('StartDate', 'DESC');
		return $meetings;
	}

	public function meetingsLink(){
		return MeetingsHolder::get()->First()->Link();

	}


}