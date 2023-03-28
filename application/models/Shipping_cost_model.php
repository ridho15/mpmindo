<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Shipping_cost_Model extends MY_Model
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
	 * Get cost
	 * 
	 * @access public
	 * @param string $type
	 * @param int $id
	 * @return float
	 */
	public function get_cost($type, $id)
	{
		if ( ! in_array($type, array('city', 'subdistrict'))) {
			return false;
		}
		
		return $this->db
		->select('cost')
		->where($type.'_id', $id)
		->order_by('shipping_cost_id', 'desc')
		->get('shipping_cost')
		->row_array();
	}
}