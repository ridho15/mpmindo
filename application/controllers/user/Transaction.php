<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction extends User_Controller
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
	
	public function index()
	{
		$this->load->view('user/transaction');
	}
}