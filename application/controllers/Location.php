<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Location extends MY_Controller
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
		
		$this->load->model('location_model');
	}
	
	public function province()
	{
		$json = array();
		
		if ($this->input->get('province_id')) {
			$province_info = $this->location_model->get_province($this->input->get('province_id'));
	
			if ($province_info) {
				$json = array(
					'province_id' => $province_info['province_id'],
					'name' => $province_info['name'],
					'cities' => $this->location_model->get_cities($this->input->get('province_id')),
				);
			}
		}

		$this->output->set_header('Content-Type: application/json');
		$this->output->set_output(json_encode($json));
	}
	
	public function city()
	{
		$json = array();
		
		if ($this->input->get('city_id')) {
			$city_info = $this->location_model->get_city($this->input->get('city_id'));
	
			if ($city_info) {
				$json = array(
					'city_id' => $city_info['city_id'],
					'name' => $city_info['name'],
					'subdistricts' => $this->location_model->get_subdistricts($this->input->get('city_id')),
				);
			}	
		}

		$this->output->set_header('Content-Type: application/json');
		$this->output->set_output(json_encode($json));
	}
}