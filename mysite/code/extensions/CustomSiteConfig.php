<?php
class CustomSiteConfig extends DataExtension {
	
    public static $db = array(
        'ViewCheck' => 'Boolean',
        'FacebookURL' => 'Text',
        'TwitterURL' => 'Text',
        'ShowRegional' => 'Boolean'
    );

    public function updateCMSFields(FieldList $fields) {
        $fields->addFieldToTab('Root.Main', new CheckboxField('ViewCheck', 'Turn on Session View count?'));
        $fields->addFieldToTab('Root.Main', new CheckboxField('ShowRegional', 'Show Regional and National IGF Page?'));
        $fields->addFieldToTab('Root.Main', new TextField('FacebookURL', 'Facebook URL'));
        $fields->addFieldToTab('Root.Main', new TextField('TwitterURL', 'Twitter URL'));
    }
    
}
