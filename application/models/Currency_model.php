<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Currency_Model extends MY_Model
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
	 * Get active currencies
	 * 
	 * @access public
	 * @return array
	 */
	public function get_currencies()
	{
		return $this->db
		->where('active', 1)
		->order_by('code', 'asc')
		->get('currency')
		->result_array();
	}
	
	/**
	 * Get active currency by code
	 * 
	 * @access public
	 * @param string $code
	 * @return array
	 */
	public function get_currency_by_code($code)
	{
		return $this->db
		->where('code', $code)
		->where('active', 1)
		->get('currency')
		->row_array();
	}
}