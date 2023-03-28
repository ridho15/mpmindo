<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_trans_model extends MY_Model
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
	 * Get inventory trans
	 * 
	 * @access public
	 * @param int $inventory_trans_id
	 * @return array
	 */
	public function get_inventory_trans($inventory_trans_id = null)
	{
		$inventory_trans = array();
		
		$inventory_trans_query = $this->db
		->select('it.*, wf.name as warehouse_from_name, wt.name as warehouse_to_name', false)
		->from('inventory_trans it')
		->join('warehouse wf', 'wf.warehouse_id = it.warehouse_from', 'left')
		->join('warehouse wt', 'wt.warehouse_id = it.warehouse_to', 'left')
		->where('it.inventory_trans_id', (int)$inventory_trans_id)
		->get()
		->row_array();
		
		if ($inventory_trans_query) {	
			$inventory_trans = $inventory_trans_query;
			
			$inventory_trans['inventories'] = $this->db
			->select('i.*, p.name as product, wf.name as warehouse_name', false)
			->from('inventory i')
			->join('product p', 'p.product_id = i.product_id', 'left')
			->join('warehouse wf', 'wf.warehouse_id = i.warehouse_id', 'left')
			->where('i.trans_table', 'inventory_trans')
			->where('i.trans_table_id', (int)$inventory_trans['inventory_trans_id'])
			->order_by('inventory_id','desc')
			->get()
			->result_array();
		}
		
		return $inventory_trans;
	}
	
	/**
	 * Get inventories
	 * 
	 * @access public
	 * @param string $trans_table
	 * @param mixed $trans_table_id
	 * @return array
	 */
	public function get_inventories($trans_table = '', $trans_table_id = null)
	{
		return $this->db
		->where('trans_table', $trans_table)
		->where('trans_table_id', $trans_table_id)
		->get('inventory')
		->result_array();
	}
}