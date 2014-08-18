<?php
class CalaisSiteConfig extends DataExtension {
	
    public static $db = array(
        'OpenCalaisAPIKey' => 'Varchar(255)'
    );

    public function updateCMSFields(FieldList $fields) {
        $fields->addFieldToTab('Root.OpenCalais', new TextField('OpenCalaisAPIKey', 'Open Calais API Key'));
    }
    
}
