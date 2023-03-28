<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Verification extends User_Controller
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
	 * Index
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		check_ajax();
		
		$user = $this->user_model->get($this->user->user_id());
		
		$data['action'] = user_url('verification/request_code');
		$data['telephone'] = $user['telephone'];
		
		$this->load->layout(false)->view('user/verification_form', $data);
	}
	
	/**
	 * Request code
	 * 
	 * @access public
	 * @return void
	 */
	public function request_code()
	{
		check_ajax();
		
		$json = array();
		
		$this->form_validation
		->set_rules('telephone', 'No. Handphone', 'trim|required|callback__check_telephone')
		->set_error_delimiters('', '');
		
		if ($this->form_validation->run() == false) {
			if (form_error('telephone') != '') {
				$json['errors']['telephone'] = form_error('telephone');
			}
		} else {
			$this->load->helper('string');
			$this->load->library('sms');
			$this->lang->load('user_sms');
			
			$code = random_string('numeric', 6);
			$date = time() + 3600;
			
			$telephone = $this->input->post('telephone');
			$message = sprintf(lang('sms_account_confirm'), $code, date('d/m/Y H:i', $date));
			
			if ($this->sms->send($telephone, $message)) {
				$this->load->model('verification_model');
							
				$verification = array(
					'user_id' => $this->user->user_id(),
					'date_expired' => date('Y-m-d H:i:s', $date),
					'telephone' => $telephone,
					'code' => $code,
					'type' => 'account_confirm',
				);
							
				$this->verification_model->insert($verification);
				$this->user_model->update($this->user->user_id(), array('telephone' => $telephone));
							
				$json['success'] = 'Kode verikasi telah dikirim melalui SMS ke nomor <b>'.$telephone.'</b>';
			} else {
				$json['error'] = 'System error, please contact our administrator!';
			}
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	/**
	 * Check telephone callback
	 * 
	 * @access public
	 * @param string $telephone
	 * @return bool
	 */
	public function _check_telephone($telephone)
	{
		if ($this->user_model->check_telephone($telephone, $this->user->user_id())) {
			$this->form_validation->set_message('_check_telephone', 'Nomor handphone tidak tersedia!');
			return false;
		}
		
		return true;
	}
	
	/**
	 * Confirm
	 * 
	 * @access public
	 * @return void
	 */
	public function confirm()
	{
		$json = array();
		
		$this->load->model('verification_model');
		
		$param = array(
			'code' => $this->input->post('code'), 
			'type' => 'account_confirm', 
			'user_id' => $this->user->user_id()
		);
		
		if ($verification = $this->verification_model->get_by($param)) {
			if (strtotime($verification['date_expired']) > strtotime(date('Y-d-m H:i:s', time()))) {
				$this->verification_model->delete_by(array('user_id' => $this->user->user_id(), 'type' => 'account_confirm'));
				$this->user_model->update($this->user->user_id(), array('verified' => 1));
				$json['redirect'] = user_url();
			} else {
				$json['error'] = 'Kode verifikasi sudah kadaluarsa!';
			}
		} else {
			$json['error'] = 'Kode verifikasi tidak valid!';
		}
		
		$this->output->set_output(json_encode($json));
	}
}