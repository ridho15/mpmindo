<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Address extends User_Controller
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
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('address_model');
	}
	
	public function get_address()
	{
		$this->load->model('address_model');
		
		$this->output->set_output(json_encode($this->address_model->get($this->input->get('address_id'))));
	}
	
	public function create()
	{
		check_ajax();
		
		$this->load->vars(array('heading_title' => 'Tambah Alamat'));
		
		$this->form();
	}
	
	public function edit()
	{
		check_ajax();
		
		$this->load->vars(array('heading_title' => 'Edit Alamat'));
		
		$this->form($this->input->get('address_id'));
	}
	
	public function form($address_id = null)
	{	
		$this->load->model('location_model');
		
		if ($address_id) {
			foreach ($this->address_model->get($address_id) as $key => $value) {
				$data[$key] = $value;
			}
		} else {
			foreach ($this->address_model->list_fields() as $field) {
				$data[$field] = '';
			}
		}
		
		$data['default'] = false;
		
		if ($user = $this->user_model->get($this->user->user_id())) {
			if ($user['address_id'] == $address_id) {
				$data['default'] = true;
			}
		}
		
		$data['user_id'] = $this->user->user_id();
		$data['action'] = user_url('address/validate');
		$data['provinces'] = $this->location_model->get_provinces();
		
		$this->output->set_output(json_encode(array(
			'content' => $this->load->layout(false)->view('user/address_form', $data, true)
		)));
	}
	
	public function validate()
	{
		check_ajax();
		
		$json = array();
		
		$this->form_validation
		->set_rules('name', 'Nama Alamat', 'trim|required')
		->set_rules('address', 'Alamat', 'trim|required')
		->set_rules('postcode', 'Kode Pos', 'trim|required|numeric|min_length[5]')
		->set_rules('province_id', 'Provinsi', 'trim|required')
		->set_rules('city_id', 'Kota / Kabupaten', 'trim|required')
		->set_rules('subdistrict_id', 'Kecamatan', 'trim|required')
		->set_error_delimiters('', '');
		
		if ($this->form_validation->run() == false) {
			foreach ($this->form_validation->get_errors() as $field => $error) {
				$json['errors'][$field] = $error;
			}
		} else {
			$address_id = $this->input->post('address_id');
			$data = $this->input->post(null, true);
			$data['user_id'] = $this->user->user_id();
			
			if ($address_id) {
				$this->address_model->update($address_id, $data);
				$success = 'Data alamat berhasil diperbarui';
			} else {
				$address_id = $this->address_model->insert($data);
				$success = 'Berhasil menambahkan alamat baru';
			}
			
			if ($this->input->post('default')) {
				$this->user_model->set_default_address($this->user->user_id(), $address_id);
			}
			
			$json['success'] = $success;
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	public function delete()
	{
		check_ajax();
		
		$json = array();
		
		$address_id = $this->input->post('address_id');
		
		if (empty($json['error'])) {
			$this->address_model->delete_by(array('address_id' => $address_id, 'user_id' => $this->user->user_id()));
			$json['success'] = 'Berhasil: Alamat telah berhasil dihapus!';
		}
		
		$this->output->set_output(json_encode($json));
	}
} 