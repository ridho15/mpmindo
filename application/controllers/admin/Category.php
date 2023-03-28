<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends Admin_Controller
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
		
		$this->lang->load('admin_category');
		$this->load->model('category_model');
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
		if ( ! $this->admin->has_permission()) {
			show_401($this->uri->uri_string());
		}
			
		$data['categories'] = json_encode($this->category_model->tree_view(0));

		$this->load
		->title(lang('heading_title'))
		->view('admin/category', $data);
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
		
		$this->form(null, $this->input->get('parent_id'));
	}
	
	/**
	 * Edit existing
	 * 
	 * @access public
	 * @param int $category_id
	 * @return void
	 */
	public function edit()
	{
		if ( ! $this->admin->has_permission()) {
			return $this->output->set_output(json_encode(array('error' => lang('admin_error_edit'))));
		}
		
		$this->load->vars(['heading_title' => lang('heading_edit')]);
		
		$this->form($this->input->get('category_id'));
	}
	
	/**
	 * Load form
	 * 
	 * @access private
	 * @param int $category_id
	 * @return void
	 */
	private function form($category_id = null, $parent_id = 0)
	{
		$data['action'] = admin_url('category/validate');
		$data['category_id'] = null;
		$data['name'] = '';
		$data['description'] = '';
		$data['image'] = '';
		$data['sort_order'] = 0;
		$data['active'] = 0;
		
		if ($parent_id) {
			$parent = $this->category_model->get_category($parent_id);
			if ($parent) {
				$data['path'] = $parent['path'] ? $parent['path'].' &raquo; '.$parent['name'] : $parent['name'];
				$data['parent_id'] = $parent_id;	
			} else {
				$data['path'] = 'Top';
				$data['parent_id'] = 0;
			}
		} else {
			$data['path'] = 'Top';
			$data['parent_id'] = 0;	
		}
		
		$image = 'no_image.jpg';
		
		if ($category = $this->category_model->get_category($category_id)) {
			$data['category_id'] = (int)$category['category_id'];
			$data['name'] = $category['name'];
			$data['description'] = $category['description'];
			$data['image'] = $category['image'];
			$data['parent_id'] = (int)$category['parent_id'];
			$data['sort_order'] = (int)$category['sort_order'];
			$data['active'] = (int)$category['active'];
			$data['path'] = $category['path'];
				
			$image = $category['image'] ? $category['image'] : 'no_image.jpg';
		}
		
		$data['thumb_detail'] = $this->image->resize($image, 150, 150);
		$data['no_image'] = $this->image->resize('no_image.jpg', 150, 150);
		$data['mobile'] = $this->agent->is_mobile();

		$this->output->set_output(json_encode(array(
			'content' => $this->load->layout(null)->view('admin/category_form', $data, true)
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
		
		$category_id = $this->input->post('category_id');
		
		$this->form_validation
		->set_rules('name', 'lang:label_name', 'trim|required')
		->set_rules('sort_order', 'lang:label_sort_order', 'trim|required')
		->set_error_delimiters('', '');
		
		if ($this->form_validation->run() == false) {
			foreach ($this->form_validation->get_errors() as $field => $error) {
				$json['error'][$field] = $error;
			}
		} else {
			$post = $this->input->post(null, true);
			$post['active'] = (bool)$this->input->post('active');
				
			if ($category_id) {
				$this->category_model->update($category_id, $post);
				$json['success'] = lang('success_updated');
			} else {	
				$this->category_model->insert($post);
				$json['success'] = lang('success_created');
			}
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	/**
	 * Repair
	 * 
	 * @access public
	 * @return void
	 */
	public function repair()
	{
		$json = array();
		
		$category_id = $this->input->post('category_id');
		
		if ( ! $this->admin->has_permission('edit')) {
			$json['error'] = lang('admin_error_edit');
		}
		
		if (empty($json['error'])) {
			$this->category_model->repair_categories();
			
			$json['success'] = lang('text_repair_success');
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	/**
	 * Auto complete
	 * 
	 * @access public
	 * @return void
	 */
	public function auto_complete()
	{
		$json = array();
		
		if ($this->input->get('filter_name')) {	
			$params = array(
				'filter_name' => $this->input->get('filter_name'),
				'start' => 0,
				'limit' => 20
			);
			
			foreach ($this->category_model->get_categories($params) as $category) {
				$json[] = array(
					'category_id' => $category['category_id'], 
					'name' => strip_tags(html_entity_decode($category['name'], ENT_QUOTES, 'UTF-8'))
				);	
				
			}
			
			$json[] = array(
				'category_id' => 0, 
				'name' => 'None'
			);	
		}
		
		$sort_order = array();
	
		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}
		
		array_multisort($sort_order, SORT_ASC, $json);
	
		$this->output->set_output(json_encode($json));
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
		
		$upload_path = DIR_IMAGE.'categories';
		
		if ( ! file_exists($upload_path)) {
			@mkdir($upload_path, 0777);
		}
		
		$this->load->library('upload', array(
			'upload_path' => $upload_path,
			'allowed_types' => 'gif|jpg|png|jpeg|heif|tiff',
			'max_size' => '5120',
			'overwrite' => true
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
	
	/**
	 * Delete
	 * 
	 * @access public
	 * @return void
	 */
	public function delete()
	{
		$json = array();
		
		$category_id = $this->input->post('category_id');
		
		if ( ! $this->admin->has_permission()) {
			$json['error'] = lang('admin_error_delete');
		}
		
		if ( ! $category_id) {
			$json['error'] = lang('error_no_selected');
		}
		
		//check product of category
		if (is_array($category_id)) {
			$qty = $this->category_model->get_qty_product_all_array($category_id);
		} else {
			$qty = $this->category_model->get_qty_product_all($category_id);
		}

		if($qty > 0) $json['error'] = 'Kategori tidak bisa dihapus karena masih ada produk';

		if (empty($json['error'])) {
			$this->category_model->delete($category_id);
			$json['success'] = lang('success_deleted');
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	/**
	 * Synchronize categories
	 * 
	 * @access public
	 * @return void
	 */
	public function synch()
	{
		$json = array();
		
		if ( ! $this->admin->has_permission('edit')) {
			$json['error'] = lang('admin_error_edit');
		}
		
		if (empty($json['error'])) {
			//$this->load->model('api/categories_model', 'api_categories_model');
			//$this->api_categories_model->synch_categories();
			
			$json['success'] = 'Sinkronisasi data kategori berhasil!';
		}
		
		$this->output->set_output(json_encode($json));
	}
}