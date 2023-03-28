<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Location_Model extends MY_Model
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
		
		$this->load->library('rajaongkir');
	}
	
	public function get_province($province_id)
	{
		if (empty($province_id)) {
			return false;
		}
		
		return $this->db
		->where('province_id', (int)$province_id)
		->where('type', 'Provinsi')
		->order_by('name', 'asc')
		->get('location')
		->row_array();
	}

	public function get_provinces()
	{
		$provinces = $this->db
		->where('type', 'Provinsi')
		->order_by('name', 'asc')
		->get('location')
		->result_array();
		
		if ( ! $provinces) {
			$result = $this->rajaongkir->province();
			
			$provinces = array();
			
			if ($result) {
				foreach ($result as $province) {
					$data = array(
						'province_id' => (int)$province['province_id'],
						'city_id' => 0,
						'subdistrict_id' => 0,
						'type' => 'Provinsi',
						'postcode' => '',
						'name' => $province['province'],
					);
					
					$this->insert($data);
						
					$provinces[] = array(
						'province_id' => (int)$data['province_id'],
						'name' => $data['name']
					);
				}
			}
		}

		return $provinces;
	}
	
	public function get_subdistrict($subdistrict_id)
	{
		if (empty($subdistrict_id)) {
			return false;
		}
		
		return $this->db
		->where('subdistrict_id', (int)$subdistrict_id)
		->where('type', 'Kecamatan')
		->order_by('name', 'asc')
		->get('location')
		->row_array();
	}
	
	public function get_subdistricts($city_id) {
		if (empty($city_id)) {
			return array();
		}
		
		$subdistricts = $this->db
		->where('city_id', (int)$city_id)
		->where('type', 'Kecamatan')
		->order_by('name', 'asc')
		->get('location')
		->result_array();
		
		if ( ! $subdistricts) {
			$result = $this->rajaongkir->subdistrict($city_id);
			
			$subdistricts = array();
			
			if ($result) {
				foreach ($result as $subdistrict) {
					$data = array(
						'province_id' => (int)$subdistrict['province_id'],
						'city_id' => (int)$subdistrict['city_id'],
						'subdistrict_id' => (int)$subdistrict['subdistrict_id'],
						'type' => 'Kecamatan',
						'postcode' => '',
						'name' => $subdistrict['subdistrict_name'],
					);
					
					$this->insert($data);
						
					$subdistricts[] = array(
						'subdistrict_id' => (int)$data['subdistrict_id'],
						'name' => $data['name']
					);
				}
			}
		}

		return $subdistricts;
	}
	
	public function get_city($city_id) {
		if (empty($city_id)) {
			return false;
		}
		
		return $this->db
		->select('city_id, CONCAT_WS(" ", type, name) as name', false)
		->where('city_id', (int)$city_id)
		->where('subdistrict_id', 0)
		->order_by('name', 'asc')
		->get('location')
		->row_array();
	}

	public function get_cities($province_id) {
		if (empty($province_id)) {
			return array();
		}

		$cities = $this->db
		->select('city_id, CONCAT_WS(" ", type, name) as name', false)
		->where('province_id', (int)$province_id)
		->where('(type = \'Kota\' OR type = \'Kabupaten\')', null, false)
		->order_by('name', 'asc')
		->get('location')
		->result_array();
			
		if (!$cities) {
			$results = $this->rajaongkir->city($province_id);
			
			foreach ($results as $result) {
				$data = array(
					'province_id' => (int)$result['province_id'],
					'city_id' => (int)$result['city_id'],
					'subdistrict_id' => 0,
					'type' => $result['type'],
					'postcode' => $result['postal_code'],
					'name' => $result['city_name'],
				);
				
				$this->insert($data);
				
				$cities[] = array(
					'city_id' => (int)$data['city_id'],
					'name' => $data['type'].' '.$data['name'],
					'postcode' => $data['postcode']
				);
			}
		}
		
		return $cities;
	}
}