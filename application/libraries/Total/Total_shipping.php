<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Total_shipping
{
	protected $sort_order = 5;
	
	public function __construct()
	{
		$this->ci =& get_instance();
		
		$this->ci->load->library('currency');
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
		$shipping_method = $this->ci->session->userdata('shipping_method') ? $this->ci->session->userdata('shipping_method') : array();
		
		if (isset($shipping_method['cost'])) {
			$cost = $shipping_method['cost'];
			
			if ((float)$cost > 0) {
				$total_data[] = array( 
					'code' => 'shipping',
					'title'	=> 'Biaya Kirim',
					'text' => $this->ci->currency->format($cost),
					'value'	=> $cost,
					'sort_order' => $this->sort_order
				);
		
				$total += $cost;	
			}	
		}
	}
}