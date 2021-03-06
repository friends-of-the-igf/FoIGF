<?php
/**
* Controller to display Link Items
*
* @package FoIGF
*/
class LinkItemController extends Page_Controller {
	
	public static $url_handlers = array(
		'$ID!/$Action' => 'handleAction'
	);

	public static $allowed_actions = array(
	);

	protected $item = null;

	public function init() {
		parent::init();

		$id = (int)$this->request->param('ID');
		if($item = LinkItem::get()->ByID($id)) {
			$this->item = $item;
		} else {
			return $this->httpError(404);
		}
	}

	/**
	 * Gets current Link Item
	 * 
	 * @return LinkItem.
	 */
	public function getItem() {
		return $this->item;
	}

	/**
	 * Returns a class name for the Controller
	 * 
	 * @return String.
	 */
	public function getClassName() {
		return 'LinkItemController';
	}

}
