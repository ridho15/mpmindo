<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends User_Controller
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
		
		$this->load->model('user_model');
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('user_agent');
	}
	
	/**
	 * Edit
	 * 
	 * @access public
	 * @return void
	 */
	public function edit()
	{
		$this->load->library('image');
		$this->load->model('location_model');
		$this->load->model('bank_model');
		
		foreach ($this->user_model->get($this->user->user_id()) as $key => $value) {
			$data[$key] = $value;
		}
		
		if ( ! empty($data['image'])) {
			$data['image'] = $this->image->resize($data['image'], 100, 100);
		} else {
			$data['image'] = $this->image->resize('no_image.jpg', 100, 100);
		}
		
		$data['action'] = user_url('profile/validate');
		$data['banks'] = $this->bank_model->get_all();
		$data['user_id'] = $this->user->user_id();
		$data['mobile'] = $this->agent->is_mobile();
		
		$this->load
		->breadcrumb('Akun', user_url())
		->breadcrumb('Edit Profil', user_url('profile/edit'))
		->view('user/profile_form', $data);
	}
	
	/**
	 * Validate
	 * 
	 * @access public
	 * @return void
	 */
	public function validate()
	{
		check_ajax();
		
		$json = array();
		
		if ($this->input->post('password') !== '') {
			$this->form_validation
			->set_rules('password', 'Password', 'trim|required|min_length[8]')
			->set_rules('confirm', 'Konfirmasi Password', 'trim|required|min_length[8]|matches[password]');
		}
		
		$this->form_validation
		->set_rules('name', 'Nama Lengkap', 'trim|required|min_length[5]')
		->set_rules('email', 'Email', 'trim|required|valid_email|callback__check_email|min_length[5]')
		->set_rules('gender', 'Jenis Kelamin', 'trim|required')
		->set_rules('telephone', 'No. Telepon / Handphone', 'trim|required|min_length[3]')
		->set_rules('job', 'Tempat Kerja', 'trim|required')
		->set_rules('job_status', 'Status Kerja', 'trim|required')
		->set_error_delimiters('', '');
		
		if ($this->form_validation->run() == false) {
			if ($this->input->post('password') !== '') {
				if (form_error('password') !== '') {
					$json['errors']['password'] = form_error('password');
				}
				
				if (form_error('confirm') !== '') {
					$json['errors']['confirm'] = form_error('confirm');
				}
			}
			
			foreach ($this->form_validation->get_errors() as $field => $error) {
				$json['errors'][$field] = $error;
			}
		} else {
			$this->user_model->update_user($this->user->user_id(), $this->input->post(null, true));

			//check active warranty claim, update name, phone, address
			$this->load->model('warranty_claim_model');
			$this->load->model('warranty_claim_history_model');

			$claim_ids = $this->warranty_claim_model->get_active_warranty_claim_by_email($this->input->post('email'));
			foreach ($claim_ids as $claim_id) {
				$this->warranty_claim_model->update_information($claim_id['id'], $this->input->post('telephone'), $this->input->post('user_address'), $this->input->post('name'));
				$histories = $this->warranty_claim_history_model->get_warranty_claim_history($claim_id['id']);
				foreach ($histories as $history) {
					$this->warranty_claim_history_model->update_information($history['id'], $this->input->post('telephone'), $this->input->post('user_address'), $this->input->post('name'));
				}
			}

			$json['success'] = 'Data profil Anda telah berhasil diperbarui';
			$this->session->set_flashdata('success', 'Edit profil berhasil');
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	/**
	 * Upload image file
	 * 
	 * @access public
	 * @return void
	 */
	public function upload()
	{
		$json = array();
		
		$upload_path = DIR_IMAGE.'user';
		
		if ( ! file_exists($upload_path)) {
			@mkdir($upload_path, 0777);
		}
		
		$this->load->library('upload', array(
			'upload_path' => $upload_path,
			'allowed_types' => 'gif|jpg|png|jpeg|heif|tiff',
			'max_size' => '5120',
			'encrypt_name' => true
		));

		if ( ! $this->upload->do_upload()) {
			$json['error'] = $this->upload->display_errors('', '');
		} else {
			$this->load->library('image');
			
			$upload_data = $this->upload->data();
			$image = substr($upload_data['full_path'], strlen(FCPATH.DIR_IMAGE));

			// check EXIF and autorotate if needed
			//$this->load->library('image_autorotate', array('filepath' => $image));
			
			$thumb = $this->image->resize($image, 128, 128);
			$this->user_model->update($this->user->user_id(), array('image' => $image));
			
			$json['image'] = $thumb;
			$json['success'] = 'File berhasil diupload!';
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	/**
	 * Callback check email
	 * 
	 * @access public
	 * @param string $email
	 * @return bool
	 */
	public function _check_email($email)
	{
		if ($this->user_model->check_email($email, $this->user->user_id())) {
			$this->form_validation->set_message('_check_email', 'Email ini sudah digunakan!');
			
			return false;
		}
		
		return true;
	}
	
	public function get_addresses()
	{
		check_ajax();
		
		if ($this->input->post()) {
			$this->load->library('datatables');
		
			$this->datatables
			->select("a.*, l3.name as province, CONCAT_WS(' ', l2.type, l2.name) as city, l.name as subdistrict, (CASE WHEN u.address_id = a.address_id THEN 1 ELSE 0 END) as is_default", false)
			->from('address a')
			->join('location l', 'l.subdistrict_id = a.subdistrict_id', 'left')
			->join('location l2', 'l2.city_id = a.city_id and l2.subdistrict_id = 0', 'left')
			->join('location l3', 'l3.province_id = a.province_id and l3.city_id = 0', 'left')
			->join('user u', 'u.user_id = a.user_id', 'left')
			->where('a.user_id', $this->input->post('user_id'));
			
			$this->output->set_output($this->datatables->generate('json'));
		} else {
			$json = array();
		
			$this->load->model('address_model');
			
			if ($addresses = $this->address_model->get_addresses($this->input->get('user_id'))) {
				foreach ($addresses as $address) {
					$json[] = $address;	
				}
			}
			
			$this->output->set_output(json_encode($json));
		}
	}
}