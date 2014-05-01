<?php
/**
* Administration for FilterSubmissions
*
* @package FoIGF
*/
class FilterSubmissionAdmin extends ModelAdmin {

	public static $managed_models = array(
		'FilterSubmission'
	);

	static $url_segment = 'filtersubmissions';

	static $menu_title = 'Filter Submissions';

	static $model_importers = array();

}
