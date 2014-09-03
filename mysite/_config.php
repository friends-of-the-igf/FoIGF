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

define('COOKIE_SALT', 'xFV-LkZ*0Z+r_xfJG8=UktJ~Tkv92uSEXVFQm74shXuek34-NwaBBcQI|0pl');

MySQLDatabase::set_connection_charset('utf8');
SSViewer::set_theme('igf');
if(class_exists('SiteTree')) SiteTree::enable_nested_urls();

// Admin Email
Email::setAdminEmail("dylan@stripetheweb.com");

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

if(class_exists('Solr')) {
    $extrasPath = BASE_PATH . '/mysite/data/solr/';
    if(!file_exists($extrasPath)) {
        $extrasPath = BASE_PATH . '/fulltextsearch/conf/solr/3/extras';
    }
    Solr::configure_server(array(
        'host' => defined('SOLR_SERVER') ? SOLR_SERVER : 'localhost',
        'port' => defined('SOLR_PORT') ? SOLR_PORT : 8983,
        'path' => defined('SOLR_PATH') ? SOLR_PATH : '/solr/',
        'indexstore' => array(
            'mode' => defined('SOLR_MODE') ? SOLR_MODE : 'file',
            'auth' => defined('SOLR_AUTH') ? SOLR_AUTH : NULL,
            'path' => defined('SOLR_INDEXSTORE_PATH') ? SOLR_INDEXSTORE_PATH : BASE_PATH . '/.solr',
            'remotepath' => defined('SOLR_REMOTE_PATH') ? SOLR_REMOTE_PATH : null
        ),
        'extraspath' => $extrasPath
    ));
}

DataObject::add_extension('File', 'FileTextExtractable');
DataObject::add_extension('File', 'CustomFileExtension');