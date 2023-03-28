<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Registration_product_history_model extends MY_Model
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
	 * Create registration product
	 * 
	 * @access public
	 * @param array $data
	 * @return void
	 */
	public function create_registration_product_history($data)
	{
		$reg_id = $this->insert($data);
	
		return $reg_id;
	}
}