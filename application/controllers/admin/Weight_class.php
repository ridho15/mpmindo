<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Weight_class extends Admin_Controller
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
		
		$this->lang->load('admin_weight_class');
		$this->load->model('weight_class_model');
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
			->select('weight_class_id, title, unit, value', false)
			->from('weight_class');
			
			$this->output->set_output($this->datatables->generate('json'));
		} else {
			if ( ! $this->admin->has_permission()) {
				show_401($this->uri->uri_string());
			}
			
			$this->load
			->title(lang('heading_title'))
			->view('admin/weight_class');
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
	 * @param int $weight_class_id
	 * @return void
	 */
	public function edit()
	{
		if ( ! $this->admin->has_permission()) {
			return $this->output->set_output(json_encode(array('error' => lang('admin_error_edit'))));
		}
		
		$this->load->vars(['heading_title' => lang('heading_edit')]);
		
		$this->form($this->input->get('weight_class_id'));
	}
	
	/**
	 * Load form
	 * 
	 * @access private
	 * @param int $weight_class_id
	 * @return void
	 */
	private function form($weight_class_id = null)
	{
		$data['action'] = admin_url('weight_class/validate');
		$data['weight_class_id'] = null;
		$data['title'] = '';
		$data['unit'] = '';
		$data['value'] = 0;
		
		if ($weight_class = $this->weight_class_model->get($weight_class_id)) {
			$data['weight_class_id'] = (int)$weight_class['weight_class_id'];
			$data['title'] = $weight_class['title'];
			$data['unit'] = $weight_class['unit'];
			$data['value'] = $weight_class['value'];
		}
		
		$this->output->set_output(json_encode(array(
			'content' => $this->load->layout(null)->view('admin/weight_class_form', $data, true)
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
		
		$weight_class_id = $this->input->post('weight_class_id');
		
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
			
			if ($weight_class_id) {
				$this->weight_class_model->update($weight_class_id, $data);
				
				$json['success'] = lang('success_updated');
			} else {
				if ($this->weight_class_model->insert($data)) {
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
		
		$weight_class_id = $this->input->post('weight_class_id');
		
		if ( ! $this->admin->has_permission()) {
			$json['error'] = lang('admin_error_delete');
		}
		
		if ( ! $weight_class_id) {
			$json['error'] = lang('error_no_selected');
		}
		
		if (empty($json['error'])) {
			$this->weight_class_model->delete($weight_class_id);
			$json['success'] = lang('success_deleted');
		}
		
		$this->output->set_output(json_encode($json));
	}
}