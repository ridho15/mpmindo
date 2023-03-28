<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Total_midtrans
{
	protected $sort_order = 7;
	
	/**
	 * Constructor
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		$this->ci =& get_instance();
		
		$this->ci->load->library('shopping_cart');
		$this->ci->load->library('currency');
		$this->ci->load->model('midtrans_model');
	}
	
	/**
	 * Get total
	 * 
	 * @access public
	 * @param array &$total_data
	 * @param float &$total
	 * @return void
	 */ 
	public function get(&$total_data, &$total)
	{
		$payment_method = $this->ci->session->userdata('payment_method');
		$surcharge = 0;
		
		if (isset($payment_method['code'])) {
			if ($method = $this->ci->midtrans_model->get_method($payment_method['code'])) {
				if ($method['surcharge_percentage']) {
					$surcharge = (float)$total * ((float)$method['surcharge_percentage'] / 100);
				}
				
				if ($method['surcharge_fixed']) {
					$surcharge = (float)$surcharge + (float)$method['surcharge_fixed'];
				}
			}	
		}
		
		if ((float)$this->ci->shopping_cart->get_total() > 0 && $surcharge) {
			$total_data[] = array( 
				'code' => 'midtrans',
				'title' => 'Biaya Admin',
				'text' => $this->ci->currency->format((float)$surcharge),
				'value'	=> (float)$surcharge,
				'sort_order' => $this->sort_order
			);
	
			$total += (float)$surcharge;
		}
	}
}