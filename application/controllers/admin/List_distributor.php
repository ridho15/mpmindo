<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class List_distributor extends Admin_Controller
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
		
		$this->load->model('List_distributor_model');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->lang->load('list_distributor');
		$this->load->model('address_model');
		$this->load->model('location_model');
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

			$this->datatables
			->select('list_distributor.list_distributor_id,list_distributor.company_name,list_distributor.shop_name,list_distributor.address,list_distributor.number_phone')
			->from('list_distributor');
			
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
			->view('admin/list_distributor');
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
		$admin_id = $this->input->get('list_distributor_id');
		
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
		$data['action'] = admin_url('list_distributor/validate');
		$data['list_distributor_id'] = null;
		$data['country'] = '';
		$data['category'] = '';
		$data['business'] = '';
		$data['haircut'] = '';
		$data['sales']	= '';
		$data['repair'] = '';
		$data['spare_part'] = '';
		$data['company_name'] = '';
		$data['shop_name'] = '';
		$data['address_name'] = '';
		$data['address'] = '';
		$data['post_code'] = '';
		$data['province_id'] = '';
		$data['city']	= '';
		$data['sub_district']	= '';
		$data['longitude'] = '';
		$data['latitude'] = '';
		$data['country_code'] = '';
		$data['number_phone'] = '';
		$data['facebook'] = '';
		$data['instagram'] = '';
		$data['twiter']	= '';
		$data['youtube'] = '';
		$data['linkedin'] = '';
		$data['tiktok'] = '';
		$data['website'] = '';
		
		if ($admin = $this->List_distributor_model->get($admin_id)) {
			$data['list_distributor_id'] = (int)$admin['list_distributor_id'];
			$data['country'] = $admin['country'];
			$data['category'] = $admin['category'];
			$data['business'] = $admin['business'];
			$data['haircut'] = (bool)$admin['haircut'];
			$data['sales']	= (bool)$admin['sales'];
			$data['repair'] = (bool)$admin['repair'];
			$data['spare_part'] = (bool)$admin['spare_part'];
			$data['company_name'] = $admin['company_name'];
			$data['shop_name'] = $admin['shop_name'];
			$data['address_name']	= $admin['address_name'];
			$data['address'] = $admin['address'];
			$data['post_code']	= $admin['address_name'];
			$data['province_id'] = $admin['province'];
			$data['post_code']	= $admin['post_code'];
			$data['city'] = $admin['city'];
			$data['sub_district'] = $admin['sub_district'];
			$data['latitude'] = $admin['latitude'];
			$data['longitude'] = $admin['longitude'];
			$data['country_code'] =$admin['country_code'];
			$data['number_phone'] = $admin['number_phone'];
			$data['facebook'] = $admin['facebook'];
			$data['instagram'] = $admin['instagram'];;
			$data['twiter']	= $admin['twiter'];;
			$data['youtube'] = $admin['youtube'];
			$data['linkedin'] = $admin['linkedin'];
			$data['tiktok'] = $admin['tiktok'];
			$data['website'] = $admin['website'];
		}

		$data['provinces'] = $this->location_model->get_provinces();
		
		$this->output->set_output(json_encode(array(
			'content' => $this->load->layout(false)->view('admin/list_distributor_form', $data, true)
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
		
		$admin_id = $this->input->post('list_distributor_id');
		
		$this->form_validation
		->set_rules('company_name', 'company_name', 'trim|required')
		->set_rules('shop_name', 'shop_name', 'trim|required')
		->set_rules('number_phone', 'number_phone', 'trim|required')
		->set_rules('address_name', 'Nama Alamat', 'trim|required')
		->set_rules('address', 'Alamat', 'trim|required')
		->set_rules('address_postcode', 'Kode Pos', 'trim|required')
		->set_rules('address_province_id', 'Provinsi', 'trim|required')
		->set_rules('address_city_id', 'Kota / Kabupaten', 'trim|required')
		->set_rules('address_subdistrict_id', 'Kecamatan', 'trim|required')
		->set_error_delimiters('', '');
		
		if ($this->form_validation->run() == false) {
			foreach (array('company_name','shop_name','number_phone','address_name','address','address_postcode','address_province_id','address_city_id','address_subdistrict_id') as $field) {
				if (form_error($field) !== '') {
					$json['error'][$field] = form_error($field);
				}
			}
		} else {

			//input data
			$data['list_distributor_id'] = $admin_id;
			$data['country']='Indonesia';
			$data['category'] = $this->input->post('category');
			$data['business'] = $this->input->post('business');
			$data['haircut'] = $this->input->post('haircut');
			$data['sales'] = $this->input->post('sales');
			$data['repair'] = $this->input->post('repair');
			$data['spare_part'] = $this->input->post('spare_part');
			$data['company_name'] = $this->input->post('company_name');
			$data['shop_name'] = $this->input->post('shop_name');
			$data['address_name'] = $this->input->post('address_name');
			$data['address'] = $this->input->post('address');
			$data['post_code'] = $this->input->post('address_postcode');
			$data['province'] = $this->input->post('address_province_id');
			$data['city'] = $this->input->post('address_city_id');
			$data['sub_district'] = $this->input->post('address_subdistrict_id');
			$data['latitude'] = $this->input->post('lat');
			$data['longitude'] = $this->input->post('long');
			$data['country_code']='62';
			$data['number_phone'] = $this->input->post('number_phone');
			$data['facebook'] = $this->input->post('fb');
			$data['instagram'] = $this->input->post('ig');
			$data['twiter'] = $this->input->post('tw');
			$data['youtube'] = $this->input->post('yb');
			$data['linkedin'] = $this->input->post('linkedin');
			$data['tiktok'] = $this->input->post('tiktok');
			$data['website'] = $this->input->post('website');
			
			if ($admin_id) {
				if ($this->List_distributor_model->update_list_distributor($admin_id, $data)) {
					$json['success'] = lang('success_updated');
				}
			} else {
				if ($this->List_distributor_model->create_list_distributor($data)) {
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
		
		$admin_id = $this->input->post('list_distributor_id');
		
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
			$this->List_distributor_model->delete($admin_id);
			
			$json['success'] = lang('success_deleted');
		}
		
		$this->output->set_output(json_encode($json));
	}
} 