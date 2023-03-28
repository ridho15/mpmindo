<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Midtrans_model extends MY_Model
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
	 * Get payment methods
	 * 
	 * @access public
	 * @return array
	 */
	public function get_methods()
	{
		return $this->db
		->where('active', 1)
		->order_by('sort_order', 'asc')
		->get('midtrans_method')
		->result_array();
	}
	
	/**
	 * Get payment method
	 * 
	 * @access public
	 * @return array
	 */
	public function get_method($code)
	{
		return $this->db
		->where('active', 1)
		->where('code', $code)
		->get('midtrans_method')
		->row_array();
	}
}