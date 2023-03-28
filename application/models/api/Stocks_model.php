<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'models/api/Base_model.php';

class Stocks_model extends Base_model
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
	 * Get stock balance
	 * 
	 * @access public
	 * @param int $product_id
	 * @param string $place
	 * @param int $place_id
	 * @return int
	 */
	public function get_stock_balance($product_id, $place = null, $place_id = null)
	{
		// $query = $this->idb
		// ->select('SUM(qty-(qty_booking_mutation+qty_booking_sale)) AS balance')
		// ->where('product_id', $product_id);
		
		// if ($place && $place_id) {
		// 	$query = $this->idb->where('place', $place);
		// 	$query = $this->idb->where('place_id', $place_id);
		// }
		
		// $query = $this->idb->get('inventories')->row_array();
		
		// if ($query) {
		// 	return (int)$query['balance'];
		// }
		
		// return 0;
		return 0;
	}
}