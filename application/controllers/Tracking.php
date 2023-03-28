<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tracking extends MY_Controller
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
		
		$this->load->library('rajaongkir');
	}
	
	/**
	 * Index
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{	
		$order = $this->db
		->select('o.shipping_code, oh.waybill')
		->from('order_history oh')
		->join('order o', 'o.order_id = oh.order_id', 'left')
		->where('oh.order_id', $this->input->get('order_id'))
		->where('oh.waybill !=', '')
		->order_by('oh.order_history_id', 'desc')
		->get()
		->row_array();
		
		$order['tracking'] = false; 
		
		if ($order) {
			$courier = explode('.', $order['shipping_code']);
			$courier = isset($courier[1]) ? $courier[1] : $courier;
			$courier = explode('_', $courier);
			$courier = isset($courier[0]) ? $courier[0] : $courier;
			
			$order['tracking'] = $this->rajaongkir->waybill($order['waybill'], $courier);
		}
		
		$this->output->set_output(json_encode(array(
			'content' => $this->load->layout(false)->view('tracking', $order, true)
		)));
	}
	
	/**
	 * Check
	 * 
	 * @access public
	 * @return void
	 */
	public function check($invoice_no = null)
	{	
		$order = $this->db
		->select('o.shipping_code, oh.waybill')
		->from('order_history oh')
		->join('order o', 'o.order_id = oh.order_id', 'left')
		->where('o.invoice_no', $invoice_no)
		->where('oh.waybill !=', '')
		->order_by('oh.order_history_id', 'desc')
		->get()
		->row_array();
		
		if ($order) {
			$courier = explode('.', $order['shipping_code']);
			$courier = isset($courier[1]) ? $courier[1] : $courier;
			$courier = explode('_', $courier);
			$courier = isset($courier[0]) ? $courier[0] : $courier;
			
			$order['tracking'] = $this->rajaongkir->waybill($order['waybill'], $courier);
			
			$this->load
			->breadcrumb('Tracking Pengiriman', site_url())
			->view('tracking_info', $order);
		} else {
			show_404();
		}
	}
}