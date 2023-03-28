<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Registration_product_model extends MY_Model
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
	public function create_registration_product($data)
	{
		$reg_id = $this->insert($data);
	
		return $reg_id;
	}

	/**
	 * Get reg sale offline
	 * 
	 * @access public
	 * @param int $reg_id
	 * @return int
	 */
	public function get_registration_product($reg_id)
	{
		$result = $this->db
		->select('*')
		->where('id', (int)$reg_id)
		->order_by('id', 'desc')
		->limit(1);
		
		$result = $this->db->get('registration_product')->row_array();
		
		return ($result) ? $result : array();
	}

	/**
	 * update start warranty
	 * 
	 * @access public
	 * @param int $reg_id
	 * @param int $start_warranty
	 * @return array
	 */
	public function update_start_warranty($reg_id, $start_warranty, $updated_date, $updated_by)
	{
		$this->db->where('id', (int)$reg_id);
		$this->db->set('start_warranty', $start_warranty);
		$this->db->set('updated_date', $updated_date);
		$this->db->set('updated_by', $updated_by);
		$this->db->update('registration_product');
	}

	/**
	 * Get reg sale offline
	 * 
	 * @access public
	 * @param int $reg_id
	 * @return int
	 */
	public function check_product_registration_product($product_code)
	{
		$result = $this->db
		->select('count(0) as qty')
		->where('product_code', $product_code);
		
		$result = $this->db->get('registration_product')->row_array();
		
		return ($result) ? $result['qty'] : 0;
	}

	/**
	 * Create registration product detail
	 * 
	 * @access public
	 * @param array $data
	 * @return void
	 */
	public function create_registration_product_staff($header, $detail, $detail_id)
	{
		if(isset($header["invoice_image"])) $image = $header["invoice_image"];
		else $image = "";
		$data = array(
			'register_date' => $header["register_date"],
			'shop_name' => $header["shop_name"],
			'product_code' => $detail["product_code"],
			'product_name' => $detail["product_name"],
			'user_name' => $header["registrant"],	
			'user_email' => $header["registrant_email"],
			"start_warranty" => $header["start_warranty"],
			"register_type" => 'reg_by_staff',
			"register_id" => $detail_id,
			"notes" => $header["notes"],
			"warehouse_id" => $header["warehouse_id"],
			"invoice_image" => $image
		);

		$reg_id = $this->db->insert('registration_product', $data);
	
		return $reg_id;
	}

	/**
	 * Create registration product detail
	 * 
	 * @access public
	 * @param array $data
	 * @return void
	 */
	public function create_registration_product_customer($header, $detail, $detail_id)
	{
		if(isset($header["invoice_image"])) $image = $header["invoice_image"];
		else $image = "";
		$data = array(
			'register_date' => $header["register_date"],
			'shop_name' => $header["shop_name"],
			'product_code' => $detail["product_code"],
			'product_name' => $detail["product_name"],
			'user_name' => $header["registrant"],	
			'user_email' => $header["registrant_email"],
			"start_warranty" => $header["start_warranty"],
			"register_type" => 'reg_by_customer',
			"register_id" => $detail_id,
			"notes" => $header["notes"],
			"invoice_image" => $image
		);

		$reg_id = $this->db->insert('registration_product', $data);
	
		return $reg_id;
	}

	/**
	 * Create registration product detail
	 * 
	 * @access public
	 * @param array $data
	 * @return void
	 */
	public function create_registration_product_order($header, $detail)
	{
		$data = array(
			'register_date' => $header["date_added"],
			'shop_name' => 'Ecommerce',
			'product_code' => $detail["product_code"],
			'product_name' => $detail["name"],
			'user_id' => $header["user_id"],
			'user_name' => $header["user_name"],	
			'user_email' => $header["user_email"],
			'user_telephone' => $header["user_telephone"],
			"start_warranty" => date('Y-m-d',strtotime($header["date_added"])),
			"register_type" => 'reg_by_online',
			"register_id" => $detail["id"],
		);

		$reg_id = $this->db->insert('registration_product', $data);
	
		return $reg_id;
	}

	/**
	 * Get reg sale offline
	 * 
	 * @access public
	 * @param int $reg_id
	 * @return int
	 */
	public function get_registration_product_online($reg_id)
	{
		$result = $this->db
		->select('*')
		->where('register_id', (int)$reg_id)
		->where('register_type', 'reg_by_online')
		->order_by('register_id', 'desc')
		->limit(1);
		
		$result = $this->db->get('registration_product')->row_array();
		
		return ($result) ? $result : array();
	}

	/**
	 * Get reg sale offline
	 * 
	 * @access public
	 * @param int $reg_id
	 * @return int
	 */
	public function get_registration_product_by_product_code($product_code)
	{
		$result = $this->db
		->select('*')
		->where('product_code', $product_code)
		->order_by('id', 'desc')
		->limit(1);
		
		$result = $this->db->get('registration_product')->row_array();
		
		return ($result) ? $result : array();
	}

	/**
	 * update product code
	 * 
	 * @access public
	 * @param int $reg_id
	 * @param int $start_warranty
	 * @return array
	 */
	public function update_product_code($reg_id, $product_code, $updated_date, $updated_by)
	{
		$this->db->where('id', (int)$reg_id);
		$this->db->set('product_code', $product_code);
		$this->db->set('updated_date', $updated_date);
		$this->db->set('updated_by', $updated_by);
		$this->db->update('registration_product');
	}
}