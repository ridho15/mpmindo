<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Tokopedia API PHP client
 *
 * @author		Adi Setiawan
 * @email		mas.adisetiawan@gmail.com
 * @copyright	Copyright (c)2020
 * @filesource
 */
 
class Tokopedia
{
	/**
	 * CI instance
	 * 
	 * @var string
	 * @access private
	 */
	private $ci;
	private $client_id = 'c615339344e64592a6485751589b6bae';
	private $client_secret = '0970b62aff584dd6a3be20795c073164';
	private $fs_id = '14117';
	private $base_url = 'https://fs.tokopedia.net/';
	private $account_url = 'https://accounts.tokopedia.com/';
	
	/**
	 * Constructor
	 * 
	 * @access public
	 * @param string $api_key
	 * @param string $account_type
	 * @return void
	 */
	public function __construct()
	{	
		if (!function_exists('curl_init')) {
			log_message('error', 'cURL Class - PHP was not built with cURL enabled. Rebuild PHP with --with-curl to use cURL.');
		}
		
		$this->ci =& get_instance();
	}
	
	/**
	 * Get token
	 * 
	 * @access public
	 * @return void
	 */
	public function get_token()
	{
		$tokopedia_token = $this->ci->session->userdata('tokopedia_token');
		
		if ($tokopedia_token) {
			if ($tokopedia_token['eat'] < time()) {
				return $tokopedia_token['access_token'];
			} else {
				$this->ci->session->unset_userdata('tokopedia_token');
				return $this->get_token();
			}
		} else {
			$auth = base64_encode($this->client_id.':'.$this->client_secret);
			$curl = curl_init();
			$header[] = "Content-Type: application/x-www-form-urlencoded";
			$header[] = "Authorization: Basic $auth";
			$header[] = "Content-Length: 0";
			
			$query = http_build_query(array(
				'grant_type' => 'client_credentials'
			));
			
			curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
			curl_setopt($curl, CURLOPT_URL, $this->account_url.'token?'.$query);
			curl_setopt($curl, CURLOPT_POST, true);
			//curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			$request = curl_exec($curl);
			$return = ($request === false) ? curl_error($curl) : $request;
			curl_close($curl);
			
			$return = json_decode($return, true);
			log_message('error', json_encode($header));
			
			if (isset($return['access_token'])) {
				$return['iat'] = time();
				$return['eat'] = time() + (int)$return['expires_in'];
				
				$this->ci->session->set_userdata('tokopedia_token', $return);
				
				return $return['access_token'];
			} else {
				log_message('error', 'Kesalahan saat otentikasi ke Tokopedia API!');
				return false;
			}
		}
	}
	
	/**
	 * cURL post
	 * 
	 * @access private
	 * @param array $params
	 * @param string $endpoint
	 * @return array
	 */
	private function post($params, $endpoint = '')
	{
		$curl = curl_init();
		$header[] = "Content-Type: application/x-www-form-urlencoded";
		$header[] = "Authorization: Bearer $this->get_auth()";
		$query = http_build_query($params);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_URL, $this->api_url.$endpoint);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$request = curl_exec($curl);
		$return = ($request === false) ? curl_error($curl) : $request;
		curl_close($curl);
		
		return json_decode($return, true);
	}
	
	/**
	 * cURL get
	 * 
	 * @access private
	 * @param array $params
	 * @param string $endpoint
	 * @return array
	 */
	private function get($params, $endpoint = '')
	{
		$curl = curl_init();
		$header[] = "key: $this->api_key";
		$query = http_build_query($params);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_URL, $this->api_url.$endpoint."?".$query);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$request = curl_exec($curl);
		$return = ($request === false) ? curl_error($curl) : $request;
		curl_close($curl);
		
		return json_decode($return, true);
	}
	
	public function update_stock($sku, $quantity)
	{
		// get stock balance on tokopedia
		
		// update stock
	}
}