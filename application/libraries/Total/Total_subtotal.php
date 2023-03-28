<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Total_subtotal
{
	protected $sort_order = 1;
	
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
		$sub_total = $this->ci->shopping_cart->get_sub_total();
		
		$total_data[] = array(
			'code' => 'subtotal',
			'title' => 'Sub Total',
			'text' => $this->ci->currency->format($sub_total),
			'value' => $sub_total,
			'sort_order' => $this->sort_order
		);
		
		$total += $sub_total;
	}
}