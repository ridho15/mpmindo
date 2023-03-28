<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'models/api/Base_model.php';

class Inventories_model extends Base_model
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
	 * Get inventory product
	 * 
	 * @access public
	 * @param int $product_id
	 * @param string $place_type
	 * @param int $place_id
	 * @return array
	 */
	public function get_inventory_product($product_id = null, $place_type = null, $place_id = null)
	{
		// $query = $this->idb
		// ->select('place_id, place_type, qty, qty_booking_mutation, qty_booking_sale, (qty-(qty_booking_mutation+qty_booking_sale)) AS balance')
		// ->where('(qty-(qty_booking_mutation+qty_booking_sale)) > 0', null, false);
		
		// if ($product_id) {
		// 	$query = $this->idb->where('product_id', $product_id);
		// }
		
		// if ($place_type) {
		// 	$query = $this->idb->where('place_type', $place_type);
		// }
		
		// if ($place_id) {
		// 	$query = $this->idb->where('place_id', $place_id);
		// }
		
		// $query = $this->idb
		// ->where('(qty-(qty_booking_mutation+qty_booking_sale)) > 0', null, false)
		// ->order_by('place_type', 'asc')
		// ->order_by('qty', 'asc');
		
		// if ($product_id && $place_type && $place_id) {
		// 	$query = $this->idb->get('inventories')->row_array();
		// } else {
		// 	$query = $this->idb->get('inventories')->result_array();
		// }
		
		// return $query;
		return array();
	}
	
	/**
	 * Get inventory product
	 * 
	 * @access public
	 * @param int $product_id
	 * @param string $place_type
	 * @param int $place_id
	 * @return bool
	 */
	public function check_inventory($product_id = null, $place_type = '', $place_id = null)
	{
		// return (bool)$this->idb
		// ->where(array('product_id' => $product_id, 'place_type' => $place_type, 'place_id' => $place_id))
		// ->count_all_results('inventories');
		return 0;
	}
	
	/**
	 * Update quantity booking sale
	 * 
	 * @access public
	 * @param int $product_id
	 * @param int $qty
	 * @param string $place_type
	 * @param int $place_id
	 * @param string $type
	 * @return bool
	 */
	public function update_qty_booking_sale($product_id = null, $qty = 1, $place_type = '', $place_id = null, $type = '')
	{
		// $result = $this->idb
		// ->select('qty_booking_sale')
		// ->where('product_id', $product_id)
		// ->where('place_type', $place_type)
		// ->where('place_id', $place_id)
		// ->limit(1)
		// ->get('inventories')
		// ->row_array();
		
		// if ($result) {
		// 	if ($type == 'add') {
		// 		$qty_booking = (int)$result['qty_booking_sale'] + (int)$qty;
		// 	} elseif ($type == 'minus') {
		// 		$qty_booking = (int)$result['qty_booking_sale'] - (int)$qty;
		// 	} else {
		// 		$qty_booking = (int)$result['qty_booking_sale'];
		// 	}
			
		// 	$data = array(
		// 		'product_id' =>$product_id,
		// 		'qty_booking_sale' => $qty_booking,
		// 		'place_type' => $place_type,
		// 		'place_id' => $place_id
		// 	);
			
		// 	$this->idb->update('inventories', $data, array('product_id' => $product_id, 'place_type' => $place_type, 'place_id' => $place_id));
		// }
		
		return true;
	}
	
	/**
	 * Update quantity booking mutation
	 * 
	 * @access public
	 * @param int $product_id
	 * @param int $qty
	 * @param string $place_type
	 * @param int $place_id
	 * @param string $type
	 * @return bool
	 */
	public function update_qty_booking_mutation($product_id = null, $qty = 1, $place_type = '', $place_id = null, $type = 'add')
	{
		// $result = $this->idb
		// ->select('qty_booking_mutation')
		// ->where('product_id', $product_id)
		// ->where('place_type', $place_type)
		// ->where('place_id', $place_id)
		// ->limit(1)
		// ->get('inventories')
		// ->row_array();
		
		// if ($result) {
		// 	if ($type == 'add') {
		// 		$qty_booking = (int)$result['qty_booking_mutation'] + (int)$qty;
		// 	} elseif ($type == 'minus') {
		// 		$qty_booking = (int)$result['qty_booking_mutation'] - (int)$qty;
		// 	} else {
		// 		$qty_booking = (int)$result['qty_booking_mutation'];
		// 	}
			
		// 	$data = array(
		// 		'product_id' =>$product_id,
		// 		'qty_booking_mutation' => $qty_booking,
		// 		'place_type' => $place_type,
		// 		'place_id' => $place_id
		// 	);
			
		// 	$this->idb->update('inventories', $data, array('product_id' => $product_id, 'place_type' => $place_type, 'place_id' => $place_id));
		// }
		
		return true;
	}
	
	/**
	 * Insert inventory data
	 * 
	 * @access public
	 * @param array $data
	 * @return bool
	 */
	public function insert_inventory_data($data = array())
	{
		// if ($this->idb->insert('inventories', $data)) {
		// 	return true;
		// }
		
		// return false;

		return true;
	}
}