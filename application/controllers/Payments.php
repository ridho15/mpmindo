<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payments extends MY_Controller
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
		
		$this->lang->load('public_payment_module');
	}
	
	/**
	 * Default index
	 * 
	 * @access public
	 * @param bool $slug (default: false)
	 * @param string $method (default: 'index')
	 * @return void
	 */
	public function index($slug = false, $method = 'index')
	{
		$bank_transfer = $this->load->payment('bank_transfer');
		
		if (method_exists($bank_transfer, $method)) {
			return call_user_func([$bank_transfer, $method]);
		} else {
			show_404();
		}
	}
}