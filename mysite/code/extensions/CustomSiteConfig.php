<?php
class CustomSiteConfig extends DataExtension {
	
    public static $db = array(
        'ViewCheck' => 'Boolean'
    );

    public function updateCMSFields(FieldList $fields) {
        $fields->addFieldToTab('Root.Main', new CheckboxField('ViewCheck', 'Turn on Session View count?'));
    }
    
}
