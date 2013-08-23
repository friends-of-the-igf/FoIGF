silverstripe-searchfilters
=========================

By Stripe the Web
------------------

Usage
-----
Fulltext Searchable must be enabled in _config.php
FulltextSearchable::enable();

define objects to be searchable in _config.php with 
CustomSearchFilter::set_search_objects(array('class_1', 'class_2'));

add full text to the searchable class fields
Object::add_extension('class_1', "FulltextSearchable('field_1','field_2','field_3')");

add static variables to the searchable class to define search parameters
	
	// fields to search
	static $searchable_fields = array(
		'field_1',
		'field_2',
		'field_3',
	);

	// fields to return
	static $return_fields = array(
		'field_1',
		'field_2',
		'field_3',
		'field_4'
	);

	// set index
	public static $indexes = array(
		"fulltext (field_1, field_2, field_3)"
    );

    // REQUIRED: object table must be set to MyISAM
	static $create_table_options = array(
	    'MySQLDatabase' => 'ENGINE=MyISAM'
	);

add URLSegment if required to link to
	
	$db = array {
		'URLSegment' => 'Varchar(255)'
	}

	// fields to return
	static $return_fields = array(
		...
		'URLSegment'
	);

	public function onAfterWrite() {
		parent::onAfterWrite();

		if(!$this->URLSegment) {
			$this->URLSegment = $this->link();
			$this->write();
		}
	}

modify search results template to account for DataObjects by using <% if ClassName == class_1 %><% end_if %> conditions