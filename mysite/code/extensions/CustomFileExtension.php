<?php
class CustomFileExtension extends DataExtension {

	// set index
	public static $indexes = array(
		"fulltext (Name, FileContentCache)"
    );

    // REQUIRED: object table must be set to MyISAM
	static $create_table_options = array(
	    'MySQLDatabase' => 'ENGINE=MyISAM'
	);

	public function onAfterWrite() {
		parent::onAfterWrite();
		if(!$this->owner->FileContentCache) {
			$this->owner->extractFileAsText(true);
		}
	}

}