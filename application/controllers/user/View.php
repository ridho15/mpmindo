<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class View extends MY_Controller
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
	
	public function index($username = null)
	{
		$data = array();
		
		$this->load->view('user/view', $data);
	}
}