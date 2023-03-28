<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Address_Model extends MY_Model
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
	 * Delete address
	 * 
	 * @access public
	 * @param int $address_id
	 * @return void
	 */
	public function delete_address($address_id)
	{
		$this->db
		->where('address_id', (int)$address_id)
		->where('user_id', (int)$this->user->user_id())
		->delete('address');
	}	
	
	/**
	 * Get address
	 * 
	 * @access public
	 * @param int $address_id
	 * @return array
	 */
	public function get_address($address_id, $user_id = false)
	{
		$this->db
		->select("a.*, l3.name as province, CONCAT_WS(' ', l2.type, l2.name) as city, l.name as subdistrict", false)
		->from('address a')
		->join('location l', 'l.subdistrict_id = a.subdistrict_id', 'left')
		->join('location l2', 'l2.city_id = a.city_id', 'left')
		->join('location l3', 'l3.province_id = a.province_id and l3.city_id = 0', 'left')
		->where('a.address_id', (int)$address_id);
		
		if ($user_id) {
			$this->db->where('a.user_id', (int)$user_id);
		}
		
		return $this->db
		->get()
		->row_array();
	}
	
	/**
	 * Get user addresses
	 * 
	 * @access public
	 * @return array
	 */
	public function get_addresses($user_id)
	{
		return $this->db
		->select("a.*, l3.name as province, CONCAT_WS(' ', l2.type, l2.name) as city, l.name as subdistrict", false)
		->from('address a')
		->join('location l', 'l.subdistrict_id = a.subdistrict_id', 'left')
		->join('location l2', 'l2.city_id = a.city_id and l2.subdistrict_id = 0', 'left')
		->join('location l3', 'l3.province_id = a.province_id and l3.city_id = 0', 'left')
		->where('a.user_id', (int)$user_id)
		->get()
		->result_array();
	}
	
	/**
	 * Count user addresses
	 * 
	 * @access public
	 * @return int
	 */
	public function count_addresses($user_id)
	{
		return $this->db
		->select('address_id')
		->where('user_id', (int)$user_id)
		->count_all_results('address');
	}
	
	/**
	 * Check user address
	 * 
	 * @access public
	 * @param int $address_id
	 * @param int $user_id
	 * @return bool
	 */
	public function check_address($address_id, $user_id = null)
	{
		return (bool)$this->db
		->select('address_id')
		->where('address_id', (int)$address_id)
		->where('user_id', (int)$user_id)
		->count_all_results('address');
	}
	
	public function set_default($address_id, $user_id)
	{
		$this->db
		->where('address_id', (int)$address_id)
		->set('user_id', 0)
		->update('address');
	}

	/**
	 * Delete address user
	 * 
	 * @access public
	 * @param int $user_id
	 * @return void
	 */
	public function delete_address_user($user_id)
	{
		$this->db
		->where('user_id', (int)$user_id)
		->delete('address');
	}	

	/**
	 * Delete address user array
	 * 
	 * @access public
	 * @param int $user_id
	 * @return void
	 */
	public function delete_address_user_array($user_id)
	{
		$this->db
		->where_in('user_id', $user_id)
		->delete('address');
	}	
}