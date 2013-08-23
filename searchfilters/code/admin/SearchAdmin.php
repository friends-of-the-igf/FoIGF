<?php

class SearchAdmin extends ModelAdmin {

	public static $url_segment = 'search-filters';
	public static $menu_title = 'Search Filters';
	public static $managed_models = array('CustomSearchFilter');
}