<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends User_Controller
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
		
		$this->load->layout('default');
		$this->load->library('form_validation');
		$this->load->helper('form');
		$this->load->helper('language');
		$this->lang->load('user_login');
	}
	
	/**
	 * user login
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		$json = array();
		
		if ($this->input->post() && $this->input->is_ajax_request()) {
			$this->form_validation
			->set_rules('email', 'lang:entry_email', 'trim|required')
			->set_rules('password', 'lang:entry_password', 'trim|required')
			->set_error_delimiters('', '');
	
			if ($this->form_validation->run() == true) {
				if ($this->user->login($this->input->post('email'), $this->input->post('password'), $this->input->post('remember'))) {
					$json['success'] = lang('text_login_success');
					if ($this->session->userdata('redirect')) {
						$redirect = $this->session->userdata('redirect');
						$this->session->unset_userdata('redirect');
					} else {
						$redirect = PATH_USER;
					}
					
					// remove guest account
					$this->session->unset_userdata('guest');
					
					$json['redirect'] = site_url($redirect);
				} else {
					$json['warning'] = lang('text_login_error');
				}
			} else {
				$json['warning'] = validation_errors();
			}
			
			$this->output->set_output(json_encode($json));
		} else {
			$data['text_register'] = sprintf(lang('text_register'), user_url('register'));
			$data['text_forgotten'] = sprintf(lang('text_forgotten'), user_url('reset'));
			
			$this->load
			->breadcrumb(lang('heading_title'), user_url('login'))
			->view('user/login', $data);
		}
	}
	
	public function popup()
	{
		$this->load->helper('form');
		
		$data['text_register'] = sprintf(lang('text_register'), user_url('register'));
		$data['text_forgotten'] = sprintf(lang('text_forgotten'), user_url('reset'));
			
		return $this->load->layout(false)->view('user/login_popup', $data);
	}
}