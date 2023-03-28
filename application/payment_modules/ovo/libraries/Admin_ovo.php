<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_Ovo
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
		$this->ci->lang->load('ovo');
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
				$this->ci->setting_model->edit_setting('payment_ovo', $this->ci->input->post(null, true));
				$json['success'] = true;
			}
			
			return $json;
		} else {
			$this->ci->load->helper('form');
			
			$data['action'] = admin_url('payment_modules/setting/ovo');
			$data['heading_title'] = lang('heading_title');
			
			$data['title'] = $this->ci->setting_model->get_setting('payment_ovo', 'title');
			$data['instruction'] = $this->ci->setting_model->get_setting('payment_ovo', 'instruction');
			$data['unique_code'] = $this->ci->setting_model->get_setting('payment_ovo', 'unique_code');
			$data['qrcode'] = $this->ci->setting_model->get_setting('payment_ovo', 'qrcode');
			$data['confirmation'] = $this->ci->setting_model->get_setting('payment_ovo', 'confirmation');
			
			if ($data['qrcode']) {
				$data['thumb_detail'] = $this->ci->image->resize($data['qrcode'], 200, 200);
			} else {
				$data['thumb_detail'] = $this->ci->image->resize('no_image.jpg', 200, 200);
			}
			
			return $this->ci->load->layout(false)->view('admin/ovo', $data, true);	
		}
	}
}