<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank_transfer
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
		$this->ci->load->model('bank_transfer_model');
		$this->ci->load->model('bank_account_model');
	}
	
	/**
	 * Index
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		$this->ci->load->helper('form');
		$order_id = $this->ci->session->userdata('order_id');
		
		$data['action'] = site_url('payments/bank_transfer/confirm');
		$data['continue'] = $this->ci->config->item('confirmation', 'payment_bank_transfer') ? site_url('payment_confirmation?order_id='.$order_id) : site_url('checkout/success');
		$data['instruction'] = $this->ci->config->item('instruction', 'payment_bank_transfer');
		$data['bank_accounts'] = [];
		
		$bank_account_ids = $this->ci->config->item('bank_accounts', 'payment_bank_transfer') ? $this->ci->config->item('bank_accounts', 'payment_bank_transfer') : [];
		
		foreach ($bank_account_ids as $bank_account_id) {
			$data['bank_accounts'][] = $this->ci->bank_account_model->get_bank_account($bank_account_id);
		}
		
		$data['payment_confirmation'] = (bool)$this->ci->config->item('confirmation', 'payment_bank_transfer');
		$data['order_id'] = $order_id;
		
		$this->ci->load->layout(false)->view('bank_transfer', $data);
	}
	
	/**
	 * Confirm
	 * 
	 * @access public
	 * @return json
	 */
	public function confirm()
	{
		$this->ci->load->model('order_model');

		$json['success'] = true;
		
		if ((bool)$this->ci->input->post('confirm')) {
			$comment = $this->ci->config->item('instruction', 'payment_bank_transfer');
			
			if ($this->ci->order_model->confirm($this->ci->session->userdata('order_id'), $this->ci->config->item('order_status_id'), $comment)) {
				$json['success'] = true;	
			}
		} else {
			$json['error'] = true;
		}
		
		$this->ci->output->set_header('Content-Type: application/json');
		$this->ci->output->set_output(json_encode($json));
	}
}