<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Store_model extends CI_Model
{
	private $idb;
	
	/**
	 * Constructor
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->idb = $this->load->database('inventory', true);
	}
	
	/**
	 * Get all stores
	 * 
	 * @access public
	 * @return void
	 */
	public function get_all()
	{
		return $this->idb
		->select('id as store_id, store_name as name, store_code as code, store_address as address, store_phone as telephone, price_increment_percent')
		->where('status', 'Aktif')
		->get('stores')
		->result_array();
	}
}