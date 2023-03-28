<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_confirmations extends Admin_Controller
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
		
		$this->load->model('payment_confirmation_model');
	}
	
	/**
	 * Orders list
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		if ($this->input->post(null, true)) {	
			$this->load->helper('format');
			$this->load->library('datatables');
		
			$this->datatables
			->select('pc.payment_confirmation_id, pc.order_id, pc.from_name, pc.telephone, pc.email, pc.amount, pc.to_bank, pc.date_added, pc.date_transfered, pc.receipt_image, pc.invoice_no')
			->from('payment_confirmation pc');
			
			if ($this->input->post('order_id')) {
				$this->datatables->where('pc.order_id', $this->input->post('order_id'));
			}
			
			$this->datatables
			->edit_column('date_added', '$1', 'format_date(date_added)')
			->edit_column('date_transfered', '$1', 'format_date(date_transfered)')
			->edit_column('amount', '$1', 'format_money(amount)')
			->edit_column('to_bank', '$1', 'strtoupper(to_bank)')
			->add_column('attachment', '$1', 'format_attachment(receipt_image)');
			
			$this->output->set_output($this->datatables->generate('json'));
		} else {
			if ( ! $this->admin->has_permission()) {
				show_401($this->uri->uri_string());
			}
			
			$data['success'] = $this->session->userdata('success');
			$data['error'] = $this->session->userdata('error');
			
			$this->load
			->title('Konfirmasi Pembayaran')
			->view('admin/payment_confirmation', $data);
		}
	}

	
	/**
	 * Delete
	 * 
	 * @access public
	 * @return void
	 */
	public function delete()
	{
		check_ajax();
		
		$json = array();
		
		if ( ! $this->admin->has_permission()) {
			$json['error'] = lang('admin_error_delete');
		}
		
		$payment_confirmation_ids = $this->input->post('payment_confirmation_id');
		
		if ( ! $payment_confirmation_ids) {
			$json['error'] = lang('error_no_selected');
		}
		
		if (empty($json['error'])) {
			$this->payment_confirmation_model->delete($payment_confirmation_ids);
			
			$json['success'] = lang('success_deleted');
		}
		
		$this->output->set_output(json_encode($json));
	}
}

function format_attachment($receipt_image) {
	$ci =& get_instance();
	if ($receipt_image) {
		return $ci->image->resize($receipt_image, 800);	
	} else {
		return false;
	}
}