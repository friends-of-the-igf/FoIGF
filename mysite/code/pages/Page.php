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

	public static $allowed_actions = array (
		'SearchForm',
		'doSearch'
	);

	public function init() {
		parent::init();
	}

	public function SearchForm(){
		$fields = new FieldList($input = new TextField('Search', 'Search'));

		$input->setAttribute('placeholder', 'Search for Sessions, Meetings and Speakers...');

		$actions = new FieldList($button = new FormAction('doSearch', 'Search'));
		$button->addExtraClass('btn');
	
		$button->addExtraClass('btn-primary');

		$form = new Form($this, 'SearchForm', $fields, $actions);
	
		$form->addExtraClass('form-search');

		return $form;
	}

	public function doSearch($data, $form) {

        var_dump($data);
        // return $this->owner->customise($data)->renderWith(array('Page_results', 'Page'));
	}

	public function sessionLink(){
		if($page = SessionsHolder::get()->First()) {
			return $page->Link();
		}
	}

	public function allTags() {
		$sessions = MeetingSession::get();

		$uniqueTagsArray = array();
		foreach($sessions as $session) {
			$tags = preg_split("*,*", trim($session->Tags));
			foreach($tags as $tag) {
				if($tag != "") {
					$tag = strtolower($tag);
					$uniqueTagsArray[$tag] = $tag;
				}
			}
		}

		$output = new ArrayList();
		$link = "";
		if($page = SessionsHolder::get()->First()) {
			$link = $page->Link('tag');
		}

		foreach($uniqueTagsArray as $tag) {
			$tagsList = $this->allTagsList();
			$count = $tagsList->Count();
			$filteredList = $tagsList->filter('Tag', $tag);
			$weight = $filteredList->Count();
			$percent = ($weight / $count) * 100;

			if($percent <= 5) {
				$size = "14px";
			} elseif($percent <= 10) {
				$size = "16px";
			} elseif($percent <= 20) {
				$size = "18px";
			} elseif($percent <= 40) {
				$size = "20px";
			} elseif($percent <= 80) {
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
		
		return $output;
	}

	public function popularTags($limit = 20) {
		$sessions = MeetingSession::get();

		$uniqueTagsArray = array();
		foreach($sessions as $session) {
			$tags = preg_split("*,*", trim($session->Tags));
			foreach($tags as $tag) {
				if($tag != "") {
					$tag = strtolower($tag);
					$uniqueTagsArray[$tag] = $tag;
				}
			}
		}

		$output = new ArrayList();
		$link = "";
		if($page = SessionsHolder::get()->First()) {
			$link = $page->Link('tag');
		}

		foreach($uniqueTagsArray as $tag) {
			$tagsList = $this->allTagsList();
			$count = $tagsList->Count();
			$filteredList = $tagsList->filter('Tag', $tag);
			$weight = $filteredList->Count();
			$percent = ($weight / $count) * 100;

			if($percent <= 5) {
				$size = "14px";
			} elseif($percent <= 10) {
				$size = "16px";
			} elseif($percent <= 20) {
				$size = "18px";
			} elseif($percent <= 40) {
				$size = "20px";
			} elseif($percent <= 80) {
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
		$output->sort('Weight', 'DESC');

		return new ArrayList(array_slice($output->items, 0, $limit));
	}

	public function allTagsList() {
		$sessions = MeetingSession::get();
		$tagsList = new ArrayList();
		foreach($sessions as $session) {
			$tags = preg_split("*,*", trim($session->Tags));
			foreach($tags as $tag) {
				if($tag != "") {
					$tag = strtolower($tag);
					$tagsList->push(new ArrayData(array(
						'Tag' => $tag
					)));
				}
			}
		}
		return $tagsList;
	}


}