<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends Admin_Controller
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
	 * Administrator logout
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		$this->admin->logout();
		$this->session->set_flashdata('message', 'Anda telah berhasil keluar!');
		redirect(PATH_ADMIN.'/login', 'refresh');
	}
}