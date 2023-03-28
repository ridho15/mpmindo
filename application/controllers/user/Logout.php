<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends User_Controller
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
		$this->user->logout();
		$this->session->set_flashdata('message', 'You are logged out!');
		redirect(PATH_USER.'/login', 'refresh');
	}
}