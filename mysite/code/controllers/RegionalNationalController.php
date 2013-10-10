<?php 

class RegionalNationalController extends Page_Controller{

	static $allowed_actions = array(
		'getMeetingsData'
		);

	public function init(){

		Requirements::javascript('themes/igf/javascript/regionalmeetings.js');

		parent::init();
	}

	public function getRegions(){
		return RNRegion::get();
	}

	public function getMeetingsData(){

		$type_reg = RNType::get()->filter(array('Title' => 'Regional'))->First();
		if($type_reg != null){
			$reg_id = $type_reg->ID;
		}
		$type_nat = RNType::get()->filter(array('Title' => 'National'))->First();
		if($type_nat != null){
			$nat_id = $type_nat->ID;
		}
		$other_meetings = RNMeeting::get()->exclude('TypeID', array(1, 2))->Sort('Title', 'ASC');

		if(array_key_exists('id', $_POST)){
			if(isset($_POST['id']) && $_POST['id'] != null){

				$region = RNRegion::get()->byID($_POST['id']);
				
				$meetings = $region->Meetings()->Sort('Title', 'ASC');
				
				$reg_count = $meetings->filter(array('TypeID' => $reg_id))->Count();
				$nat_count = $meetings->filter(array('TypeID' => $nat_id))->Count();

				$r_n_meetings = $meetings->filter('TypeID', array($reg_id, $nat_id));

				$data = array(
				'Region' => $region->Title,
				'RegCount' => $reg_count,
				'NatCount' => $nat_count,
				'Meetings' =>$r_n_meetings,
				'OtherMeetings' => $other_meetings
				);

				$meetingData =  new ArrayData($data);

				return $meetingData->renderWith('RegionalMeetings');
			}
		} else {
			$meetings =  RNMeeting::get()->Sort('Title', 'ASC');
			
			$reg_count = $meetings->filter(array('TypeID' => $reg_id))->Count();
			
			$nat_count = $meetings->filter(array('TypeID' => $nat_id))->Count();

			$r_n_meetings = $meetings->filter('TypeID', array($reg_id, $nat_id));

			$data = array(
				'Region' => 'all over the world',
				'RegCount' => $reg_count,
				'NatCount' => $nat_count,
				'Meetings' => $r_n_meetings,
				'OtherMeetings' => $other_meetings
				);

			return new ArrayData($data);
		}
		
	}


	

}