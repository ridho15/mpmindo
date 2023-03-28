<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_view_user_model extends MY_Model
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
	 * Add record
	 * 
	 * @access public
	 * @param int $product_id
	 * @param int $user_id
	 * @return void
	 */
	public function add($product_id = null, $user_id = null)
	{
		$this->insert(array(
			'product_id' => $product_id,
			'view_date' => date('Y-m-d H:i:s', time()),
			'user_id' => $user_id
		));
	}
}