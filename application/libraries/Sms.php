<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sms
{
	private $username;
	private $password;
	private $server_name;
	private $use_database = false;
	
	public function __construct($config = array())
	{
		$this->_initialize($config);
	}
	
	private function _initialize($config = array())
	{
		foreach ($config as $key => $value) {
			$this->{$key} = $value;
		}
	}
	
	public function send($mobile = '', $message = '', $secure = false)
	{
		$params['username'] = $this->username;
		$params['mobile'] = $mobile;
		$params['message'] = $message;
		
		if ($secure == false) {
			$params['password'] = $this->password;
			
			$return_code = $this->get($params, 'Send.php');
		} else {
			$params['auth'] = md5($this->username.$this->password.$mobile);
			$params['trxid'] = '';
			$params['type'] = 0;
			
			$return_code = $this->get($params, 'sendSMS.php');
		}
		
		//log_message('error', $return_code);
		
		return $return_code;
	}
	
	public function get($params, $endpoint = '')
	{
		$curl = curl_init();
		$query = http_build_query($params);
		curl_setopt($curl, CURLOPT_URL, $this->server_name . $endpoint . "?" . $query);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$request = curl_exec($curl);
		$return = ($request === false) ? curl_error($curl) : $request;
		curl_close($curl);
		
		return $return;
	}
}