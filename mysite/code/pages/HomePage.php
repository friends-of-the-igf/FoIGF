<?php 
class HomePage extends Page{
	
}

class HomePage_Controller extends Page_Controller{





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

		for($i = 0; $i < 4; $i++){

			$list2 = new ArrayList();

			for($x = 0; $x < 3; $x++){
				$list2->push($x);
			}
			$list->push(new ArrayData(array('col' => $list2)));
		}
		return $list;
	}
}

// $sessions = Sessions::get()->sort('DESC', 'Created');
