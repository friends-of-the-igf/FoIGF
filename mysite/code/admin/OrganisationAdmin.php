<?php
/**
* Administration for Organisation
*
*/
class OrganisationAdmin extends ModelAdmin {

	public static $managed_models = array(
		'Organisation',
		
	);

	static $url_segment = 'organisations';

	static $menu_title = 'Organisations';

	static $model_importers = array();

}
