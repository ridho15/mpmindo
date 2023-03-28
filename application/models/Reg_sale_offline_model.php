<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Reg_sale_offline_model extends MY_Model
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
	 * Create reg sale offline
	 * 
	 * @access public
	 * @param array $data
	 * @return void
	 */
	public function create_reg_sale_offline($data)
	{
		$reg_id = $this->insert($data);
	
		return $reg_id;
	}

	/**
	 * Get reg sale offline
	 * 
	 * @access public
	 * @param int $reg_id
	 * @return int
	 */
	public function get_reg_sale_offline($reg_id)
	{
		$result = $this->db
		->select('*')
		->where('id', (int)$reg_id)
		->order_by('id', 'desc')
		->limit(1);
		
		$result = $this->db->get('reg_sale_offline')->row_array();
		
		return ($result) ? $result : array();
	}

	/**
	 * update start warranty
	 * 
	 * @access public
	 * @param int $reg_id
	 * @param int $start_warranty
	 * @return array
	 */
	public function update_start_warranty($reg_id, $start_warranty, $updated_date, $updated_by)
	{
		$this->db->where('id', (int)$reg_id);
		$this->db->set('start_warranty', $start_warranty);
		$this->db->set('updated_date', $updated_date);
		$this->db->set('updated_by', $updated_by);
		$this->db->update('reg_sale_offline');
	}

	/**
	 * Get reg sale offline
	 * 
	 * @access public
	 * @param int $reg_id
	 * @return int
	 */
	public function check_product_reg_sale_offline($product_code)
	{
		$result = $this->db
		->select('count(0) as qty')
		->where('product_code', $product_code);
		
		$result = $this->db->get('reg_sale_offline_detail')->row_array();
		
		return ($result) ? $result['qty'] : 0;
	}

	/**
	 * Get reg sale offline detail
	 * 
	 * @access public
	 * @param int $detail_id
	 * @return int
	 */
	public function get_reg_sale_offline_detail($detail_id)
	{
		$result = $this->db
		->select('*')
		->where('id', (int)$detail_id)
		->order_by('id', 'desc')
		->limit(1);
		
		$result = $this->db->get('reg_sale_offline_detail')->row_array();
		
		return ($result) ? $result : array();
	}
}