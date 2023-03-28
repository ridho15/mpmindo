<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat extends CI_Controller
{
	private $webhook_secret = '6b38edab2fa1b13a3482e4ba2b71d9bdd9632f76c339c41dfb00f52c88ec77329858bcdb1ab91fc8a5c4c197af99a125';
	
	/**
	 * Constructor
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Verifiy signature
	 * 
	 * @access private
	 * @param array $body
	 * @param string $signature
	 * @return bool
	 */
	private function verify_signature($body, $signature)
	{
		$digest = hash_hmac('sha1', $body, $this->webhook_secret);
		
		return $signature == $digest ;
	}
	
	/**
	 * Event
	 * 
	 * @access public
	 * @return void
	 */
	public function event()
	{
		$data = file_get_contents('php://input');
		
		if ( ! $this->verify_signature($data, $_SERVER['HTTP_X_TAWK_SIGNATURE'])) {
			exit('Webhook chat not authorized!');
		} else {
			$result = json_decode($data, true);
			
			$this->load->model('chat_session_model');
			
			switch($result['event']) {
				case 'chat:start':
					$this->chat_session_model->insert(array(
						'chat_id' => $result['chatId'],
						'time_start' => date('Y-m-d H:i:s', strtotime($result['time'])),
						'agent_id' => $result['property']['id'],
						'agent_name' => $result['property']['name']
					));
				break;
				
				case 'chat:end':
					if ($chat_session = $this->chat_session_model->get_by(array('chat_id' => $result['chatId']))) {
						$this->chat_session_model->update($chat_session['chat_session_id'], array(
							'time_end' => date('Y-m-d H:i:s', strtotime($result['time'])),
						));
					}
				break;
			}
		}
	}
}