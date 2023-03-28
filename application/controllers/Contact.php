<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->load->helper('form');
		
		$data['action'] = site_url('contact/validate');
		$data['success'] = $this->session->flashdata('success');
		
		$this->load->view('contact', $data);
	}
	
	public function validate()
	{
		$json = array();
		
		$this->load->library('form_validation');
		
		$this->form_validation
		->set_rules('name', 'Name', 'trim|required|min_length[3]|max_length[128]')
		->set_rules('email', 'Email', 'trim|required|valid_email')
		->set_rules('telephone', 'Phone Number', 'trim|required|numeric|min_length[3]|max_length[12]')
		->set_rules('subject', 'Subject', 'trim|required|min_length[8]|max_length[64]')
		->set_rules('message', 'Message', 'trim|required|min_length[32]|max_length[1000]')
		->set_rules('captcha', 'Captcha', 'trim|required|callback__check_captcha')
		->set_error_delimiters('', '');
		
		if ($this->form_validation->run() == false) {
			foreach (array('name', 'email', 'telephone', 'subject', 'message', 'captcha') as $field) {
				if (form_error($field) !== '') {
					$json['error'][$field] = form_error($field);
				}
			}
		} else {
			$this->load->library('email');
			
			$message  = 'From : ' . html_entity_decode($this->input->post('name'), ENT_QUOTES, 'UTF-8') . "\n";
			$message .= 'Email : ' . $this->input->post('email') . "\n";
			$message .= 'Telephone : ' . html_entity_decode($this->input->post('telephone'), ENT_QUOTES, 'UTF-8') . "\n";
			$message .= 'Subject : ' . html_entity_decode($this->input->post('subject'), ENT_QUOTES, 'UTF-8') . "\n";
			$message .= 'Message : ' . "\n";
			$message .= html_entity_decode($this->input->post('message'), ENT_QUOTES, 'UTF-8') . "\n";
			
			$this->email->from('halo@mpmindo.id', $this->config->item('site_name'));
			$this->email->to($this->config->item('email'));
			$this->email->reply_to($this->input->post('email'));
			$this->email->subject('Message Subject : ' . html_entity_decode($this->input->post('subject'), ENT_QUOTES, 'UTF-8'));
			$this->email->message($message);
			
			if ($this->email->send()) {
				$success = 'Message sent, thank you.';
				$this->session->set_flashdata('success', $success);
				$json['success'] = $success;
			} else {
				exit($this->email->print_debugger());
			}
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	public function _check_captcha($word)
	{
		$captcha = $this->session->userdata('captcha');
		
		if (isset($captcha['word'])) {
			if ($captcha['word'] == $word) {
				return true;
			} else {
				$this->form_validation->set_message('_check_captcha', 'No matches captcha!');
				return false;
			}
		} else {
			$this->form_validation->set_message('_check_captcha', 'No matches captcha!');
			return false;
		}
	}
}