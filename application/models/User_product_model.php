<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_Product_Model extends MY_Model
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
	
	public function get_products($user_id, $params = array())
	{
		$this->db->select('*');
		$this->db->from('user_product');
		$this->db->where('user_id', (int)$user_id);
		
		if (isset($params['active'])) {
			$this->db->where('active', (int)$params['active']);
		}

		if ( ! empty($data['search'])) {
			$this->db->like('name', $data['search'], 'both');
		}
		
		$this->db->group_by('user_product_id');

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) $data['start'] = 0;
			if ($data['limit'] < 1) $data['limit'] = 20;
			
			$this->db->limit((int)$data['limit'], (int)$data['start']);
		}
		
		return $this->db->get()->result_array();
	}
	
	public function count_products($user_id, $params = array())
	{
		$this->db->select('user_product_id');
		$this->db->where('user_id', (int)$user_id);

		if (isset($params['active'])) {
			$this->db->where('active', (int)$params['active']);
		}

		if ( ! empty($params['search'])) {
			$this->db->like('name', $params['search'], 'both');
		}
		
		return (int)$this->db->count_all_results('user_product');
	}
	
	public function delete_product($user_product_id, $user_id)
	{
		$this->db
		->where('user_product_id', (int)$user_product_id)
		->where('user_id', (int)$user_id)
		->delete('user_product');
	}
}