<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends User_Controller
{
	private $limit = 10;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('product_model');
		$this->load->model('category_model');
		$this->load->model('showcase_model');
		$this->load->model('weight_class_model');
		$this->load->model('stock_status_model');
		
		$this->load->helper('form');
		$this->load->helper('format');
		$this->load->library('form_validation');
	}
	
	public function index()
	{
		if ($this->user->store_id()) {
			if ($this->input->post(null, true)) {
				$this->load->library('datatables');
			
				$this->datatables
				->select('(SELECT pi.image FROM product_image pi WHERE pi.product_id = p.product_id LIMIT 1) as image, p.product_id, p.name, p.active, s.name as showcase')
				->from('product p')
				->join('showcase s', 's.showcase_id = p.showcase_id', 'left')
				->where('p.store_id', $this->user->store_id())
				->edit_column('image', '$1', 'img_tag(image)');
				
				$this->output->set_output($this->datatables->generate('json'));
			} else {
				$this->load->view('user/product');	
			}
		} else {
			show_401();
		}
	}
	
	public function create()
	{
		$this->load->vars('heading_title', 'Tambah Produk');		
		$this->form();
	}
	
	public function edit($product_id = null)
	{
		$this->load->vars('heading_title', 'Edit Produk');
		$this->form($product_id);
	}
	
	private function form($product_id = null)
	{
		$data['action']				= user_url('product/create');
		$data['product_id'] 		= null;
		$data['name'] 				= '';
		$data['description']		= '';
		$data['meta_description']	= '';
		$data['meta_keyword']		= '';
		$data['tag']				= '';
		$data['sku'] 				= '';
		$data['image'] 				= '';
		$data['price'] 				= 0;
		$data['category_id'] 		= 0;
		$data['manufacturer_id'] 	= 0;
		$data['showcase_id'] 		= 0;
		$data['points'] 			= 0;
		$data['weight'] 			= 0;
		$data['weight_class_id'] 	= 0;
		$data['quantity'] 			= 0;
		$data['unit_class_id'] 		= 0;
		$data['stock_status_id'] 	= 0;
		$data['minimum'] 			= 0;
		$data['sort_order'] 		= 0;
		$data['discounts']			= array();
		$data['images']				= array();
		$data['path_ids']			= array();
		
		$data['categories'] = $this->category_model->get_all_by(array('parent_id' => 0, 'active' => 1));
		
		$product_image = 'no_image.jpg';
		
		$this->load->library('image');
		
		if ($product_id) {
			if ($product = $this->product_model->get_by(array('product_id' => (int)$product_id, 'store_id' => (int)$this->user->store_id()))) {
				$data['action']				= user_url('product/edit/'.$product_id);
				$data['product_id'] 		= (int)$product['product_id'];
				$data['name'] 				= $product['name'];
				$data['description']		= $product['description'];
				$data['meta_description']	= $product['meta_description'];
				$data['meta_keyword']		= $product['meta_keyword'];
				$data['tag']				= $product['tag'];
				$data['sku'] 				= $product['sku'];
				$data['image'] 				= $product['image'];
				$data['price'] 				= (int)$product['price'];
				$data['category_id'] 		= (int)$product['category_id'];
				$data['manufacturer_id'] 	= (int)$product['manufacturer_id'];
				$data['showcase_id'] 		= (int)$product['showcase_id'];
				$data['points'] 			= (int)$product['points'];
				$data['weight'] 			= (int)$product['weight'];
				$data['weight_class_id'] 	= (int)$product['weight_class_id'];
				$data['quantity'] 			= (int)$product['quantity'];
				$data['unit_class_id'] 		= (int)$product['unit_class_id'];
				$data['stock_status_id'] 	= (int)$product['stock_status_id'];
				$data['minimum'] 			= (int)$product['minimum'];
				$data['sort_order'] 		= (int)$product['sort_order'];
				
				$product_image = $product['image'] ? $product['image'] : 'no_image.jpg';
				
				if ($category = $this->category_model->get_category($product['category_id'])) {
					if ($category['path_id']) {
						$category_path_id = $category['path_id'].'-'.$product['category_id'];
					} else {
						$category_path_id = $product['category_id'];
					}
					
					$data['path_ids'] = explode('-', $category_path_id);
				}
				
				foreach ($this->product_model->get_product_discounts($product_id) as $discount) {
					$discount['date_start'] = ($discount['date_start'] != '0000-00-00') ? date('d-m-Y', strtotime($discount['date_start'])) : '';
					$discount['date_end'] = ($discount['date_end'] != '0000-00-00') ? date('d-m-Y', strtotime($discount['date_end'])) : '';
					
					$data['discounts'][] = $discount;
				}
				
				foreach ($this->product_model->get_product_images($product_id) as $image) {
					$data_image = $image['image'] ? $image['image'] : 'no_image.jpg';
					$image['thumb'] = $this->image->resize($data_image, 150, 150);
					$data['images'][] = $image;
				}
			} else {
				show_404();
			}
		}
		
		$data['thumb'] = $this->image->resize($product_image, 150, 150);
		$data['no_image'] = $this->image->resize('no_image.jpg', 150, 150);
		$data['showcases'] = $this->showcase_model->get_all_by(array('store_id' => (int)$this->user->store_id()));
		$data['weight_classes'] = $this->weight_class_model->get_all();
		$data['stock_statuses'] = $this->stock_status_model->get_all();
			
		$this->form_validation->set_rules('name', 'Nama Produk', 'trim|required');
		
		if ($this->form_validation->run() == false) {
			$data['errors'] = (validation_errors()) ? validation_errors() : false;
			
			$this->load->view('user/product_form', $data);
		} else {
			$post = $this->input->post(null, false);
			$post['store_id'] = $this->user->store_id();
			
			if ($product_id) {
				$this->session->set_flashdata('success', 'Produk telah berhasil diupdate!');
				$this->product_model->edit_product($product_id, $post);
			} else {
				$this->session->set_flashdata('success', 'Produk baru telah berhasil ditambahkan!');
				$post['active'] = 1;
				$product_id = $this->product_model->create_product($post);
			}
			
			redirect(user_url('product'));
		}
	}
	
	public function upload()
	{
		$json = array();
		
		$upload_path = DIR_IMAGE.'products';
		
		if ( ! file_exists($upload_path)) {
			@mkdir($upload_path, 0777);
		}
		
		$this->load->library('upload', array(
			'upload_path'	=> $upload_path,
			'allowed_types' => 'gif|jpg|png|jpeg|heif|tiff',
			'max_size' => '5120',
			'encrypt_name'  => true
		));

		if ( ! $this->upload->do_upload()) {
			$json['error'] = $this->upload->display_errors('', '');
		} else {
			$this->load->library('image');
			
			$upload_data = $this->upload->data();
			$image = substr($upload_data['full_path'], strlen(FCPATH.DIR_IMAGE));

			// check EXIF and autorotate if needed
			//$this->load->library('image_autorotate', array('filepath' => $image));
			
			$thumb = $this->image->resize($image, 150, 150);
			
			$json['image'] = $image;
			$json['thumb'] = $thumb;
			
			$json['success'] = 'File is now uploaded!';
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	public function delete()
	{
		check_ajax();
		
		$json = array();
		
		$product_id = $this->input->post('product_id');
		
		if ( ! $this->auth->has_permission())
		{
			$json['error'] = lang('auth_error_delete');
		}
		
		if (empty($json['error']))
		{
			$this->product_model->delete($product_id);
			
			$json['success'] = 'Berhasil menghapus produk!';
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	/**
	 * Auto complete
	 * 
	 * @access public
	 * @return json
	 */
	public function auto_complete()
	{
		$json = array();
		
		if ($this->input->get('filter_name'))
		{	
			$params = array(
				'filter_name' => $this->input->get('filter_name'),
				'start' => 0,
				'limit' => 20
			);
			
			foreach ($this->product_model->get_products_autocomplete($params) as $product)
			{
				$json[] = array(
					'product_id' => $product['product_id'], 
					'name' => strip_tags(html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8')),
					'sku' => $product['sku'],
					'price' => $product['price'],
				);	
				
			}
		}
		
		$sort_order = array();
	
		foreach ($json as $key => $value)
		{
			$sort_order[$key] = $value['name'];
		}
		
		array_multisort($sort_order, SORT_ASC, $json);
	
		$this->output->set_output(json_encode($json));
	}
	
	public function subcategories()
	{
		$json = array();
		
		if ($this->input->get('parent_id')) {	
			foreach ($this->category_model->get_subcategories($this->input->get('parent_id')) as $category) {
				$json[] = array(
					'category_id' => $category['category_id'], 
					'name' => strip_tags(html_entity_decode($category['name'], ENT_QUOTES, 'UTF-8'))
				);	
				
			}
		}
		
		$sort_order = array();
	
		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}
		
		array_multisort($sort_order, SORT_ASC, $json);
	
		$this->output->set_output(json_encode($json));
	}
}

function img_tag($image) {
	$ci =& get_instance();
	$ci->load->library('image');
	
	if ($image) {
		$src = $ci->image->resize($image, 80, 80);
	} else {
		$src = $ci->image->resize('no_image.jpg', 80, 80);
	}
	
	return '<img src="'.$src.'">';
}