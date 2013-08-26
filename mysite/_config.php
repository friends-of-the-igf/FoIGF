<?php

global $project;
$project = 'mysite';

global $database;
$database = 'STW_igf';

require_once('conf/ConfigureFromEnv.php');

MySQLDatabase::set_connection_charset('utf8');
SSViewer::set_theme('igf');
if(class_exists('SiteTree')) SiteTree::enable_nested_urls();

// Admin Email
Email::setAdminEmail("ben@stripetheweb.com");

// Don't use compass when not required
// if ( !isset($_GET['flush']) ) {
// 	if(class_exists('SiteTree')) {
// 		Object::remove_extension('SiteTree', 'Compass_RebuildDecorator');
// 	}
// 	if(class_exists('LeftAndMain')) {
// 		Object::remove_extension('LeftAndMain', 'Compass_RebuildDecorator');
// 	}
// }

DataObject::add_extension('SiteConfig', 'CustomSiteConfig');
DataObject::add_extension('Member', 'CustomMember');
Object::useCustomClass('SearchForm', 'CustomSearchForm');
Member::set_unique_identifier_field('Username');


FulltextSearchable::enable();

CustomSearchFilter::set_search_objects(array('Meeting', 'MeetingSession', 'Location', 'Topic', 'Type', 'Member'));
CustomSearchForm::set_return_objects(array('Location', 'Topic', 'Type', 'Member'));

Object::add_extension('MeetingSession', "FulltextSearchable('Title', 'Date', 'Tags', 'Content', 'TranscriptContent')");
Object::add_extension('Meeting', "FulltextSearchable('Title')");
Object::add_extension('Location', "FulltextSearchable('City', 'Country')");
Object::add_extension('Topic', "FulltextSearchable('Name')");
Object::add_extension('Type', "FulltextSearchable('Name')");
Object::add_extension('Member', "FulltextSearchable('FirstName', 'Surname')");
