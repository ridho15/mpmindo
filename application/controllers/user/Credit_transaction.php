<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Credit_Transaction extends User_Controller
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
	
	/**
	 * administrator logout
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		$this->load->view('user/credit_transaction');
	}
}