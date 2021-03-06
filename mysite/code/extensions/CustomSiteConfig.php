<?php
/**
* Custom extension to Site Config
*
* @package FoIGF
*/
class CustomSiteConfig extends DataExtension {
	
    public static $db = array(
        'ViewCheck' => 'Boolean',
        'FacebookURL' => 'Text',
        'TwitterURL' => 'Text',
        'ShowRegional' => 'Boolean',
        'ShowOrganisers' => 'Boolean',
        'ShowFeatured' => 'Boolean'
    );

    public static $has_one = array(
        'ResearchGroup' => 'Group',
        'CurationGroup' => 'Group'
        );

    public function updateCMSFields(FieldList $fields) {
        $fields->addFieldToTab('Root.Main', new CheckboxField('ViewCheck', 'Turn on Session View count?'));
        $fields->addFieldToTab('Root.Main', new CheckboxField('ShowFeatured', 'Show Next Meeting on Home Page?'));
        $fields->addFieldToTab('Root.Main', new CheckboxField('ShowRegional', 'Show Regional and National IGF Page?'));
        $fields->addFieldToTab('Root.Main', new CheckboxField('ShowOrganisers', 'Show Organisers on Session pages?'));
        $fields->addFieldToTab('Root.Main', new TextField('FacebookURL', 'Facebook URL'));
        $fields->addFieldToTab('Root.Main', new TextField('TwitterURL', 'Twitter URL'));
        $fields->addFieldToTab('Root.ContentEnrichment', new TreeDropdownField('ResearchGroupID', 'Security Group to conduct Content Enrichment research'));
        $fields->addFieldToTab('Root.ContentEnrichment', new TreeDropdownField('CurationGroupID', 'Security Group to conduct Tag curation'));
    }
    
}
