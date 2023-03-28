<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_Shipping_Model extends MY_Model
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
	 * Get order shipping
	 * 
	 * @access public
	 * @param int $order_id
	 * @param bool $cod
	 * @return void
	 */
	public function get_order_shipping($order_id = null, $cod = false)
	{
		return $this->db
		->where('order_id', (int)$order_id)
		->where('cod', (int)$cod)
		->order_by('date_added', 'desc')
		->limit(1)
		->get('order_shipping')
		->row_array();
	}
	
	/**
	 * Get order shipping
	 * 
	 * @access public
	 * @param int $order_id
	 * @param bool $cod
	 * @return void
	 */
	public function get_order_shippings($order_id = null, $cod = false)
	{
		return $this->db
		->where('order_id', (int)$order_id)
		->where('cod', (int)$cod)
		->order_by('date_added', 'desc')
		->get('order_shipping')
		->result_array();
	}
}