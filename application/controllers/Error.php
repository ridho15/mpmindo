<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error extends MY_Controller
{
	private $_error;
	
	/**
	 * Constructor
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('admin');
		$this->load->library('user');
		
		if ($this->uri->segment(1) == PATH_ADMIN && $this->admin->is_logged()) {
			$this->_error = new Admin_Error;
		} elseif ($this->uri->segment(1) == PATH_USER && $this->user->is_logged()) {
			$this->_error = new User_Error;
		} else {
			$this->_error = new Public_Error;
		}
	}
	
	public function http($code = '404')
	{
		if ( ! in_array($code, array('401', '404', '503'))) {
			show_404();
		} else {
			$this->_error->view($code);
		}
	}
}

class Admin_Error extends Admin_Controller
{
	public function view($code = '404')
	{
		$data = array();
		$this->output->set_status_header($code);
		exit($this->load->view('error/'.$code, $data, true));
	}
}

class User_Error extends User_Controller
{
	public function view($code = '404')
	{	
		$data = array();
		$this->output->set_status_header($code);
		exit($this->load->view('error/'.$code, $data, true));
	}
}

class Public_Error extends MY_Controller
{
	public function view($code = '404')
	{
		$data = array();
		$this->output->set_status_header($code);
		exit($this->load->view('error/'.$code, $data, true));
	}
}