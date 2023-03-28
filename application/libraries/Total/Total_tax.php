<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Total_tax
{
	protected $sort_order = 3;
	
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
		$products = $this->ci->shopping_cart->get_products();
		$tax_amount = 0;
		
		foreach ($products as $product) {
			if ($product['tax_type'] == 'Eksklusif') {
				$tax_amount += (float)$product['price'] * ((float)$product['tax_value']/100) * (int)$product['quantity'];
			}
		}
		
		if ($tax_amount) {
			$total_data[] = array( 
				'code' => 'tax',
				'title' => 'PPN 10% (Barang Kena Pajak)',
				'text' => $this->ci->currency->format((float)$tax_amount),
				'value'	=> (float)$tax_amount,
				'sort_order' => $this->sort_order
			);
	
			$total += (float)$tax_amount;
		}
	}
}