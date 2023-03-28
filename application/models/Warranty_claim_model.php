<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Warranty_claim_model extends MY_Model
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
	 * Create reg sale offline
	 * 
	 * @access public
	 * @param array $data
	 * @return void
	 */
	public function create_warranty_claim($data)
	{
		$reg_id = $this->insert($data);
	
		return $reg_id;
	}

	/**
	 * Get last claim code
	 * 
	 * @access public
	 * @return int
	 */
	public function get_last_claim_code()
	{
		$result = $this->db
		->select('warranty_code')
		->order_by('id', 'desc')
		->limit(1);
		
		$result = $this->db->get('warranty_claim')->row_array();
		
		return ($result) ? $result['warranty_code'] : '';
	}

	/**
	 * Get count article active warranty exist
	 * 
	 * @access public
	 * @param int $article_id
	 * @return int
	 */
	public function get_count_article_active_warranty($product_code)
	{
		$result = $this->db
		->select('count(0) as total')
		->where('product_code', $product_code)
		->where('status != ', 'Selesai')
		->where('status != ', 'Klaim Ditolak');
		
		$result = $this->db->get('warranty_claim')->row_array();
		
		return ($result) ? $result['total'] : 0;
	}

	/**
	 * Get warranty claim
	 * 
	 * @access public
	 * @param int $claim_id
	 * @return int
	 */
	public function get_warranty_claim($claim_id)
	{
		$result = $this->db
		->select('*')
		->where('id', (int)$claim_id)
		->order_by('id', 'desc')
		->limit(1);
		
		$result = $this->db->get('warranty_claim')->row_array();
		
		return ($result) ? $result : array();
	}

	/**
	 * update status
	 * 
	 * @access public
	 * @param int $claim_id
	 * @param int $start_warranty
	 * @return array
	 */
	public function update_status($claim_id, $status, $updated_date, $updated_by, $response_note, $response_photo)
	{
		$this->db->where('id', (int)$claim_id);
		$this->db->set('status', $status);
		$this->db->set('response_note', $response_note);
		$this->db->set('response_photo', $response_photo);
		$this->db->set('update_date', $updated_date);
		$this->db->set('update_by', $updated_by);
		$this->db->update('warranty_claim');
	}

	/**
	 * update status by customer
	 * 
	 * @access public
	 * @param int $claim_id
	 * @param int $start_warranty
	 * @return array
	 */
	public function update_status_by_customer($claim_id, $status, $updated_date, $updated_by)
	{
		$this->db->where('id', (int)$claim_id);
		$this->db->set('status', $status);
		$this->db->set('update_date', $updated_date);
		$this->db->set('update_by', $updated_by);
		$this->db->update('warranty_claim');
	}

	/**
	 * Get active warranty claim by email
	 * 
	 * @access public
	 * @param string $email
	 * @return int
	 */
	public function get_active_warranty_claim_by_email($email)
	{
		$result = $this->db
		->select('id')
		->where('user_email', $email)
		->where('create_source', 'user')
		->where_not_in('status', array('Selesai','Klaim Ditolak'))
		->order_by('id', 'desc');
		
		$result = $this->db->get('warranty_claim')->result_array();
		
		return ($result) ? $result : array();
	}

	/**
	 * update information
	 * 
	 * @access public
	 * @param int $claim_id
	 * @param int $start_warranty
	 * @return array
	 */
	public function update_information($claim_id, $user_phone, $user_address, $claim_by)
	{
		$this->db->where('id', (int)$claim_id);
		$this->db->set('user_phone', $user_phone);
		$this->db->set('user_address', $user_address);
		$this->db->set('claim_by', $claim_by);
		$this->db->update('warranty_claim');
	}

	/**
	 * update new product code
	 * 
	 * @access public
	 * @param int $claim_id
	 * @param string $new_product_code
	 * @return array
	 */
	public function update_new_product_code($claim_id, $new_product_code)
	{
		$this->db->where('id', (int)$claim_id);
		$this->db->set('new_product_code', $new_product_code);
		$this->db->update('warranty_claim');
	}

	/**
	 * update transfer done
	 * 
	 * @access public
	 * @param int $claim_id
	 * @param string $new_product_code
	 * @return array
	 */
	public function update_claim_transfer_done($claim_id, $transfer_photo, $status, $updated_date, $updated_by)
	{
		$this->db->where('id', (int)$claim_id);
		$this->db->set('transfer_photo', $transfer_photo);
		$this->db->set('status', $status);
		$this->db->set('update_date', $updated_date);
		$this->db->set('update_by', $updated_by);
		$this->db->update('warranty_claim');
	}
}