<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends Admin_Controller
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
		
		$this->lang->load('admin_setting');
		$this->load->library('user_agent');
	}
	
	/**
	 * Index
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		$this->load->model('setting_model');
		$this->load->model('weight_class_model');
		$this->load->model('order_status_model');
		
		$data['action'] = admin_url('settings');
		
		$settings = $this->setting_model->get_settings();
		
		if (isset($settings['config'])) {
			foreach ($settings['config'] as $key => $value) {
				$data[$key] = $value;
			}
		}
		
		$data['logo'] = isset($data['logo']) ? $data['logo'] : '';
		$data['icon'] = isset($data['icon']) ? $data['icon'] : '';
		
		$this->load->library('image');
		
		$data['thumb_logo'] = $this->image->resize($data['logo'] !== '' ? $data['logo'] : 'no_image.jpg', 150, 150);
		$data['thumb_icon'] = $this->image->resize($data['icon'] !== '' ? $data['icon'] : 'no_image.jpg', 150, 150);
		$data['no_image'] = $this->image->resize('no_image.jpg', 150, 150);
		
		$this->load->library('rajaongkir');
		
		$data['rajaongkir_courier'] = isset($data['rajaongkir_courier']) ? (array)$data['rajaongkir_courier'] : [];
		$data['rajaongkir_province_id'] = isset($data['rajaongkir_province_id']) ? $data['rajaongkir_province_id'] : 0;
		$data['rajaongkir_city_id'] = isset($data['rajaongkir_city_id']) ? $data['rajaongkir_city_id'] : 0;
		$data['rajaongkir_subdistrict_id'] = isset($data['rajaongkir_subdistrict_id']) ? $data['rajaongkir_subdistrict_id'] : 0;
		$data['couriers'] = $this->rajaongkir->get_couriers('domestic');
		$data['weight_classes'] = $this->weight_class_model->get_all();
		$data['order_statuses'] = $this->order_status_model->get_statuses();
		
		$this->load->model('location_model');
		$data['provinces'] = $this->location_model->get_provinces();
		
		$this->load->model('store_model');
		$data['stores'] = $this->store_model->get_all();
		
		$data['mobile'] = $this->agent->is_mobile();
		
		$this->form_validation
		->set_rules('company', 'lang:label_company', 'trim|required')
		->set_rules('email', 'lang:label_email', 'trim|required|valid_email')
		->set_error_delimiters('', '');
		
		if ($this->input->post(null, true)) {
			$json = array();
			
			if ( ! $this->admin->has_permission('edit')) {
				$json['error'] = lang('admin_error_edit');
			}
			
			if ($this->form_validation->run() == false) {
				foreach (array('company', 'email') as $field) {
					if (form_error($field) !== '') {
						$json['errors'][$field] = form_error($field);
					}
				}
			} else {
				if ( ! $json) {
					$post = array();
					
					if (isset($settings['config'])) {
						foreach ($settings['config'] as $key => $value) {
							$post[$key] = $value;
						}
					}
					
					$configs = $this->input->post(null, false);
					
					foreach ($configs as $key => $value) {
						$post[$key] = $value;
					}
					
					$post['maintenance'] = (bool)$this->input->post('maintenance');
					$post['registration'] = (bool)$this->input->post('registration');
					
					$this->setting_model->edit_setting('config', $post);
					$json['success'] = lang('success_updated');
				}
			}
			
			$this->output->set_output(json_encode($json));	
		} else {
			if ( ! $this->admin->has_permission()) {
				show_401();
			}
			
			$this->load
			->title(lang('heading_title'))
			->view('admin/settings', $data);	
		}
	}
	
	/**
	 * Upload
	 * 
	 * @access public
	 * @return void
	 */
	public function upload()
	{
		$json = array();
		
		$upload_path = DIR_IMAGE.'data/logo';
		
		if ( ! file_exists($upload_path)) {
			@mkdir($upload_path, 0777);
		}
		
		$this->load->library('upload', array(
			'upload_path' => $upload_path,
			'allowed_types' => 'gif|jpg|png|jpeg|heif|tiff',
			'max_size' => '5120',
			'overwrite' => true,
		));

		if ( ! $this->upload->do_upload()) {
			$json['error'] = $this->upload->display_errors('', '');
		} else {
			$this->load->library('image');
			
			$upload_data = $this->upload->data();
			$image = substr($upload_data['full_path'], strlen(FCPATH.DIR_IMAGE));

			// check EXIF and autorotate if needed
			//$this->load->library('image_autorotate', array('filepath' => $image));
			
			$thumb = $this->image->resize($image, 150, 150, true);
			
			$json['thumb'] = $thumb;
			$json['image'] = $image;
			$json['success'] = lang('success_uploaded');
		}
		
		$this->output->set_output(json_encode($json));
	}
} 