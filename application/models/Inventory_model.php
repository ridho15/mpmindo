<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_model extends MY_Model
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
	}
	
	/**
	 * Check stock
	 * 
	 * @access public
	 * @param int $product_id
	 * @param int $warehouse_id
	 * @return int
	 */
	public function check_stock($product_id, $warehouse_id = null)
	{
		$result = $this->db
		->select_sum('quantity')
		->where('product_id', (int)$product_id);
		
		if ($warehouse_id) {
			$result = $this->db->where('warehouse_id', (int)$warehouse_id);
		}
		
		$result = $this->db->get('inventory')->row_array();
		
		return ($result) ? (int)$result['quantity'] : 0;
	}
}