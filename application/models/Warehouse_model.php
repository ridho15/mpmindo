<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Warehouse_model extends MY_Model
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
	 * Code exists validation
	 * 
	 * @access public
	 * @param string $code
	 * @param int $warehouse_id Exclude warehouse ID
	 * @return bool
	 */
	public function check_code($code = null, $warehouse_id = null)
	{
		$this->db->select('code');
		
		if ($warehouse_id) {
			$this->db->where('warehouse_id !=', (int)$warehouse_id);
		}
		
		return (bool)$this->db
		->where('code', strtoupper($code))
		->count_all_results('warehouse');
	}
	
	/**
	 * Check stock
	 * 
	 * @access public
	 * @param int $warehouse_id
	 * @return bool
	 */
	public function check_stock($warehouse_id)
	{
		$result = $this->db
		->select_sum('quantity')
		->where('warehouse_id', (int)$warehouse_id)
		->get('inventory')
		->row_array();
		
		return ($result) ? (bool)$result['quantity'] : false;
	}

	/**
	 * Get warehouse
	 * 
	 * @access public
	 * @param int $warehouse_id
	 * @return int
	 */
	public function get_warehouse($warehouse_id)
	{
		$result = $this->db
		->select('*')
		->where('warehouse_id', (int)$warehouse_id)
		->order_by('warehouse_id', 'desc')
		->limit(1);
		
		$result = $this->db->get('warehouse')->row_array();
		
		return ($result) ? $result : array();
	}

	/**
	 * Get all warehouse
	 * 
	 * @access public
	 * @return int
	 */
	public function get_all_warehouse()
	{
		$result = $this->db
		->select('*')
		->order_by('code asc, name asc');
		
		$result = $this->db->get('warehouse')->result_array();
		
		return ($result) ? $result : array();
	}
}