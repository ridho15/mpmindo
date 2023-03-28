<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_group extends Admin_Controller
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
		
		$this->load->model('admin_group_model');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->lang->load('admin_group');
	}
	
	/**
	 * Index staff levels data
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		if ($this->input->post(null, true)) {
			$this->load->library('datatables');
		
			$this->datatables
			->select('admin_group_id, name')
			->from('admin_group');
			
			$this->output->set_output($this->datatables->generate('json'));
		} else {
			if (!$this->admin->has_permission()) {
				show_401($this->uri->uri_string());
			}
			
			$this->load
			->title(lang('heading_title'))
			->view('admin/admin_group');	
		}
	}
	
	/**
	 * Create new staff level
	 * 
	 * @access public
	 * @return void
	 */
	public function create()
	{
		if (!$this->input->is_ajax_request()) {
			show_404();
		}
		
		if (!$this->admin->has_permission()) {
			return $this->output->set_output(json_encode(array('error' => lang('admin_error_create'))));
		}
		
		$this->load->vars(array('heading_title' => lang('heading_create')));
		
		$this->form();
	}
	
	/**
	 * Edit existing staff level
	 * 
	 * @access public
	 * @param int $admin_group_id
	 * @return void
	 */
	public function edit()
	{
		if (!$this->input->is_ajax_request()) {
			show_404();
		}
		
		if (!$this->admin->has_permission()) {
			return $this->output->set_output(json_encode(array('error' => lang('admin_error_edit'))));
		}
		
		$this->load->vars(array('heading_title' => lang('heading_edit')));
		
		$this->form($this->input->get('admin_group_id'));
	}
	
	/**
	 * Load staff level form
	 * 
	 * @access private
	 * @param int $admin_group_id
	 * @return void
	 */
	private function form($admin_group_id = null)
	{
		$this->lang->load('permission');
		$this->load->config('permission');
		
		$data['actions'] = $this->config->item('permission');
		$data['action'] = admin_url('admin_group/validate');
		$data['admin_group_id'] = null;
		$data['name'] = '';
		
		$data['permissions'] = array();
		
		foreach ($data['actions'] as $key => $val) {
			$data['permissions'][] = $key;
		}
		
		if ($admin_group = $this->admin_group_model->get($admin_group_id)) {
			$data['admin_group_id'] = (int)$admin_group['admin_group_id'];
			$data['name'] = $admin_group['name'];
			$data['permission'] = unserialize($admin_group['permission']);
		}
		
		$this->output->set_output(json_encode(array(
			'content' => $this->load->layout(false)->view('admin/admin_group_form', $data, true)
		)));
	}
	
	/**
	 * Validate staff level form
	 * 
	 * @access public
	 * @return json
	 */
	public function validate()
	{
		if (!$this->input->is_ajax_request()) {
			show_404();
		}
		
		$json = array();
		
		$admin_group_id = $this->input->post('admin_group_id');
		
		$this->form_validation
		->set_rules('name', 'lang:entry_name', 'trim|required|min_length[3]')
		->set_error_delimiters('', '');
		
		if ($this->form_validation->run() == false) {
			$json['error']['name'] = form_error('name');
		} else {
			$data['admin_group_id'] = (int)$admin_group_id;
			$data['name'] = $this->input->post('name');
			$data['permission'] = array();
			
			if ($permission = $this->input->post('permission')) {
				foreach ($permission as $path => $action) {
					$data['permission'][$path] = array_keys($action);
				}
			}
			
			$data['permission'] = serialize($data['permission']);
			
			if ($admin_group_id) {
				$this->admin_group_model->update($admin_group_id, $data);
				$json['success'] = lang('success_updated');
			} else {
				if ($this->admin_group_model->insert($data)) {
					$json['success'] = lang('success_created');
				}
			}
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	/**
	 * Delete staff level
	 * 
	 * @access public
	 * @return void
	 */
	public function delete()
	{
		$json = array();
		
		$this->load->model('admin_model');
		
		$admin_group_id = $this->input->post('admin_group_id');
		
		if (!$this->admin->has_permission()) {
			$json['error'] = lang('admin_error_delete');
		}
		
		if (empty($json['error'])) {
			$this->admin_group_model->delete($admin_group_id);
			$json['success'] = lang('success_deleted');
		}
		
		$this->output->set_output(json_encode($json));
	}
} 