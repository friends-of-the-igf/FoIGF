<?php 

class ContactPage extends Page{

	static $db = array(
		'Email' => 'Text',
		'SuccessMessage' => 'HTMLText'
	);

	public function getCMSFields(){
		$fields = parent::getCMSFields();

		$fields->addFieldToTab('Root.Form', new EmailField('Email', 'Email'));
		$fields->addFieldToTab('Root.Form', new HTMLEditorField('SuccessMessage', 'Message (on success)'));

		return $fields;
	}
	
}

class ContactPage_Controller extends Page_Controller{

	static $allowed_actions = array(
		'ContactForm'
	);

	public function ContactForm() {
		if(!$this->Email) {
			return false;
		}

		$fields = new FieldList();

		$fields->push(new TextField('Name', 'Name'));
		$fields->push(new EmailField('Email', 'Email'));
		$fields->push(new TextareaField('Message', 'Message'));

		$actions = new FieldList();
		$actions->push($button = new FormAction('doContactForm', 'Send'));
		$button->addExtraClass('btn');
		$button->addExtraClass('btn-primary');

		$validator = new RequiredFields('Email');

		$form = new Form($this, 'ContactForm', $fields, $actions, $validator);
		return $form;
	}

	public function doContactForm($data, $form) {
		$from = $data['Email'];
		$to = $this->Email;
		$subject = "IGF - Contact Form Submission";    
		$email = new Email($from, $to, $subject);
		$email->setTemplate('ContactEmail');
		$email->populateTemplate($data);
		$email->send();

		return $this->redirect($this->Link('?success=1'));
	}

	public function Success() {
		if($this->request->getVar('success')) {
			return true;
		}
		return false;
	}

}