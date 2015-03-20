<?php
/**
 * Controller to respond to the javascript api call to populate the dropdown options 
 * within the tinymce component that inserts codeblock shortcodes
 *
 * @author Michael Parkhill <mike@silverstripe.com>
 */
class CodeBlockController extends Controller {

	/**
	 * @var array $allowed_actions whitelisting of public methods available in this Controller 
	 */
	private static $allowed_actions = array(
		'getJson'
	);

	/**
	 * Initialises the controller and ensures that only
	 * ADMIN level users can access this controller
	 */
	private function init() {
		parent::init();
		if (!Permission::check('ADMIN')) {
			return $this->httpError(403);
		}
	}

	/**
	 * Returns a json encoded array of CodeBlock dataobjects in the format: (ID, Name) 
	 * Note: only objects with AdvertType of 'Embed Javascript' and Status of 'Active' are returned
	 *
 	 * @param SS_HTTPRequest $request
	 * @return SS_HTTPResonse A http response containing a json encoded array
 	 * @see: code-blocks/_config/routes.yml
	 */
	private function getJson(SS_HTTPRequest $request) {
		$this->response->addHeader('Content-Type', 'application/json');
		if ($list = CodeBlock::get()->filter(array('Status' => 'Active'))->map('ID', 'Name')) {
			$this->response->setBody(json_encode($list->toArray()));
			return $this->response;
		}
	}
}