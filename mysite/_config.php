<?php
/**
*Base Configuration file for FoIGF
*
*
*/

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
Object::add_extension('ContentController', 'SearchExtension');
Member::set_unique_identifier_field('Username');

// fulltextsearch/solr

SearchUpdater::bind_manipulation_capture();

Solr::configure_server(isset($solr_config) ? $solr_config : array(
	'host' => 'localhost',
	'indexstore' => array(
		'mode' => 'file',
		'path' => BASE_PATH . '/.solr'
	),
	'extraspath' => Director::baseFolder() . '/mysite/data/solr/'
));

DataObject::add_extension('File', 'FileTextExtractable');
DataObject::add_extension('File', 'CustomFileExtension');