<?php

/**
 * Description of app
 *
 * @package TopCorner
 * @category Controller
 * @author Haralan Dobrev
 */
class Controller_App extends Controller {
	
	/**
	 *
	 * @var Boolean checks if the request is an XMLHttpRequest 
	 */
	protected $ajax = false;
	
	/**
	 *
	 * @var Session instance of the Session helper 
	 */
	protected $session;
	
	/**
	 *
	 * @var Boolean check if the request is a post request and has a valid $_POST array 
	 */
	protected $valid_post = false;
	
	/**
	 *
	 * @var Contains Trello Api Key
	 */
	protected $trello_api_key;
	
	/**
	 *
	 * @var Contains Trello Api OAuth Secret
	 */
	protected $trello_api_secret;
	
	public function before()
	{
		parent::before();

		$this->session = Session::instance();
		$this->ajax = $this->request->is_ajax();

		$trello_config = Kohana::$config->load('trello_api');
		$this->trello_api_key = $trello_config->get('api_key');
		$this->trello_api_secret = $trello_config->get('api_secret');
		$this->trello_api_url = $trello_config->get('api_url') . $trello_config->get('api_version') . '/';

		if ($this->request->is_ajax())
		{
			$this->ajax = true;
		}
		if (HTTP_Request::POST == $this->request->method() && Valid::not_empty($_POST))
		{
			$this->valid_post = true;
		}
	}
	
	protected function _get($index, $default = false)
	{
		return Arr::get($_GET, $index, $default);
	}

	protected function _post($index, $default = false)
	{
		return Arr::get($_POST, $index, $default);
	}
	
}
