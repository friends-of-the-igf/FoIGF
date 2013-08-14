<?php
class MeetingSession extends DataObject {

	public static $db = array(
		'Title' => 'Text',
		'Date' => 'Date',
		'Tags' => 'Text',
		'Views' => 'Int',
		'Content' => 'HTMLText',
		'YouTubeID' => 'VarChar'
	);

	public static $has_one = array(
		'Transcript' => 'File',
		'Proposal' => 'File'
	);

	public static $summary_fields = array(
		'Title',
		'Date'
	);

	public function getCMSFields() {
		$fields = new FieldList();

		$fields->push(new TextField('Title', 'Title'));
		$fields->push($date = new DateField('Date', 'Date'));
		$date->setConfig('showcalendar', true);
		$fields->push(new TextField('Tags', 'Tags (comma seperated)'));
		$fields->push(new HTMLEditorField('Content', 'Content'));
		$fields->push(new TextField('YouTubeID', 'YouTube ID (can be ID or full URL)'));

		return $fields;
	}

	public function Link($action = null) {
		return Controller::join_links('session', $this->ID, $action);
	}

	public function TagsCollection() {
		$tags = preg_split(" *, *", trim($this->Tags));
		$output = new ArrayList();
		
		$link = "";
		if($page = SessionsHolder::get()->First()) {
			$link = $page->Link('tag');
		}

		foreach($tags as $tag) {
			$output->push(new ArrayData(array(
				'Tag' => $tag,
				'Link' => $link . '/' . urlencode($tag),
				'URLTag' => urlencode($tag)
			)));
		}
		
		if($this->Tags) {
			return $output;
		}
	}

}
