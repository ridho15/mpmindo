<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class List_distributor_model extends MY_Model
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
	 * Create new admin
	 * 
	 * @access public
	 * @param array $data
	 * @return int | bool
	 */
	public function create_list_distributor($data = array())
	{
		$this->db
		->set($this->set_data($data))
		->insert('list_distributor');
		
		$list_distributor_id= $this->db->insert_id();

		if ($list_distributor_id) {
			$distributor = $this->db
			->select('list_distributor.list_distributor_id,list_distributor.country,list_distributor.category,list_distributor.business,list_distributor.haircut,list_distributor.sales,list_distributor.repair,list_distributor.spare_part,list_distributor.company_name,list_distributor.company_name,list_distributor.shop_name,list_distributor.address_name,list_distributor.address,list_distributor.post_code,list_distributor.province,list_distributor.city,list_distributor.sub_district,list_distributor.latitude,list_distributor.longitude,list_distributor.country_code,list_distributor.number_phone,list_distributor.facebook,list_distributor.instagram,list_distributor.twiter,list_distributor.youtube,list_distributor.linkedin,list_distributor.tiktok,list_distributor.website', (int)$list_distributor_id)
			->get('list_distributor')
			->row_array();

		}
		return $list_distributor_id;
	}

	
	/**
	 * Update existing admin
	 * 
	 * @access public
	 * @param array $data
	 * @return int | bool
	 */
	public function update_list_distributor($list_distributor_id, $data = array())
	{
		$this->load->helper('string');
		$this->load->helper('security');
		
		$this->db
		->set($this->set_data($data))
		->where('list_distributor_id', (int)$list_distributor_id)
		->update('list_distributor');
		
		return $this->db->affected_rows();
	}
	
	/**
	 * Get admin
	 * 
	 * @access public
	 * @param int $admin_id
	 * @return array
	 */
	public function get_list_distributor()
	{
		return $this->db
		->select('list_distributor.list_distributor_id,list_distributor.company_name,list_distributor.shop_name,list_distributor.address,list_distributor.number_phone,list_distributor.country_code,list_distributor.country,list_distributor.category,list_distributor.business,list_distributor.haircut,list_distributor.sales,list_distributor.repair,list_distributor.spare_part,list_distributor.address_name,list_distributor.post_code,list_distributor.province,list_distributor.city,list_distributor.sub_district,list_distributor.latitude,list_distributor.longitude,list_distributor.facebook,list_distributor.instagram,list_distributor.twiter,list_distributor.youtube,list_distributor.linkedin,list_distributor.tiktok,,list_distributor.website')
		->get('list_distributor')
		->result_array();
	}

	public function get_province()
	{
		$this->db->select('province');
		$this->db->distinct();
		return $this->db->get('list_distributor')->result_array();
	}

	public function get_city($province)
	{
		$this->db->select('city');
		$this->db->distinct();
		$this->db->where('province',$province);
		return $this->db->get('list_distributor')->result_array();
	}

	public function get_province_name($province_id)
	{
		$this->db->where('type','Provinsi');
		$this->db->where('province_id',$province_id);
		return $this->db->get('location')->row_array();
	}

	public function get_city_name($city_id)
	{
		$this->db->where('city_id',$city_id);
		$this->db->group_start()
		->where('type','Kota')
		->or_where('type','Kabupaten')
		->group_end();
		return $this->db->get('location')->row_array();
	}

	public function get_company_name($province,$city)
	{
		$this->db->where('province',$province);
		$this->db->where('city',$city);
		return $this->db->get('list_distributor')->result_array();
	}

	public function get_province_dua($province_id)
	{
		$this->db->where('type','Provinsi');
		$this->db->where('province_id',$province_id);
		return $this->db->get('location')->row_array();
	}

	public function get_city_dua($city)
	{
		$this->db->where('city_id',$city);
		return $this->db->get('location')->row_array();
	}

	public function get_subdistrict($subdistrict)
	{
		$this->db->where('subdistrict_id',$subdistrict);
		return $this->db->get('location')->row_array();
	}
	
	/**
	 * Get admins
	 * 
	 * @access public
	 * @return array
	 */
	
}