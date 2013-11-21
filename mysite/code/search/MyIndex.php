<?php

class MyIndex extends SolrIndex {

	function init() {
		$this->addClass('Page');
		$this->addClass('Meeting');
		$this->addClass('MeetingSession');
		$this->addClass('File');
		$this->addClass('Location');
		$this->addClass('Topic');
		$this->addClass('Type');
		$this->addClass('Member');

		$this->addFulltextField('Title');
		$this->addFulltextField('Content');
		$this->addFulltextField('Tags');
		$this->addFulltextField('TranscriptContent');
		$this->addFulltextField('ProposalContent');
		$this->addFulltextField('Location.City');
		$this->addFulltextField('Location.Country');
		$this->addFulltextField('Type.Name');
		$this->addFulltextField('Topic.Name');
		$this->addFulltextField('FirstName');
		$this->addFulltextField('Surname');

		// files
		$this->addFulltextField('Transcript.FileContentCache'); // will return only related files with name Transcript
		$this->addFulltextField('FileContentCache'); // will return all files
	}

}
