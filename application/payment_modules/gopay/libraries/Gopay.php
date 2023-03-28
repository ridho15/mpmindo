<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gopay
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
		$this->ci->lang->load('gopay');
		$this->ci->load->model('gopay_model');
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
		
		$data['action'] = site_url('payments/gopay/confirm');
		$data['continue'] = $this->ci->config->item('confirmation', 'payment_gopay') ? site_url('payment_confirmation?order_id='.$order_id) : site_url('checkout/success');
		$data['instruction'] = $this->ci->config->item('instruction', 'payment_gopay');
		$data['payment_confirmation'] = (bool)$this->ci->config->item('confirmation', 'payment_gopay');
		$data['order_id'] = $order_id;
		
		$qrcode = $this->ci->config->item('qrcode', 'payment_gopay');
		
		if ($qrcode) {
			$data['qrcode'] = $this->ci->image->resize($qrcode, 1000, 1000);
		} else {
			$data['qrcode'] = $this->ci->image->resize('no_image.jpg', 1000, 1000);
		}
		
		$this->ci->load->layout(false)->view('gopay', $data);
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
			$comment = $this->ci->config->item('instruction', 'payment_gopay');
			
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