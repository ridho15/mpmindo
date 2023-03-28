<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bank_model extends MY_Model
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
	 * Check code
	 * 
	 * @access public
	 * @param string $code
	 * @param int $bank_id
	 * @return bool
	 */
	public function check_code($code, $bank_id)
	{
		if ($bank_id) {
			$this->db->where('bank_id !=', (int)$bank_id);
		}
		
		return (bool)$this->db
		->select('bank_id')
		->where('code', (string)$code)
		->count_all_results('bank');
	}
	
	/**
	 * Get bank name
	 * 
	 * @access public
	 * @param int $bank_id
	 * @return string
	 */
	public function get_bank_name($bank_id)
	{
		$query = $this->db
		->select('name')
		->where('bank_id', (int)$bank_id)
		->get('bank')
		->row_array();
		
		return $query ? $query['name'] : false;
	}
}