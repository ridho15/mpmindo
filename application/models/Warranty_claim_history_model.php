<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Warranty_claim_history_model extends MY_Model
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
	 * Create warranty claim history
	 * 
	 * @access public
	 * @param array $data
	 * @return void
	 */
	public function create_warranty_claim_history($data)
	{
		$reg_id = $this->insert($data);
	
		return $reg_id;
	}

	/**
	 * Get warranty claim history
	 * 
	 * @access public
	 * @param int $claim_id
	 * @return int
	 */
	public function get_warranty_claim_history($claim_id)
	{
		$result = $this->db
		->select('*')
		->where('warranty_claim_id', (int)$claim_id)
		->order_by('id', 'desc');
		
		$result = $this->db->get('warranty_claim_history')->result_array();
		
		return ($result) ? $result : array();
	}

	/**
	 * update information
	 * 
	 * @access public
	 * @param int $claim_id
	 * @param int $start_warranty
	 * @return array
	 */
	public function update_information($claim_id, $user_phone, $user_address, $user_name)
	{
		$this->db->where('id', (int)$claim_id);
		$this->db->set('user_phone', $user_phone);
		$this->db->set('user_address', $user_address);
		$this->db->set('user_name', $user_name);
		$this->db->update('warranty_claim_history');
	}
}