<?php
defined('SYSPATH') or die('No direct access allowed!');

/**
 *
 * @package Trello Time Tracking
 * @category Controller
 * @author Stoyan Dipchikov
 * @copyright Despark Ltd.
 */
class Controller_Api extends Controller_App {

	public $template = '';

	public $directory = '';

	/**
	 *
	 * @var String path to the view file for the current request 
	 */
	public $view = '';

	/**
	 * 
	 * @var string name of the current action 
	 */
	protected $action;
	
	/**
	 * 
	 * @var string name of the current controller
	 */
	protected $controller;

	public function action_index() {
		echo "Hello Trallo";
	}

	public function action_init_organization() {
		if ($this->valid_post) 
		{
			$organization_id = $this->_post('organization_id');
			$user_token = $this->_post('token');

			$org = ORM::factory('organization', $organization_id);

			if (!$org->loaded()) 
			{
				$this->debug($this->trello_get('organizations/' . $organization_id, array('token' => $user_token)));
			}
		}
		else 
		{
			die('not post');
		}
	}

	private function trello_get($method, $params = array()) {
		$request_url = $this->trello_api_url . $method . '?key='. $this->trello_api_key;
		$params_string = '';

		if (count($params) > 0) 
		{
			foreach ($params as $key => $value) 
			{
				$params_string .= "&" . "$key=" . urlencode($value);
			}
		}

		$request_url .= $params_string;

		$request = Request::factory($request_url);

		$request->client()->options(CURLOPT_SSL_VERIFYPEER, false);

		$response = $request->execute();

		return $response->body();
	}

	private function trello_post($method, $params = array()) {
		$request_url = $this->trello_api_url . $method . '?key='. $this->trello_api_key;

		$response = Request::factory($request_url)
							->method(Request::POST)
							->post($params)
							->execute();

		return $response->body();
	}

	public function action_self_tester() {
		// $response = Request::factory('')

		$response = Request::factory('http://trello-time/api/init_organization')
							->method(Request::POST)
							->post(array(
										'token' 			=> '53b85141ce140ca9143b0ec3df460395a8769a5f04f9d60e028b93a27142ee25',
										'organization_id' 	=> '4f214a2360b4b1ca6410f53c'
									)
							)
							->execute();

		$this->debug($response->body());
	}

	private function debug($var, $is_json = false, $bool_var = false) {
		echo "<pre>";

		if ($bool_var) 
			$res = (integer)$var;
		elseif ($is_json) 
			$res = json_decode($var);
		else 
			$res = $var;

		print_r($res);
	}
}