<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home_Widget extends Admin_Controller
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
		
		$this->load->helper('form');
		$this->load->library('form_validation');
	}
	
	/**
	 * Index
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		$this->load->model('banner_model');
		$this->load->model('category_model');
		
		$data['action'] = admin_url('home_widget');
		$data['banners'] = $this->banner_model->get_all();
		$data['categories'] = array();
		
		$this->form_validation
		->set_rules('slideshow_banner_id', 'Slideshow Banner', 'trim|required')
		->set_error_delimiters('', '');
		
		if ($this->input->post(null, true)) {
			$json = array();
			
			if ( ! $this->admin->has_permission('edit')) {
				$json['error'] = lang('admin_error_edit');
			}
			
			if ($this->form_validation->run() == false) {
				foreach (array('slideshow_banner_id') as $field) {
					if (form_error($field) !== '') {
						$json['errors'][$field] = form_error($field);
					}
				}
			} else {
				if (!$json) {
					$slideshow_banner_id = (int)$this->input->post('slideshow_banner_id');
					$slideshow_mobile_banner_id = (int)$this->input->post('slideshow_mobile_banner_id');
					$ads_banner_id = (int)$this->input->post('ads_banner_id');
					$special_image = $this->input->post('special_image');
					$carousel_categories = $this->input->post('carousel_categories');
					$footer_cards = $this->input->post('footer_cards');
					
					if ($slideshow_banner_id) {
						$this->setting_model->edit_setting_value('config', 'slideshow_banner_id', (int)$slideshow_banner_id);
					}
					
					if ($slideshow_mobile_banner_id) {
						$this->setting_model->edit_setting_value('config', 'slideshow_mobile_banner_id', (int)$slideshow_mobile_banner_id);
					}
					
					if ($ads_banner_id) {
						$this->setting_model->edit_setting_value('config', 'ads_banner_id', (int)$ads_banner_id);
					}
					
					if ($special_image) {
						$this->setting_model->edit_setting_value('config', 'special_image', $special_image);
					}
					
					if ($carousel_categories) {
						$this->setting_model->edit_setting_value('config', 'carousel_categories', $carousel_categories);
					}
					
					if ($footer_cards) {
						$this->setting_model->edit_setting_value('config', 'footer_cards', $footer_cards);
					}
					
					$json['success'] = 'Pengaturan Home Widget telah diperbarui!';
				}
			}
			
			$this->output->set_output(json_encode($json));	
		} else {
			if ( ! $this->admin->has_permission()) {
				show_401();
			}
			
			$data['slideshow_banner_id'] = (int)$this->config->item('slideshow_banner_id');
			$data['slideshow_mobile_banner_id'] = (int)$this->config->item('slideshow_mobile_banner_id');
			$data['ads_banner_id'] = (int)$this->config->item('ads_banner_id');
			
			$carousel_categories = $this->config->item('carousel_categories');
			
			if (!is_array($carousel_categories)) {
				$carousel_categories = array(0);
			}
			
			$this->load->database();
			$data['categories'] = $this->db->where_in('category_id', $carousel_categories)->get('category')->result_array();
			$data['footer_cards'] = $this->config->item('footer_cards') ? $this->config->item('footer_cards') : array();
			
			$this->load->view('admin/home_widget', $data);	
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
		
		$upload_path = DIR_IMAGE;
		
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
			$json['success'] = 'File berhasil diupload!';
		}
		
		$this->output->set_output(json_encode($json));
	}
} 