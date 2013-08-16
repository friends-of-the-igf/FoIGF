<?php
class MeetingAdmin extends ModelAdmin {

	public static $managed_models = array(
		'Meeting',
		'Location',
		'Topic'
	);

	static $url_segment = 'meeting';

	static $menu_title = 'Meetings';

	static $model_importers = array();

}
