<?php 
class HomePage extends Page {
	
}
class HomePage_Controller extends Page_Controller {

	public function getSpeakers(){
		$list = new ArrayList();
		for($i = 0; $i < 12; $i++){
			$y = array();
			$list->push(new ArrayData($y));
		}
		return $list;
	}

	public function getSessions(){
		$list = new ArrayList();

		$col1 = new ArrayList();
		$col2 = new ArrayList();
		$col3 = new ArrayList();
		$col4 = new ArrayList();

		$i = 0;
		$j = 1;
		
		while ($i <= 11) {
			$session = MeetingSession::get()->sort('Created', 'DESC')->limit(1, $i)->first();
			if($session) {
				switch ($j) {
					case 1:
						$col1->push($session);
						$j++;
						break;
					case 2:
						$col2->push($session);
						$j++;
						break;
					case 3:
						$col3->push($session);
						$j++;
						break;
					case 4:
						$col4->push($session);
						$j = 1;
						break;
				}
			}
			$i++;
		}

		$list->push(new ArrayData(array('Columns' => $col1)));
		$list->push(new ArrayData(array('Columns' => $col2)));
		$list->push(new ArrayData(array('Columns' => $col3)));
		$list->push(new ArrayData(array('Columns' => $col4)));

		return $list;
	}

	public function getTopics(){
		return Topic::get()->sort('Name', 'ASC');
	}

	public function getFeaturedMeeting(){
		return Meeting::get()->sort('StartDate', 'DESC')->First();
	}
}
