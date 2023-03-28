<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends Admin_Controller
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
		
		$this->load->model('admin_model');
		$this->load->model('admin_group_model');
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('user_agent');
	}
	
	/**
	 * Index
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		$admin = $this->admin_model->get($this->admin->admin_id());
		
		$this->load->library('image');
		
		if ( ! empty($admin['image'])) {
			$data['image'] = $this->image->resize($admin['image'], 200, 200);
		} else {
			$data['image'] = $this->image->resize('admin.png', 200, 200);
		}
		
		$data['action'] = admin_url('profile/validate');
		$data['name'] = $this->admin->name();
		$data['username'] = $admin['username'];
		$data['email'] = $this->admin->email();
		$data['group'] = $this->admin->group();
		$data['mobile'] = $this->agent->is_mobile();

		$this->load->view('admin/profile', $data);
	}
	
	public function validate()
	{
		$json = array();
		
		$this->form_validation
		->set_rules('name', 'Nama', 'trim|required|min_length[1]|max_length[32]')
		->set_rules('email', 'Email', 'trim|required|valid_email|callback__check_email')
		->set_rules('username', 'Username', 'trim|required|callback__check_username')
		->set_error_delimiters('', '');
		
		if ($this->input->post('password') && $this->input->post('password') != '') {
			$this->form_validation
			->set_rules('password', 'Password', 'trim|required|min_length[8]')
			->set_rules('confirm', 'Konfirmasi Password', 'trim|required|matches[password]');
		}
		
		if ($this->form_validation->run() == false) {
			if (form_error('name') != '') {
				$json['errors']['name'] = form_error('name');
			}
			
			if (form_error('email') != '') {
				$json['errors']['email'] = form_error('email');
			}
			
			if ($this->input->post('password') && $this->input->post('password') != '') {
				if (form_error('password') != '') {
					$json['errors']['password'] = form_error('password');
				}
				
				if (form_error('confirm') != '') {
					$json['errors']['confirm'] = form_error('confirm');
				}
			}
		} else {
			$admin['name']  = ucwords($this->input->post('name'));
			$admin['email'] = $this->input->post('email');
			$admin['username'] = $this->input->post('username');
			
			if ($this->input->post('password') && $this->input->post('password') != '') {
				$this->load->helper('security');
				
				$admin['salt'] = hash_string();
				$admin['password'] = hash_string($this->input->post('password'), $admin['salt']);
			}
			
			if ($this->admin_model->update($this->admin->admin_id(), $admin)) {
				$json['success'] = 'Profil Anda telah diperbarui';
			}
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
		$admin_id = $this->admin->admin_id();
		$upload_path = DIR_IMAGE.'admin';
		
		if ( ! file_exists($upload_path)) {
			@mkdir($upload_path, 0777);
		}
		
		$this->load->library('upload', array(
			'upload_path' => $upload_path,
			'file_name'	=> 'image-'.$admin_id,
			'allowed_types' => 'gif|jpg|png|jpeg|heif|tiff',
			'max_size' => '5120',
			'overwrite' => true,
		));

		if ( ! $this->upload->do_upload()) {
			$json['error'] = $this->upload->display_errors('', '');
		} else {
			$this->load->library('image');
			
			$upload_data = $this->upload->data();
			$image = substr($upload_data['full_path'], strlen(FCPATH.DIR_IMAGE));

			// check EXIF and autorotate if needed
			//$this->load->library('image_autorotate', array('filepath' => $image));
			
			$thumb = $this->image->resize($image, 200, 200, true);
			
			$this->admin_model->update($admin_id, array('image' => $image));
			
			$json['image'] = $thumb;
			$json['success'] = 'File berhasil diunggah.';
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	/**
	 * Callback check username
	 * 
	 * @access public
	 * @param string $username
	 * @return bool
	 */
	public function _check_username($username)
	{	
		if ($this->admin_model->check_username($username, $this->admin->admin_id())) {
			$this->form_validation->set_message('_check_username', 'username ini sudah digunakan!');
			
			return false;
		}
		
		return true;
	}
	
	/**
	 * Callback check username
	 * 
	 * @access public
	 * @param string $username
	 * @return bool
	 */
	public function _check_email($email)
	{
		if ($this->admin_model->check_email($email, $this->admin->admin_id())) {
			$this->form_validation->set_message('_check_email', 'Email ini sudah digunakan!');
			
			return false;
		}
		
		return true;
	}
}