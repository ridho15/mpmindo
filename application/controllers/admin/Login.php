<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends Admin_Controller
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
		
		$this->load->library('form_validation');
		$this->load->helper('form');
	}
	
	/**
	 * administrator login
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		$json = array();
		
		if ($this->input->post() && $this->input->is_ajax_request()) {
			$this->form_validation
			->set_rules('email', 'Username or Email', 'trim|required')
			->set_rules('password', 'Password', 'trim|required')
			->set_error_delimiters('', '');
	
			if ($this->form_validation->run() == true) {
				if ($this->admin->login($this->input->post('email'), $this->input->post('password'), $this->input->post('remember'))) {
					$json['success'] = 'Redirecting to dashboard...';
					$json['redirect'] = admin_url();
				} else {
					$json['warning'] = 'Wrong authentication!';
				}
			} else {
				$json['warning'] = validation_errors();
			}
			
			$this->output->set_output(json_encode($json));
		} else {
			$this->load->layout(false)->view('admin/login');
		}
	}
}