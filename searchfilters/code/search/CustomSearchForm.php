<?php

class CustomSearchForm extends SearchForm
{
	
	private $customSearchClasses = array();
	static $returnClasses = array();
	
	private static function add_search_fields($object, $record)
	{
		$object->setField('Title',$record['Title']);
		$object->setField('Content',$record['Content']);
		return $object;
	}

	public static function set_return_objects($classes) {
		self::$returnClasses = $classes;
	}

	public static function get_return_objects() {
		return self::$returnClasses;
	}
	
	public function addSearchableClasses($classes)
	{
		$map = array();
		foreach($classes as $class) {
			$SNG = singleton($class);
			$map[$class]['Fields'] = $SNG->stat('searchable_fields');
			$map[$class]['Title'] = $SNG->stat('search_heading');
			$map[$class]['Content'] = $SNG->stat('search_content');
		}
		$this->customSearchClasses = $map;
	}
	
	function classesToSearch($classes) {
		$this->classesToSearch = $classes;
	}

	public function getCustomClassesToSearch() {
		return array_merge(array('SiteTree', 'File'), CustomSearchFilter::get_search_objects());
	}
	
	public function getResults($pageLength = null, $data = null){
	
	 	// legacy usage: $data was defaulting to $_REQUEST, parameter not passed in doc.silverstripe.org tutorials
		if(!isset($data) || !is_array($data)) $data = $_REQUEST;
		
		// set language (if present)
		if(class_exists('Translatable') && singleton('SiteTree')->hasExtension('Translatable') && isset($data['locale'])) {
			$origLocale = Translatable::get_current_locale();
			Translatable::set_current_locale($data['locale']);
		}
	
		$keywords = $data['Search'];
		$filters = null;
		if(isset($data['SearchFilters']) && $data['SearchFilters'] != '') {
			$filters = $data['SearchFilters'];
		}
		$classes = array('SiteTree');

	 	$andProcessor = create_function('$matches','
	 		return " +" . $matches[2] . " +" . $matches[4] . " ";
	 	');
	 	$notProcessor = create_function('$matches', '
	 		return " -" . $matches[3];
	 	');

	 	$keywords = preg_replace_callback('/()("[^()"]+")( and )("[^"()]+")()/i', $andProcessor, $keywords);
	 	$keywords = preg_replace_callback('/(^| )([^() ]+)( and )([^ ()]+)( |$)/i', $andProcessor, $keywords);
		$keywords = preg_replace_callback('/(^| )(not )("[^"()]+")/i', $notProcessor, $keywords);
		$keywords = preg_replace_callback('/(^| )(not )([^() ]+)( |$)/i', $notProcessor, $keywords);

		$keywords = $this->addStarsToKeywords($keywords);

		if(!$pageLength) $pageLength = $this->pageLength;
		$start = isset($_GET['start']) ? (int)$_GET['start'] : 0;

		$filterClass = null;
		$filter = null;
		if(isset($filters) && $filters != '') {
			if($filters = CustomSearchFilter::get()->byID($filters)) {
				if($filters->SearchPageID && $filters->SearchPageID != 0) {
					$filter = $filters->SearchPageID;
				} else {
					$filterClass = $filters->SearchClass;
				}
			}
		}

		if(isset($filter) && $filter != '') {
			if(strpos($keywords, '"') !== false || strpos($keywords, '+') !== false || strpos($keywords, '-') !== false || strpos($keywords, '*') !== false) {
				$results = DB::getConn()->searchEngine($classes, $keywords, $start, $pageLength, "\"Relevance\" DESC", "ParentID = " . $filter, true);
			} else {
				$results = SiteTree::get()->where("ParentID = " . $filter);
				$results = new PaginatedList($results);
				$results->setPageStart($start);
				$results->setPageLength($pageLength);
			}
		} elseif($filterClass) {
			if(isset($keywords) && $keywords != '') {
				$results = $this->searchEngine(array($filterClass), $keywords, $start, $pageLength, "\"Relevance\" DESC", "", true);
			} else {
				$searchList = $filterClass::get();
				$results = new PaginatedList($searchList);
				$results->setPageStart($start);
				$results->setPageLength($pageLength);
			}
		} elseif(strpos($keywords, '"') !== false || strpos($keywords, '+') !== false || strpos($keywords, '-') !== false || strpos($keywords, '*') !== false) {
			$results = $this->searchEngine($this->getCustomClassesToSearch(), $keywords, $start, $pageLength, "\"Relevance\" DESC", "", true);
		} else {
			$results = $this->searchEngine($this->getCustomClassesToSearch(), $keywords, $start, $pageLength);
		}
		
		// filter by permission
		if($results) foreach($results as $result) {
			if(!$result->canView()) $results->remove($result);
		}
		
		// reset locale
		if(class_exists('Translatable') && singleton('SiteTree')->hasExtension('Translatable') && isset($data['locale'])) {
			Translatable::set_current_locale($origLocale);
		}

		return $results;
	}


	public function searchEngine($classesToSearch, $keywords, $start, $pageLength, $sortBy = "Relevance DESC", $extraFilter = "", $booleanSearch = false, $alternativeFileFilter = "", $invertedMatch = false) {
	 	$keywords = Convert::raw2sql($keywords);
		$htmlEntityKeywords = htmlentities($keywords, ENT_NOQUOTES, 'UTF-8');

		$extraFilters = array();
		foreach($classesToSearch as $class) {
			$extraFilters[$class] = '';

			if($class == "SiteTree") $extraFilters['SiteTree'] .= " AND ShowInSearch <> 0";
		}

	 	if($booleanSearch) $boolean = "IN BOOLEAN MODE";

		$limit = $start . ", " . (int) $pageLength;

		$notMatch = $invertedMatch ? "NOT " : "";

		// match
		if($keywords) {
			$match = array();
			foreach($classesToSearch as $class) {
				if($class == "SiteTree") {
					$match['SiteTree'] = "
						MATCH (Title, MenuTitle, Content, MetaTitle, MetaDescription, MetaKeywords) AGAINST ('$keywords' $boolean)
						+ MATCH (Title, MenuTitle, Content, MetaTitle, MetaDescription, MetaKeywords) AGAINST ('$htmlEntityKeywords' $boolean)
					";
				} elseif($class == "File") {
					$match['File'] = "MATCH (Filename, Title, Content) AGAINST ('$keywords' $boolean) AND ClassName = 'File'";
				} else {
					$fields = implode(', ', $class::$searchable_fields);
					$match[$class] = "MATCH ($fields) AGAINST ('$keywords' $boolean)";
				}
			}

			// We make the relevance search by converting a boolean mode search into a normal one
			$relevanceKeywords = str_replace(array('*','+','-'),'',$keywords);
			$htmlEntityRelevanceKeywords = str_replace(array('*','+','-'),'',$htmlEntityKeywords);

			$relevance = array();
			foreach($classesToSearch as $class) {
				if($class == "SiteTree") {
					$relevance['SiteTree'] = "MATCH (Title, MenuTitle, Content, MetaTitle, MetaDescription, MetaKeywords) AGAINST ('$relevanceKeywords') + MATCH (Title, MenuTitle, Content, MetaTitle, MetaDescription, MetaKeywords) AGAINST ('$htmlEntityRelevanceKeywords')";
				} elseif($class == "File") {
					$relevance['File'] = "MATCH (Filename, Title, Content) AGAINST ('$relevanceKeywords')";
				} else {
					$fields = implode(', ', $class::$searchable_fields);
					$relevance[$class] = "MATCH ($fields) AGAINST ('$relevanceKeywords')";
				}
			}
		} else {
			$match = array();
			$relevance = array();
			foreach($classesToSearch as $class) {
				$relevance[$class] = 1;
				$match[$class] = "1 = 1";
			}
		}

		// Generate initial DataLists and base table names
		$lists = array();
		$baseClasses = array();
		$fullList = new ArrayList();
		foreach($classesToSearch as $class) {
			$dataList = DataList::create($class)->where($notMatch . $match[$class] . $extraFilters[$class], "");
			$lists[$class] = $dataList;
			$baseClasses[$class] = '';
			$fullList->push($dataList);
		}

		// Make column selection lists
		$select = array();
		foreach($classesToSearch as $class) {
			if($class == 'SiteTree') {
				$select[$class] = array("ClassName","SiteTree.\"ID\"","ParentID","Title","MenuTitle","URLSegment","Content","LastEdited","Created","Filename" => "_utf8''", "Name" => "_utf8''", "Relevance" => $relevance['SiteTree'], "CanViewType");
			} elseif($class == 'File') {
				$select[$class] = array("ClassName","File.\"ID\"","ParentID" => "_utf8''","Title","MenuTitle" => "_utf8''","URLSegment" => "_utf8''","Content","LastEdited","Created","Filename","Name", "Relevance" => $relevance['File'], "CanViewType" => "NULL");
			} else {
				$select[$class] = array_merge($class::$return_fields, array("Relevance" => $relevance[$class], "ClassName"));
			}	
		}

		// Process and combine queries
		$querySQLs = array();
		$totalCount = 0;
		$objects = array();
		foreach($lists as $class => $list) {
			$query = $list->dataQuery()->query();

			// There's no need to do all that joining
			$query->setFrom($class);
			$query->setSelect($select[$class]);
			$query->setOrderBy(array());

			$totalCount += $query->unlimitedRowCount();
			$records = DB::query($query->sql());

			foreach($records as $record) {
				$objects[] = new $record['ClassName']($record);
			}
		}

		$list = new ArrayList($objects);
		$list->sort('Relevance');

		foreach($list as $item) {
			if(in_array($item->ClassName, $this->get_return_objects())) {
				$location = Location::get()->ByID($item->ID);
				$list->replace($item, $location);
			}
		}

		$list = new PaginatedList($list);
		$list->setPageStart($start);
		$list->setPageLength($pageLength);
		$list->setTotalItems($totalCount);
		$list->setLimitItems(true);

		return $list;
	}

}


?>