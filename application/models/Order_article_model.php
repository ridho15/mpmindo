<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_Article_Model extends MY_Model
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
	 * Get order articles
	 * 
	 * @access public
	 * @param int $order_id
	 * @return array
	 */
	public function get_order_articles($order_id)
	{
		return $this->db
		->select('oa.*')
		->from('order_article oa')
		->where('oa.order_id', (int)$order_id)
		->get()
		->result_array();
	}

	/**
	 * Get order article
	 * 
	 * @access public
	 * @param int $order_id
	 * @return array
	 */
	public function get_order_article($id)
	{
		return $this->db
		->select('oa.*')
		->from('order_article oa')
		->where('oa.id', (int)$id)
		->get()
		->row_array();
	}
}