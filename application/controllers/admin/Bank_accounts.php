<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank_accounts extends Admin_Controller
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
		
		$this->load->model('bank_account_model');
		$this->lang->load('admin_bank_account');
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
			->select('ba.bank_account_id, ba.title, ba.account_number, ba.account_name, ba.active, b.code as bank_code, b.name as bank_name')
			->join('bank b', 'b.bank_id = ba.bank_id', 'left')
			->from('bank_account ba');
					
			$this->output->set_output($this->datatables->generate('json'));
		} else {
			if ( ! $this->admin->has_permission()) {
				show_401($this->uri->uri_string());
			}
			
			$this->load
			->title(lang('heading_title'))
			->view('admin/bank_account');	
		}
	}
	
	/**
	 * Create
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
	 * Edit
	 * 
	 * @access public
	 * @return void
	 */
	public function edit()
	{
		if (!$this->admin->has_permission()) {
			return $this->output->set_output(json_encode(array('error' => lang('admin_error_edit'))));
		}
		
		$this->load->vars(array('heading_title' => lang('heading_edit')));
		
		$this->form($this->input->get('bank_account_id'));
	}
	
	/**
	 * Form
	 * 
	 * @access public
	 * @param int $bank_account_id
	 * @return void
	 */
	public function form($bank_account_id = null)
	{
		$data['bank_account_id'] = null;
		$data['bank_id'] = null;
		$data['title'] = '';
		$data['account_name'] = '';
		$data['account_number'] = '';
		$data['active'] = false;
		
		if ($bank_account = $this->bank_account_model->get($bank_account_id)) {
			$data = $bank_account;
		}
		
		$data['action'] = admin_url('bank_accounts/validate');
		
		$this->load->model('bank_model');
		$data['banks'] = $this->bank_model->get_all();
				
		$this->output->set_output(json_encode(array(
			'content' => $this->load->layout(false)->view('admin/bank_account_form', $data, true)
		)));
	}
	
	/**
	 * Validate
	 * 
	 * @access public
	 * @return void
	 */
	public function validate()
	{
		$this->form_validation
		->set_rules('title', 'lang:label_title', 'trim|required|min_length[3]')
		->set_rules('account_name', 'lang:label_account_name', 'trim|required|min_length[3]')
		->set_rules('account_number', 'lang:label_account_number', 'trim|required|min_length[3]')
		->set_error_delimiters('', '');
		
		$json = array();
		
		if ($this->form_validation->run() === false) {
			foreach ($this->form_validation->get_errors() as $field => $error) {
				$json['error'][$field] = $error;
			}
		} else {
			$bank_account_id = $this->input->post('bank_account_id');
			
			$post['title'] = $this->input->post('title');
			$post['account_name'] = $this->input->post('account_name');
			$post['account_number'] = $this->input->post('account_number');
			$post['bank_id'] = (int)$this->input->post('bank_id');
			$post['active'] = (bool)$this->input->post('active');

			if ((bool)$bank_account_id) {
				$this->bank_account_model->update($bank_account_id, $post);
				$json['success'] = lang('success_updated');
			} else {
				$this->bank_account_model->insert($post);
				$json['success'] = lang('success_created');
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
		
		$bank_account_id = $this->input->post('bank_account_id');
		
		if (!$this->admin->has_permission()) {
			$json['error'] = lang('admin_error_delete');
		}
		
		if (!$bank_account_id) {
			$json['error'] = lang('error_no_selected');
		}
		
		if (empty($json['error'])) {
			if (is_array($bank_account_id)) {
				foreach ($bank_account_id as $id) {
					$this->bank_account_model->delete($id);
				}
			} else {
				$this->bank_account_model->delete($bank_account_id);
			}
			
			$json['success'] = lang('success_deleted');
		}
		
		$this->output->set_output(json_encode($json));
	}
} 