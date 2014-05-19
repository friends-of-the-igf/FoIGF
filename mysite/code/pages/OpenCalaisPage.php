<?php

class OpenCalaisPage extends Page{
	

}

class OpenCalaisPage_Controller extends Page_Controller{

	public static $allowed_actions = array(
		'test',
		'OpenCalaisForm'
	);

	public function OpenCalaisForm(){

		$sessions = MeetingSession::get()->map();

		$fields = new FieldList(array(
			new DropdownField('Session', 'Choose a session:', $sessions),
			new CheckboxSetField('ContentSelection', 'Select what areas of content you would like to process:', array('Transcripts' => 'Transcript', 'Agenda' =>  'Agenda', 'Proposal' =>  'Proposal', 'Report' =>  'Report'))
			)
		);

		$actions = new FieldList(new FormAction('processSession', 'Extract Entities'));

		return new Form($this, 'OpenCalaisForm', $fields, $actions);
	}

	public function processSession($data, $form){
		$session =  MeetingSession::get()->byID($data['Session']);
		$returnData = array();
		$ocs = new OpenCalaisService();
		foreach($data['ContentSelection'] as $area){
			if($area == 'Agenda'){
				$content = $session->Content;
			} else if($area == 'Transcripts') {
				if($session->Transcripts()->filter('TranscriptType', 'Text')->Count() != 0){
					$content = $session->Transcripts()->filter('TranscriptType', 'Text')->First()->Content;
				} else {
					$returnData[$area] = 'Transcript was not available in a format that can be processed or does not exist. You can only process text based Transcripts.';
					break;
				}
			} else if($area == 'Proposal'){
				if($session->ProposalType == 'Text' && strlen($session->ProposalContent) > 0){
					$content = $session->ProposalContent;
				} else {
					$returnData[$area] = 'Proposal was not available in a format that can be processed or does not exist. You can only process text based Proposals.';
					break;
				}
			} else {
				if($session->ReportType == 'Text' && strlen($session->ReportContent) > 0){
					$content = $session->ReportContent;
				} else {
					$returnData[$area] = 'Report was not available in a format that can be processed or does not exist. You can only process text based Reports.';
					break;
				}
			}

			//TO DO: Move this process to part of the service class
			
			Debug::dump($ocs->processContent($content));
			// $ocs->processContent($content);
			return;
			
		}

		// return Debug::dump($returnData);

	}


	public function test(){
		$ocs = new OpenCalaisService();
		$ocs->setContent("April 7 (Bloomberg) . Yahoo! Inc., the Internet company that snubbed a $44.6 billion takeover bid from Microsoft Corp., may drop in Nasdaq trading after the software maker threatened to cut its bid if directors fail to give in soon. If Yahoo.s directors refuse to negotiate a deal within three weeks, Microsoft plans to nominate a board slate and take its case to investors, Chief Executive Officer Steve Ballmer said April 5 in a statement. He suggested the deal.s value might decline if Microsoft has to take those steps. The ultimatum may send Yahoo Chief Executive Officer Jerry Yang scrambling to find an appealing alternative for investors to avoid succumbing to Microsoft, whose bid was a 62 percent premium to Yahoo.s stock price at the time. The deadline shows Microsoft is in a hurry to take on Google Inc., which dominates in Internet search, said analysts including Canaccord Adams.s Colin Gillis.");
		$result = $ocs->processContent();
		return Debug::dump($result);
	}
}