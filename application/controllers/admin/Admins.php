<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admins extends Admin_Controller
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
		$this->lang->load('admin');
	}
	
	/**
	 * Index admins data
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		if ($this->input->post(null, true)) {
			$this->load->library('datatables');
		
			// $this->datatables
			// ->select('admin.admin_id, admin.name, admin.email, admin.active, (case when(admin.admin_id < 0) then "Top Administrator" else admin_group.name end) as group_name')
			// ->from('admin')
			// ->join('admin_group', 'admin_group.admin_group_id = admin.admin_group_id', 'left');
			$this->datatables
			->select('admin.admin_id, admin.name, admin.email, admin.username, admin.active')
			->where('admin.is_distributor','0')
			->from('admin');
			
			$this->output->set_output($this->datatables->generate('json'));
		} else {
			if ( ! $this->admin->has_permission()) {
				show_401($this->uri->uri_string());
			}
			
			$this->load
			->title(lang('heading_title'))
			->js(base_url('assets/js/plugins/jquery.dataTables.min.js'))
			->js(base_url('assets/js/plugins/dataTables.bootstrap.min.js'))
			->breadcrumb(lang('text_admins'))
			->breadcrumb(lang('heading_title'))
			->view('admin/admin');
		}
	}
	
	/**
	 * Create new admin
	 * 
	 * @access public
	 * @return void
	 */
	public function create()
	{
		if (!$this->admin->has_permission()) {
			return $this->output->set_output(json_encode(array('error' => lang('admin_error_create'))));
		}
		
		$this->load->vars(array('heading_title' => lang('heading_create')));
		
		$this->form();
	}
	
	/**
	 * Edit existing admin
	 * 
	 * @access public
	 * @param int $admin_id
	 * @return void
	 */
	public function edit()
	{
		$admin_id = $this->input->get('admin_id');
		
		if (!$this->admin->has_permission()) {
			return $this->output->set_output(json_encode(array('error' => lang('admin_error_edit'))));
		}
		
		if ($admin_id < 0 && $this->admin->admin_id() > 0) {
			return $this->output->set_output(json_encode(array('error' => lang('admin_error_edit'))));
		}
		
		$this->load->vars(array('heading_title' => lang('heading_edit')));
		
		$this->form($admin_id);
	}
	
	/**
	 * Load admin form
	 * 
	 * @access private
	 * @param int $admin_id
	 * @return void
	 */
	private function form($admin_id = null)
	{
		$data['action'] = admin_url('admins/validate');
		$data['admin_id'] = null;
		$data['name'] = '';
		$data['username'] = '';
		$data['email'] = '';
		$data['admin_group_id'] = null;
		$data['active']	= null;
		
		if ($admin = $this->admin_model->get($admin_id)) {
			$data['admin_id'] = (int)$admin['admin_id'];
			$data['name'] = $admin['name'];
			$data['username'] = $admin['username'];
			$data['email'] = $admin['email'];
			$data['admin_group_id'] = (int)$admin['admin_group_id'];
			$data['active']	= (bool)$admin['active'];
		}
		
		$data['admin_groups'] = $this->admin_group_model->get_all();
		
		$this->output->set_output(json_encode(array(
			'content' => $this->load->layout(false)->view('admin/admin_form', $data, true)
		)));
	}
	
	/**
	 * Validate admin form
	 * 
	 * @access public
	 * @return json
	 */
	public function validate()
	{
		$json = array();
		
		$admin_id = $this->input->post('admin_id');
		
		if ($this->input->post('password') !== '' || ! $admin_id) {
			$this->form_validation
			->set_rules('password', 'Password', 'trim|required|min_length[8]')
			->set_rules('confirm', 'Confirm Password', 'trim|required|matches[password]');
		}
		
		$this->form_validation
		->set_rules('name', 'Name', 'trim|required|min_length[5]')
		->set_rules('username', 'username', 'trim|required|callback__check_username|min_length[5]')
		->set_rules('email', 'Email', 'trim|required|valid_email|callback__check_email')
		->set_error_delimiters('', '');
		
		if ($this->form_validation->run() == false) {
			if ($this->input->post('password') !== '' || ! $admin_id) {
				if (form_error('password') !== '') {
					$json['error']['password'] = form_error('password');
				}
				
				if (form_error('confirm') !== '' || ! $admin_id) {
					$json['error']['confirm'] = form_error('confirm');
				}
			}
		
			foreach (array('name', 'username', 'email') as $field) {
				if (form_error($field) !== '') {
					$json['error'][$field] = form_error($field);
				}
			}
		} else {
			$data['admin_id'] = (int)$admin_id;
			$data['name'] = $this->input->post('name');
			$data['username'] = $this->input->post('username');
			$data['email'] = strtolower($this->input->post('email'));
			$data['password'] = $this->input->post('password');
			$data['admin_group_id'] = (int)$this->input->post('admin_group_id');
			$data['active']	= (bool)$this->input->post('active');
			
			if ($admin_id) {
				if ($this->admin_model->update_admin($admin_id, $data)) {
					$json['success'] = lang('success_updated');
				}
			} else {
				$data['photo'] = 'admin.png';
				
				if ($this->admin_model->create_admin($data)) {
					$json['success'] = lang('success_created');
				}
			}
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
		if ($this->admin_model->check_username($username, (int)$this->input->post('admin_id'))) {
			$this->form_validation->set_message('_check_username', lang('error_username'));
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
		if ($this->admin_model->check_email($email, (int)$this->input->post('admin_id'))) {
			$this->form_validation->set_message('_check_email', lang('error_email'));
			return false;
		}
		
		return true;
	}
	
	/**
	 * Delete
	 * 
	 * @access public
	 * @return void
	 */
	public function delete()
	{
		$json = array();
		
		$admin_id = $this->input->post('admin_id');
		
		if (!$this->admin->has_permission()) {
			$json['error'] = lang('admin_error_delete');
		}
		
		if (!$admin_id) {
			$json['error'] = lang('error_no_selected');
		}
		
		if (is_array($admin_id)) {
			foreach ($admin_id as $id) {
				if ($id == $this->admin->admin_id()) {
					$json['error'] = lang('error_delete_own');
				} elseif ($id < 0) {
					$json['error'] = lang('error_delete_admin');
				}
			}
		} else {
			if ($admin_id == $this->admin->admin_id()) {
				$json['error'] = lang('error_delete_own');
			} elseif ($admin_id < 0) {
				$json['error'] = lang('error_delete_admin');
			}
		}
		
		if (empty($json['error'])) {
			$this->admin_model->delete($admin_id);
			
			$json['success'] = lang('success_deleted');
		}
		
		$this->output->set_output(json_encode($json));
	}
} 