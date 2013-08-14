<?php
class CustomMember extends DataExtension {

	public static $belongs_many_many = array(
		'MeetingSessions' => 'MeetingSession'
	);

}
