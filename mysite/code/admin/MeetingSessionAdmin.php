<?php
class MeetingSessionAdmin extends ModelAdmin {

	public static $managed_models = array(
		'MeetingSession',
		'Type',
		'Topic'
	);

	static $url_segment = 'sessions';

	static $menu_title = 'Sessions';

	static $model_importers = array();

}
