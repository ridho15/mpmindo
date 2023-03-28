<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Shipping_cost extends Admin_Controller
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
		
		$this->lang->load('admin_shipping_cost');
		$this->load->model('shipping_cost_model');
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
			->select('sc.*, l3.name as province, CONCAT_WS(" ", l2.type, l2.name) as city, l.name as subdistrict', false)
			->from('shipping_cost sc')
			->join('location l', 'l.subdistrict_id = sc.subdistrict_id', 'left')
			->join('location l2', 'l2.city_id = sc.city_id and l2.subdistrict_id = 0', 'left')
			->join('location l3', 'l3.province_id = sc.province_id and l3.city_id = 0', 'left')
			->group_by('sc.shipping_cost_id');
			
			$this->output->set_output($this->datatables->generate('json'));
		} else {
			if ( ! $this->admin->has_permission()) {
				show_401($this->uri->uri_string());
			}
			
			$this->load
			->title(lang('heading_title'))
			->view('admin/shipping_cost');
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
	 * Edit
	 * 
	 * @access public
	 * @param int $shipping_cost_id
	 * @return void
	 */
	public function edit()
	{
		if ( ! $this->admin->has_permission()) {
			return $this->output->set_output(json_encode(array('error' => lang('admin_error_edit'))));
		}
		
		$this->load->vars(['heading_title' => lang('heading_edit')]);
		
		$this->form($this->input->get('shipping_cost_id'));
	}
	
	/**
	 * Load form
	 * 
	 * @access private
	 * @param int $shipping_cost_id
	 * @return void
	 */
	private function form($shipping_cost_id = null)
	{
		$data['action'] = admin_url('shipping_cost/validate');
		$data['shipping_cost_id'] = null;
		$data['province_id'] = $this->config->item('rajaongkir_province_id');
		$data['city_id'] = null;
		$data['subdistrict_id'] = null;
		$data['cost'] = 0;
		
		if ($shipping_cost = $this->shipping_cost_model->get($shipping_cost_id)) {
			$data['shipping_cost_id'] = (int)$shipping_cost['shipping_cost_id'];
			$data['province_id'] = (int)$shipping_cost['province_id'];
			$data['city_id'] = (int)$shipping_cost['city_id'];
			$data['subdistrict_id'] = (int)$shipping_cost['subdistrict_id'];
			$data['cost'] = (float)$shipping_cost['cost'];
		}
		
		$this->load->model('location_model');
		$data['provinces'] = $this->location_model->get_provinces();
		
		$this->output->set_output(json_encode(array(
			'content' => $this->load->layout(null)->view('admin/shipping_cost_form', $data, true)
		)));
	}
	
	/**
	 * Validate
	 * 
	 * @access public
	 * @return json
	 */
	public function validate()
	{
		$json = array();
		
		$shipping_cost_id = $this->input->post('shipping_cost_id');
		
		$this->form_validation
		->set_rules('province_id', 'lang:label_province', 'trim|required')
		->set_rules('city_id', 'lang:label_city', 'trim|required')
		->set_rules('subdistrict_id', 'lang:label_subdistrict', 'trim|required')
		->set_rules('cost', 'lang:label_cost', 'trim|required')
		->set_error_delimiters('', '');
		
		if ($this->form_validation->run() == false) {
			foreach ($this->form_validation->get_errors() as $field => $error) {
				$json['error'][$field] = $error;
			}
		} else {
			$data['shipping_cost_id'] = (int)$shipping_cost_id;
			$data['province_id'] = (int)$this->input->post('province_id');
			$data['city_id'] = (int)$this->input->post('city_id');
			$data['subdistrict_id'] = (int)$this->input->post('subdistrict_id');
			$data['cost'] = (float)$this->input->post('cost');
			
			if ($shipping_cost_id) {
				$this->shipping_cost_model->update($shipping_cost_id, $data);
				
				$json['success'] = lang('success_updated');
			} else {
				$shipping_cost_id = $this->shipping_cost_model->insert($data);
				
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
		
		$shipping_cost_id = $this->input->post('shipping_cost_id');
		
		if ( ! $this->admin->has_permission()) {
			$json['error'] = lang('admin_error_delete');
		}
		
		if ( ! $shipping_cost_id) {
			$json['error'] = lang('error_no_selected');
		}
		
		if (empty($json['error'])) {
			$this->shipping_cost_model->delete($shipping_cost_id);
			$json['success'] = lang('success_deleted');
		}
		
		$this->output->set_output(json_encode($json));
	}
}