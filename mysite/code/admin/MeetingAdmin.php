<?php
/**
* Administration for Meetings
*
* @package FoIGF
*/
class MeetingAdmin extends ModelAdmin {

	public static $managed_models = array(
		'Meeting',
		'Location'
	);

	static $url_segment = 'meeting';

	static $menu_title = 'Meetings';

	static $model_importers = array();

}
