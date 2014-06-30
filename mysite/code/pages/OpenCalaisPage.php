<?php

class OpenCalaisPage extends Page{
	
	/**
	 * This automatically creates a OpenCalaisPage whenever dev/build
	 * is invoked and there is no page on the site with OpenCalaisPage
	 * applied to it.
	 */
	function requireDefaultRecords() {
		parent::requireDefaultRecords();

		$page = OpenCalaisPage::get();

		if($page->count() == 0) {
			$page = OpenCalaisPage::create();
			$page->Title = 'Open Calais';
			$page->URLSegment = 'opencalais';
			$page->ShowInMenus = 0;
			$page->writeToStage('Stage');
			$page->publish('Stage', 'Live');

			DB::alteration_message('Open Calais Page \'Open Calais\' created', 'created');
		}
 	}

}

class OpenCalaisPage_Controller extends Page_Controller{

	protected $meetingSession;

	public static $allowed_actions = array(
		'test',
		'openCalaisSession',
		'processSession',
		'sortColumn',
		'BatchIDForm'
	);

	public function init(){
		parent::init();

		$this->meetingSession = (isset($_GET['ID'])) ? MeetingSession::get()->byID($_GET['ID']) : false ;

		Requirements::javascript('themes/igf/javascript/opencalaispage.js');
	}

	public function getMeetingSession(){
		return $this->meetingSession;
	}

	public function processSession($meetingSession = null){
		$session =  ($meetingSession == null) ? $this->meetingSession : $meetingSession;
		$returnData = array();
		$ocs = new OpenCalaisService();
		$areas = (isset($_GET['area'])) ? $_GET['area'] : array('Agenda','Transcripts','Report','Proposal');
		foreach($areas as $area){
			$content = false;
			if($area == 'Agenda'){
				$content = $session->Content;
			} else if($area == 'Transcripts') {
				if($session->Transcripts()->filter('TranscriptType', 'Text')->Count() != 0){
					$content = $session->Transcripts()->filter('TranscriptType', 'Text')->First()->Content;
				} else {
					$returnData[$area] = 'Transcript was not available in a format that can be processed or does not exist. You can only process text based Transcripts.';
				}
			} else if($area == 'Proposal'){
				if($session->ProposalType == 'Text' && strlen($session->ProposalContent) > 0){
					$content = $session->ProposalContent;
				} else {
					$returnData[$area] = 'Proposal was not available in a format that can be processed or does not exist. You can only process text based Proposals.';
				}
			} else if($area == 'Report') {
				if($session->ReportType == 'Text' && strlen($session->ReportContent) > 0){
					$content = $session->ReportContent;
				} else {
					$returnData[$area] = 'Report was not available in a format that can be processed or does not exist. You can only process text based Reports.';
				}
			}
			if($content){
				$returnData[$area] = $ocs->processContent($content);	
			}		
		}

		$areaList = new ArrayList();

		foreach($returnData as $area => $entityTypes){
			$areaData['Title'] = preg_replace("/(?<=[a-zA-Z])(?=[A-Z])/", " ", $area);
			$areaData['Value'] = $area;
			$typeList = new ArrayList();
			if(is_array($entityTypes)){
				foreach($entityTypes as $type => $entities){
					$typeData['Title'] = preg_replace("/(?<=[a-zA-Z])(?=[A-Z])/", " ", $type);
					$typeData['Value'] = $type;
					$entityList = new ArrayList();
					if(is_array($entities)){
						foreach($entities as $entity){
							$entityList->push(new ArrayData($entity));
						}
						$typeData['Entities'] = $entityList;
					} else {
						$typeData['Entities'] = false;
						$typeData['Message'] = $entities;
					}
					$typeList->push(new ArrayData($typeData));
				}
				$areaData['Types'] = $typeList;
			} else {
				$areaData['Types'] = false;
				$areaData['Message'] = $entityTypes;
			}	
			$areaList->push(new ArrayData($areaData));
		}
		Session::set('Entities', $areaList);
	
		return $areaList;
	}

	public function openCalaisSession(){
		$areaList = $this->processSession();
		return $this->customise(array('Areas' => $areaList));
	}

	public function sortColumn(){
		$dir = $_POST['dir'];
		$field = ($_POST['field'] == 'Name' ? 'Value' : $_POST['field']);
		$type = $_POST['type'];
		$area = $_POST['area'];
		$list = Session::get('Entities');
		$sorted = $list->find('Value', $area)->Types->find('Value', $type)->Entities->sort($field, $dir);
		if($type == 'SocialTags'){
			return $this->customise(array('Entities' => $sorted))->renderWith('TagTable');
		} else if($type == 'Topics'){
			return $this->customise(array('Entities' => $sorted))->renderWith('TopicTable');
		} else {
			return $this->customise(array('Entities' => $sorted))->renderWith('EntityTable');
		}
	}

	public function BatchIDForm(){
		$fields = new FieldList();

		$fields->push($field = new TextField('IDList', 'ID List'));
		$field->setAttribute('placeholder', 'eg. 123,456,789');

		$actions = new FieldList($button = new FormAction('processIDList', 'Process'));
		$button->addExtraClass('btn');
		$button->addExtraClass('btn-primary');


		$validator = new RequiredFields('IDList');

		return new Form($this, 'BatchIDForm', $fields, $actions, $validator);
	}

	public function processIDList($data, $form){
	 	$idArray = array_filter(array_map('trim', explode(',', $data['IDList'])));
	 	$recordHolder = new ArrayList();
	 	foreach($idArray as $id){
	 		$meetingSession = MeetingSession::get()->byID($id);
	 		if($meetingSession instanceOf MeetingSession){
	 			$entities = $this->processSession($meetingSession);

	 			$recordHolder->push(new ArrayData(array(
	 				'ID' => $id,
	 				'Entities' => $entities
	 				)
	 			));
	 		}
	 	}
	 	
	 	return $this->customise(array('BatchProcess' => true, 'Records' => $recordHolder));
	}

	public function test(){
		$ocs = new OpenCalaisService();
		$ocs->setContent("April 7 (Bloomberg) . Yahoo! Inc., the Internet company that snubbed a $44.6 billion takeover bid from Microsoft Corp., may drop in Nasdaq trading after the software maker threatened to cut its bid if directors fail to give in soon. If Yahoo.s directors refuse to negotiate a deal within three weeks, Microsoft plans to nominate a board slate and take its case to investors, Chief Executive Officer Steve Ballmer said April 5 in a statement. He suggested the deal.s value might decline if Microsoft has to take those steps. The ultimatum may send Yahoo Chief Executive Officer Jerry Yang scrambling to find an appealing alternative for investors to avoid succumbing to Microsoft, whose bid was a 62 percent premium to Yahoo.s stock price at the time. The deadline shows Microsoft is in a hurry to take on Google Inc., which dominates in Internet search, said analysts including Canaccord Adams.s Colin Gillis.");
		$result = $ocs->processContent();
		return Debug::dump($result);
	}
}