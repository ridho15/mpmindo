<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Unit_class extends Admin_Controller
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
		
		$this->lang->load('admin_unit_class');
		$this->load->model('unit_class_model');
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
			->select('unit_class_id, title, unit, value', false)
			->from('unit_class');
			
			$this->output->set_output($this->datatables->generate('json'));
		} else {
			if ( ! $this->admin->has_permission()) {
				show_401($this->uri->uri_string());
			}
			
			$this->load
			->title(lang('heading_title'))
			->view('admin/unit_class');
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
	 * @param int $unit_class_id
	 * @return void
	 */
	public function edit()
	{
		if ( ! $this->admin->has_permission()) {
			return $this->output->set_output(json_encode(array('error' => lang('admin_error_edit'))));
		}
		
		$this->load->vars(['heading_title' => lang('heading_edit')]);
		
		$this->form($this->input->get('unit_class_id'));
	}
	
	/**
	 * Load form
	 * 
	 * @access private
	 * @param int $unit_class_id
	 * @return void
	 */
	private function form($unit_class_id = null)
	{
		$data['action'] = admin_url('unit_class/validate');
		$data['unit_class_id'] = null;
		$data['title'] = '';
		$data['unit'] = '';
		$data['value'] = 0;
		
		if ($unit_class = $this->unit_class_model->get($unit_class_id)) {
			$data['unit_class_id'] = (int)$unit_class['unit_class_id'];
			$data['title'] = $unit_class['title'];
			$data['unit'] = $unit_class['unit'];
			$data['value'] = $unit_class['value'];
		}
		
		$this->output->set_output(json_encode(array(
			'content' => $this->load->layout(null)->view('admin/unit_class_form', $data, true)
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
		
		$unit_class_id = $this->input->post('unit_class_id');
		
		$this->form_validation
		->set_rules('title', 'lang:label_title', 'trim|required')
		->set_rules('unit', 'lang:label_unit', 'trim|required')
		->set_rules('value', 'lang:label_value', 'trim|required')
		->set_error_delimiters('', '');
		
		if ($this->form_validation->run() == false) {
			foreach ($this->form_validation->get_errors() as $field => $error) {
				$json['error'][$field] = $error;
			}
		} else {
			$data['title'] = $this->input->post('title');
			$data['unit'] = $this->input->post('unit');
			$data['value'] = $this->input->post('value');
			
			if ($unit_class_id) {
				$this->unit_class_model->update($unit_class_id, $data);
				
				$json['success'] = lang('success_updated');
			} else {
				if ($this->unit_class_model->insert($data)) {
					$json['success'] = lang('success_created');
				}
			}
		}
		
		$this->output->set_output(json_encode($json));
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
		
		$unit_class_id = $this->input->post('unit_class_id');
		
		if ( ! $this->admin->has_permission()) {
			$json['error'] = lang('admin_error_delete');
		}
		
		if ( ! $unit_class_id) {
			$json['error'] = lang('error_no_selected');
		}
		
		if (empty($json['error'])) {
			$this->unit_class_model->delete($unit_class_id);
			$json['success'] = lang('success_deleted');
		}
		
		$this->output->set_output(json_encode($json));
	}
}