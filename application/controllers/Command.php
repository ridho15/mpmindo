<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Command extends MY_Controller
{
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
	
	public function sms($mobile = '', $message = '')
	{
		$this->load->library('sms');
		
		print_r($this->sms->send($mobile, $message));
	}
	
	public function check_resi($no_resi = 'JD0072025968')
	{
		$this->load->library('rajaongkir');
		$res = $this->rajaongkir->waybill($no_resi, 'jnt');
		
		print_r($res);
	}
	
	function getCodeGeneratorForBlowfishPassword()
	{
		return "$2a$09$";
	}
	
	function getBlowfishPassword($password,$username)
	{
		$salt = $this->getCodeGeneratorForBlowfishPassword();
		for ($i = 0; $i < 22; $i++) {
			$salt .= substr("./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789", mt_rand(0, 63), 1);
		}
		$newPassword = crypt($password."{".$username."}", $salt);
		echo str_replace(self::getCodeGeneratorForBlowfishPassword(),'',$newPassword);
	}
	
	public function get_order($order_id = null)
	{
		$this->load->model('order_model');
		
		print_r($this->order_model->get_order_tax($order_id, true));
		print_r($this->order_model->get_order_no_tax($order_id, true));
	}
	
	public function notifications()
	{
		//$this->load->model('api/notifications_model', 'api_notifications_model');
		//$this->load->model('api/users_model', 'api_users_model');
		
		//print_r($this->api_notifications_model->get_notifications());
		
		//$users = $this->api_users_model->get_users(array(8,9), 'warehouse', 3);
		
		//print_r($users);
	}
}