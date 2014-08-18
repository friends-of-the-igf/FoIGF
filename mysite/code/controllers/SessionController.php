<?php
/**
* Controller to display Meeting Sessions
*
* @package FoIGF
*/
class SessionController extends Page_Controller {
	
	public static $url_handlers = array(
		'$ID!/$Action' => 'handleAction'
	);

	public static $allowed_actions = array(
        'TagForm',
        'saveTags',
        'getTags',
        'OpenCalaisForm'
	);

	protected $meetingsession = null;

	public function init() {
		parent::init();
		
		Requirements::javascript('themes/igf/javascript/sessioncontroller.js');
		Requirements::javascript('themes/igf/thirdparty/bootstrap-typeahead.js');

		$id = (int)$this->request->param('ID');
		if($meetingsession = MeetingSession::get()->ByID($id)) {

			// view counter
			$config = SiteConfig::current_site_config();
			if($config->ViewCheck) {
				if($viewed_sessions = Session::get('ViewedSessions')) {
					if(!in_array($meetingsession->ID, $viewed_sessions)) {
						$viewed_sessions[] = $meetingsession->ID;
						Session::set('ViewedSessions', $viewed_sessions);
						$meetingsession->Views = $meetingsession->Views + 1;
						$meetingsession->write();
					}
				} else {
					Session::set('ViewedSessions', array($meetingsession->ID));
					$meetingsession->Views = $meetingsession->Views + 1;
					$meetingsession->write();
				}
			}
			Session::set('CurrentSession', $meetingsession);
			$this->meetingsession = $meetingsession;
		} else {
			if($this->request->param('Action') == 'SearchForm' || $this->request->param('Action') == 'TagForm' || $this->request->param('Action') == 'getTags' || $this->request->param('Action') == 'OpenCalaisForm'){
				return;
			}else{
				return $this->httpError(404);
			}
		}
	}

	/**
	 * Gets current MeetingSession
	 * 
	 * @return MeetingSession.
	 */
	public function getMeetingSession() {
		return $this->meetingsession;
	}
	
	/**
	 * Gets ClassName for the Controller
	 * 
	 * @return String.
	 */
	public function getClassName() {
		return 'SessionController';
	}

	/**
	 * Gets Titile of current Meeting Session
	 * 
	 * @return String.
	 */
	public function Title(){
		if($this->meetingsession){
			return $this->meetingsession->Title;
		}
	}


	public function OpenCalaisForm(){

		$fields = new FieldList(array(
			new CheckboxSetField('ContentSelection', 'Select what areas of content you would like to process:', array('Transcripts' => 'Transcript', 'Agenda' =>  'Agenda', 'Proposal' =>  'Proposal', 'Report' =>  'Report')),	
			)
		);
		if($this->meetingsession){
			$fields->push(new HiddenField('MeetingSessionID', 'MeetingSessionID', $this->meetingsession->ID));
		}

		$required = new RequiredFields(array('ContentSelection'));

		$actions = new FieldList(new FormAction('processSession', 'Extract Entities'));

		return new Form($this, 'OpenCalaisForm', $fields, $actions, $required);
	}

	public function processSession($data, $form){
		$id = $data['MeetingSessionID'];

		$page = OpenCalaisPage::get()->First();
		if($page){
			$link = $page->Link().'openCalaisSession?ID='.$id;
			foreach($data['ContentSelection'] as $area){
				$link.='&area[]='.$area;
			}
			return $this->redirect($link);	
		}

	}


}
