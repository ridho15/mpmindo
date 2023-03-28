<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_bank_transfer
{
	private $ci;
	
	/**
	 * Constructor
	 * 
	 * @access public
	 * @return void
	 */	
	public function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->lang->load('bank_transfer');
	}
	
	/**
	 * Setting
	 * 
	 * @access public
	 * @return void
	 */
	public function setting()
	{
		if ($this->ci->input->post(null, true)) {
			$json = [];
					
			$this->ci->form_validation
			->set_rules('title', 'Judul', 'trim|required')
			->set_rules('instruction', 'Instruksi', 'trim|required')
			->set_error_delimiters('', '');
			
			if ($this->ci->form_validation->run() == false) {
				foreach ($this->ci->form_validation->get_errors() as $field => $error) {
					$json['errors'][$field] = $error;
				}
			} else {
				$this->ci->setting_model->edit_setting('payment_bank_transfer', $this->ci->input->post(null, true));
				$json['success'] = true;
			}
			
			return $json;
		} else {
			$this->ci->load->helper('form');
			
			$data['action'] = admin_url('payment_modules/setting/bank_transfer');
			$data['heading_title'] = lang('heading_title');
			
			$data['title'] = $this->ci->setting_model->get_setting('payment_bank_transfer', 'title');
			$data['instruction'] = $this->ci->setting_model->get_setting('payment_bank_transfer', 'instruction');
			$data['unique_code'] = $this->ci->setting_model->get_setting('payment_bank_transfer', 'unique_code');
			$data['confirmation'] = $this->ci->setting_model->get_setting('payment_bank_transfer', 'confirmation');
			$data['bank_account_ids'] = (array)$this->ci->setting_model->get_setting('payment_bank_transfer', 'bank_accounts');
			
			$this->ci->load->model('bank_account_model');
			$data['bank_accounts'] = $this->ci->bank_account_model->get_bank_accounts();
			
			return $this->ci->load->layout(false)->view('admin/bank_transfer', $data, true);	
		}
	}
}