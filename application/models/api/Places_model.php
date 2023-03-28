<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'models/api/Base_model.php';

class Places_model extends Base_model
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
	 * Get inventory places
	 * 
	 * @access public
	 * @return array
	 */
	public function get_places()
	{
		// $query1 = $this->idb->select('id as place_id, store_name AS name, \'store\' AS type, CONCAT_WS(\'-\', \'store\', id) AS code')->get('stores')->result_array();
		// $query2 = $this->idb->select('id as place_id, warehouse_name AS name, \'warehouse\' AS type, CONCAT_WS(\'-\', \'warehouse\', id) AS code')->get('warehouses')->result_array();
		
		// return array_merge($query1, $query2);
		return array();
	}
	
	/**
	 * Get place name
	 * 
	 * @access public
	 * @param string $place_type
	 * @param int $place_id
	 * @return void
	 */
	public function get_place_name($place_type, $place_id)
	{
		// if ($place_type == 'store') {
		// 	$query = $this->idb->select('store_name AS name')->where('id', $place_id)->get('stores')->row_array();
		// } elseif ($place_type == 'warehouse') {
		// 	$query = $this->idb->select('warehouse_name AS name')->where('id', $place_id)->get('warehouses')->row_array();
		// } else {
		// 	return false;
		// }
		
		// return $query ? $query['name'] : '';
		return '';
	}
}