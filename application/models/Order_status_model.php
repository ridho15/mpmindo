<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_Status_Model extends MY_Model
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
	 * Get statuses
	 * 
	 * @access public
	 * @return array
	 */
	public function get_statuses()
	{
		return $this->db
		->select('order_status_id, name')
		->from('order_status')
		->order_by('name', 'asc')
		->get()
		->result_array();
	}
}