<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_Status extends Admin_Controller
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
		
		$this->lang->load('admin_order_status');
		$this->load->model('order_status_model');
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
			->select('order_status_id, name')
			->from('order_status');
			
			$this->output->set_output($this->datatables->generate('json'));
		} else {
			if ( ! $this->admin->has_permission()) {
				show_401($this->uri->uri_string());
			}
			
			$this->load
			->title(lang('heading_title'))
			->view('admin/order_status');
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
	 * @param int $order_status_id
	 * @return void
	 */
	public function edit()
	{
		if ( ! $this->admin->has_permission()) {
			return $this->output->set_output(json_encode(array('error' => lang('admin_error_edit'))));
		}
		
		$this->load->vars(['heading_title' => lang('heading_edit')]);
		
		$this->form($this->input->get('order_status_id'));
	}
	
	/**
	 * Load order status form
	 * 
	 * @access private
	 * @param int $order_status_id
	 * @return void
	 */
	private function form($order_status_id = null)
	{
		$data['action'] = admin_url('order_status/validate');
		$data['order_status_id'] = null;
		$data['name'] = '';
		
		if ($order_status = $this->order_status_model->get($order_status_id)) {
			$data['order_status_id'] = (int)$order_status['order_status_id'];
			$data['name'] = $order_status['name'];
		}
		
		$this->output->set_output(json_encode(array(
			'content' => $this->load->layout(null)->view('admin/order_status_form', $data, true)
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
		
		$order_status_id = $this->input->post('order_status_id');
		
		$this->form_validation
		->set_rules('name', 'lang:label_name', 'trim|required|min_length[3]')
		->set_error_delimiters('', '');
		
		if ($this->form_validation->run() == false) {
			foreach ($this->form_validation->get_errors() as $field => $error) {
				$json['error'][$field] = $error;
			}
		} else {
			$data['order_status_id'] = $order_status_id;
			$data['name'] = $this->input->post('name');
			
			if ($order_status_id) {
				$this->order_status_model->update($order_status_id, $data);
				
				$json['success'] = lang('success_updated');
			} else {
				$order_status_id = $this->order_status_model->insert($data);
				
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
		
		$order_status_id = $this->input->post('order_status_id');
		
		if ( ! $this->admin->has_permission()) {
			$json['error'] = lang('admin_error_delete');
		}
		
		if ( ! $order_status_id) {
			$json['error'] = lang('error_no_selected');
		}
		
		if (empty($json['error'])) {
			$this->order_status_model->delete($order_status_id);
			$json['success'] = lang('success_deleted');
		}
		
		$this->output->set_output(json_encode($json));
	}
}