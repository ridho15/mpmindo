<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reset extends User_Controller
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
		$this->load->model('user_model');
		$this->lang->load('user_reset');
	}
	
	/**
	 * Request form
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		$json = array();
		
		if ($this->input->post() && $this->input->is_ajax_request()) {
			$this->form_validation
			->set_rules('email', 'lang:entry_email', 'trim|required|callback__check_email')
			->set_rules('captcha', 'lang:entry_captcha', 'trim|required|callback__check_captcha')
			->set_error_delimiters('', '');
	
			if ($this->form_validation->run() == true) {
				$user = $this->user_model->get_by(array('email' => $this->input->post('email'), 'active' => 1));
			
				if ($user) {
					$data['name'] = $user['name'];
					$data['continue'] = user_url('reset/change_password/'.$this->user_model->request_reset_password($user['email']));
					
					$this->load->library('email');
					$this->load->config('email');
					
					$this->email->from($this->config->item('sender'), $this->config->item('site_name'));
					$this->email->to($user['email']);
					$this->email->subject(lang('heading_title'));
					$this->email->message($this->load->layout(null)->view('emails/reset_password', $data, true));
					$this->email->send();
					
					$this->session->set_flashdata('message', lang('text_change_instruction'));
					
					$json['redirect'] = user_url('reset');
				} else {
					$json['warning'] = lang('error_email');
				}
			} else {
				$json['warning'] = validation_errors();
			}
			
			$this->output->set_output(json_encode($json));
		} else {
			if ($this->session->flashdata('message')) {
				$data['message'] = $this->session->flashdata('message');
			} else {
				$data['message'] = false;
			}
			
			$data['text_login'] = sprintf(lang('text_login'), user_url('login'));
			
			$this->load
			->breadcrumb(lang('heading_title'), user_url('reset'))
			->view('user/reset', $data);
		}
	}
	
	/**
	 * Check email callback
	 * 
	 * @access public
	 * @param string $email
	 * @return bool
	 */
	public function _check_email($email)
	{
		if ( ! $this->user_model->check_email($email)) {
			$this->form_validation->set_message('_check_email', lang('error_email_not_found'));
			return false;
		}
		
		return true;
	}
	
	/**
	 * Check captcha
	 * 
	 * @access public
	 * @param string $word
	 * @return bool
	 */
	public function _check_captcha($word)
	{
		$captcha = $this->session->userdata('captcha');
		
		if (isset($captcha['word'])) {
			if ($captcha['word'] == $word) {
				return true;
			} else {
				$this->form_validation->set_message('_check_captcha', lang('error_captcha'));
				return false;
			}
		} else {
			$this->form_validation->set_message('_check_captcha', lang('error_captcha'));
			return false;
		}
	}
	
	/**
	 * Change reset password
	 * 
	 * @access public
	 * @param string $code
	 * @return void
	 */
	public function change_password($code = null)
	{
		if (is_null($code)) {
			show_404();
		}
		
		$json = array();
		
		$user = $this->db->where('code_forgotten', $code)->get('user')->row_array();
		
		if ($user) {
			$this->user_id = $user['user_id'];
			
			$this->form_validation->set_rules('confirm', 'lang:entry_confirm', 'trim|required|matches[password]');
			$this->form_validation->set_rules('password', 'lang:entry_new_password', 'trim|required|min_length[8]');
			
			if ($this->form_validation->run() == true) {
				$new_password = $this->input->post('password');
				$old_password = $this->user_model->reset_password($code);
				
				$this->user_model->change_password($user['user_id'], $old_password, $new_password);
				
				if ($this->user->login($user['email'], $new_password)) {
					$redirect = user_url();
				} else {
					$redirect = user_url('login');
				}
				
				if ($this->input->is_ajax_request()) {
					$json['redirect'] = $redirect;
				} else {
					redirect($redirect);
				}
			} else {
				$error = validation_errors();
				
				if ($this->input->is_ajax_request()) {
					$json['warning'] = $error;
				} else {
					$data['action'] = user_url('reset/change_password/'.$code);
					$data['warning'] = $error;
					
					$this->load->view('user/change_password', $data);
				}
			}
		} else {
			if ($this->input->is_ajax_request()) {
				$json['error'] = 'Bad request!';
			} else {
				show_404();
			}
		}
		
		if ($this->input->is_ajax_request()) {
			$this->output->set_output(json_encode($json));
		}
	}
}