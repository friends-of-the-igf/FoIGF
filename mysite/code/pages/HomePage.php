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
		for($i = -1; $i < 12; $i += 4){
			$columns = new ArrayList();
			if($i == -1){
				$col = MeetingSession::get()->sort('Created', 'DESC')->limit(3);
			} else
			{
				$col = MeetingSession::get()->sort('Created', 'DESC')->limit(3, $i);
			}
			foreach($col as $session){
				$columns->push($session);
			}
			$list->push(new ArrayData(array('Columns' => $columns)));
		}
		return $list;
	}

	public function sessionLink(){
		return SessionsHolder::get()->First()->Link();
	}
}


