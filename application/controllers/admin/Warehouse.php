<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Warehouse extends Admin_Controller
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
		
		$this->lang->load('admin_warehouse');
		$this->load->model('warehouse_model');
	}
	
	/**
	 * Index
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		if ($this->input->post(null, true)) {
			$this->load->library('datatables');
		
			$this->datatables
			->select('warehouse_id, name, code, region, address, telephone, email', false)
			->from('warehouse')
			->where_not_in('warehouse_id', array(1,2));
			
			$this->output->set_output($this->datatables->generate('json'));
		} else {
			if ( ! $this->admin->has_permission()) {
				show_401($this->uri->uri_string());
			}
			
			$this->load
			->title(lang('heading_title'))
			->view('admin/warehouse');
		}	
	}
	
	/**
	 * Create new
	 * 
	 * @access public
	 * @return void
	 */
	public function create()
	{
		if ( ! $this->admin->has_permission()) {
			return $this->output->set_output(json_encode(array('error' => lang('admin_error_create'))));
		}
		
		$this->load->vars(['heading_title' => lang('heading_create')]);
		
		$this->form();
	}
	
	/**
	 * Edit existing
	 * 
	 * @access public
	 * @param int $warehouse_id
	 * @return void
	 */
	public function edit()
	{
		if ( ! $this->admin->has_permission()) {
			return $this->output->set_output(json_encode(array('error' => lang('admin_error_edit'))));
		}
		
		$this->load->vars(['heading_title' => lang('heading_edit')]);
		
		$this->form($this->input->get('warehouse_id'));
	}
	
	/**
	 * Load form
	 * 
	 * @access private
	 * @param int $warehouse_id
	 * @return void
	 */
	private function form($warehouse_id = null)
	{
		$data['action'] = admin_url('warehouse/validate');
		$data['warehouse_id'] = null;
		$data['name'] = '';
		$data['code'] = '';
		$data['region'] = '';
		$data['address'] = '';
		$data['telephone'] = '';
		$data['email'] = '';
		
		if ($warehouse = $this->warehouse_model->get($warehouse_id)) {
			$data['warehouse_id'] = (int)$warehouse['warehouse_id'];
			$data['name'] = $warehouse['name'];
			$data['code'] = $warehouse['code'];
			$data['region'] = $warehouse['region'];
			$data['address'] = $warehouse['address'];
			$data['telephone'] = $warehouse['telephone'];
			$data['email'] = $warehouse['email'];
		}
		
		$this->output->set_output(json_encode(array(
			'content' => $this->load->layout(null)->view('admin/warehouse_form', $data, true)
		)));
	}
	
	/**
	 * Validate form
	 * 
	 * @access public
	 * @return json
	 */
	public function validate()
	{
		$json = array();
		
		$warehouse_id = $this->input->post('warehouse_id');
		
		$this->form_validation
		->set_rules('name', 'lang:label_name', 'trim|required')
		->set_rules('code', 'lang:label_code', 'trim|required|callback__check_code')
		->set_rules('region', 'lang:label_region', 'trim|required')
		->set_rules('address', 'lang:label_address', 'trim|required')
		->set_rules('email', 'lang:label_email', 'trim|required|valid_email')
		->set_error_delimiters('', '');
		
		if ($this->form_validation->run() == false) {
			foreach ($this->form_validation->get_errors() as $field => $error) {
				$json['error'][$field] = $error;
			}
		} else {
			$data['name'] = $this->input->post('name');
			$data['code'] = $this->input->post('code');
			$data['region'] = $this->input->post('region');
			$data['address'] = $this->input->post('address');
			$data['telephone'] = $this->input->post('telephone');
			$data['email'] = $this->input->post('email');
			
			if ($warehouse_id) {
				$this->warehouse_model->update($warehouse_id, $data);
				
				$json['success'] = lang('success_updated');
			} else {
				if ($this->warehouse_model->insert($data)) {
					$json['success'] = lang('success_created');
				}
			}
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	/**
	 * Callback check code
	 * 
	 * @access public
	 * @param string $code
	 * @return bool
	 */
	public function _check_code($code)
	{
		if ($this->warehouse_model->check_code($code, (int)$this->input->post('warehouse_id'))) {
			$this->form_validation->set_message('_check_code', lang('error_code'));
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
		
		$warehouse_id = $this->input->post('warehouse_id');
		
		if ( ! $this->admin->has_permission()) {
			$json['error'] = lang('admin_error_delete');
		}
		
		if ( ! $warehouse_id) {
			$json['error'] = lang('error_no_selected');
		}
		
		if (empty($json['error'])) {
			$this->warehouse_model->delete($warehouse_id);
			$json['success'] = lang('success_deleted');
		}
		
		$this->output->set_output(json_encode($json));
	}
}