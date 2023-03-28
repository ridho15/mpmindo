<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank extends Admin_Controller
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
		
		$this->lang->load('admin_bank');
		$this->load->model('bank_model');
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
			->select('bank_id, code, name, charge')
			->from('bank');
			
			$this->output->set_output($this->datatables->generate('json'));
		} else {
			if ( ! $this->admin->has_permission()) {
				show_401($this->uri->uri_string());
			}
			
			$this->load
			->title(lang('heading_title'))
			->view('admin/bank');
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
	 * @param int $bank_id
	 * @return void
	 */
	public function edit()
	{
		if ( ! $this->admin->has_permission()) {
			return $this->output->set_output(json_encode(array('error' => lang('admin_error_edit'))));
		}
		
		$this->load->vars(['heading_title' => lang('heading_edit')]);
		
		$this->form($this->input->get('bank_id'));
	}
	
	/**
	 * Load form
	 * 
	 * @access private
	 * @param int $bank_id
	 * @return void
	 */
	private function form($bank_id = null)
	{
		$data['action'] = admin_url('bank/validate');
		$data['bank_id'] = null;
		$data['code'] = '';
		$data['name'] = '';
		$data['charge'] = 0;
		
		if ($bank = $this->bank_model->get($bank_id)) {
			$data['bank_id'] = (int)$bank['bank_id'];
			$data['code'] = $bank['code'];
			$data['name'] = $bank['name'];
			$data['charge'] = $bank['charge'];
		}
		
		$this->output->set_output(json_encode(array(
			'content' => $this->load->layout(null)->view('admin/bank_form', $data, true)
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
		
		$bank_id = $this->input->post('bank_id');
		
		$this->form_validation
		->set_rules('code', 'lang:label_code', 'trim|required|callback__check_code')
		->set_rules('name', 'lang:label_name', 'trim|required')
		->set_rules('charge', 'lang:label_charge', 'trim|required')
		->set_error_delimiters('', '');
		
		if ($this->form_validation->run() == false) {
			foreach ($this->form_validation->get_errors() as $field => $error) {
				$json['error'][$field] = $error;
			}
		} else {
			$data['code'] = $this->input->post('code');
			$data['name'] = $this->input->post('name');
			$data['charge'] = (float)$this->input->post('charge');
			
			if ($bank_id) {
				$this->bank_model->update($bank_id, $data);
				
				$json['success'] = lang('success_updated');
			} else {
				if ($this->bank_model->insert($data)) {
					$json['success'] = lang('success_created');
				}
			}
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	public function _check_code($code)
	{	
		if ($this->bank_model->check_code($code, $this->input->post('bank_id'))) {
			$this->form_validation->set_message('_check_code', lang('error_bank_code'));
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
		
		$bank_id = $this->input->post('bank_id');
		
		if ( ! $this->admin->has_permission()) {
			$json['error'] = lang('admin_error_delete');
		}
		
		if ( ! $bank_id) {
			$json['error'] = lang('error_no_selected');
		}
		
		if (empty($json['error'])) {
			$this->bank_model->delete($bank_id);
			$json['success'] = lang('success_deleted');
		}
		
		$this->output->set_output(json_encode($json));
	}
}