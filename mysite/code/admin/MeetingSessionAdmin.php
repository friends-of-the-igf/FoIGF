<?php
class MeetingSessionAdmin extends ModelAdmin {

	public static $managed_models = array(
		'MeetingSession'
	);

	static $url_segment = 'sessions';

	static $menu_title = 'Sessions';

	static $model_importers = array();

}