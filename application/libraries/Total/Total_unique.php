<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Total_unique
{
	protected $sort_order = 6;
	
	public function __construct()
	{
		$this->ci =& get_instance();
		
		$this->ci->load->library('shopping_cart');
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
		$payment_method = $this->ci->session->userdata('payment_method');
		
		if (isset($payment_method['code'])) {
			$status = (bool)$this->ci->config->item('unique_code', 'payment_' . $payment_method['code']);	
		} else {
			$status = false;
		}
		
		if ($this->ci->shopping_cart->get_total() == 0) {
			$status = false;
		}
		
		if ($status) {
			if ($this->ci->session->userdata('payment_unique')) {
				$unique = $this->ci->session->userdata('payment_unique');
			} else {
				$this->ci->load->helper('string');
				$unique = random_string('numeric', 3);
				$this->ci->session->set_userdata('payment_unique', $unique);
			}
			
			$total_data[] = array( 
				'code' => 'unique',
				'title' => 'Kode Unik',
				'text' => $this->ci->currency->format((float)$unique),
				'value'	=> (float)$unique,
				'sort_order' => $this->sort_order
			);
	
			$total += (float)$unique;
		} else {
			$this->ci->session->unset_userdata('payment_unique');
		}
	}
}