<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends Admin_Controller
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
		
		$this->lang->load('admin_product');
		$this->load->model('product_model');
		$this->load->model('category_model');
		$this->load->model('weight_class_model');
		$this->load->model('unit_class_model');
		$this->load->model('warehouse_model');
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
		
		if ($this->input->post(null, true)) {
			$this->load->library('datatables');
			$this->load->helper('format');
			
			$this->datatables
			->select('p.product_id, p.sku, p.inventory_product_id, p.name, p.slug, p.category_id, p.price, p.active, p.sort_order, (select sum(quantity) from inventory b where b.product_id = p.product_id and warehouse_id != 0) as quantity', false)
			->from('product p');
			
			if ($this->input->post('category_id')) {
				$this->datatables->where('p.category_id', (int)$this->input->post('category_id'));
			}
			
			$this->datatables
			->edit_column('price', '$1', 'format_money(price)')
			->add_column('stock', '$1', 'quantity')
			->add_column('link', '$1', 'get_product_link(slug,category_id)');
			
			$this->output->set_output($this->datatables->generate('json'));
		} else {
			if ( ! $this->admin->has_permission()) {
				show_401($this->uri->uri_string());
			}
			
			$data['categories'] = $this->category_model->get_categories();
			
			// $this->load->model('api/categories_model', 'api_categories_model');
			// $data['icategories'] = $this->api_categories_model->get_icategories();
			
			// $this->load->model('api/places_model', 'api_places_model');
			// $data['iplaces'] = $this->api_places_model->get_places();
			
			$data['iplaces'] = $this->warehouse_model->get_all_warehouse();

			$this->load
			->title(lang('heading_title'))
			->view('admin/product', $data);
		}
	}
	
	/**
	 * Inventory products
	 * 
	 * @access public
	 * @return void
	 */
	public function iproducts()
	{
		if ($this->input->post(null, true)) {
			$this->load->library('datatables');
			$this->load->helper('format');
			
			$edb = $this->load->database('default', true);
			
			$this->datatables
			->set_database('inventory')
			->set_prefix()
			->select('p.id, p.product_code, p.product_name, p.product_category_id, p.limit_price, TRUNCATE(p.limit_price + (p.limit_price * (s.price_increment_percent/100)) + (p.limit_price * (pc.price_increment_percent/100)),2) AS sale_price, pr.product_id');
			
			if ($this->input->post('location') && $this->input->post('location_id')) {
				$this->datatables->select('(SELECT SUM(qty-(qty_booking_mutation+qty_booking_sale)) FROM knr_inventories s WHERE s.product_id = p.id AND s.place_type = "'.$this->input->post('location').'" AND s.place_id = "'.(int)$this->input->post('location_id').'") AS stock');
			} else {
				$this->datatables->select('(SELECT SUM(qty-(qty_booking_mutation+qty_booking_sale)) FROM knr_inventories s WHERE s.product_id = p.id) AS stock');
			}
			
			$this->datatables->from('knr_products p');
			$this->datatables->join('knr_stores s', 's.id = "'.(int)$this->config->item('store_id').'"', 'left');
			$this->datatables->join('knr_product_categories pc', 'pc.id = p.product_category_id', 'left');
			$this->datatables->join($edb->database.'.product pr', 'pr.inventory_product_id = p.id', 'left');
			
			if ($this->input->post('product_category_id')) {
				$this->datatables->where('p.product_category_id', (int)$this->input->post('product_category_id'));
			}
			
			$this->datatables->edit_column('limit_price', '$1', 'format_money(limit_price,0)');
			$this->datatables->edit_column('sale_price', '$1', 'format_money(sale_price,0)');
			$this->datatables->edit_column('stock', '$1', 'number_format(stock)');
			
			$this->output->set_output($this->datatables->generate('json'));
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
	 * @param int $product_id
	 * @return void
	 */
	public function edit()
	{
		if ( ! $this->admin->has_permission()) {
			return $this->output->set_output(json_encode(array('error' => lang('admin_error_edit'))));
		}
		
		$this->load->vars(['heading_title' => lang('heading_edit')]);
		
		$this->form($this->input->get('product_id'));
	}
	
	/**
	 * Load form
	 * 
	 * @access private
	 * @param int $product_id
	 * @return void
	 */
	private function form($product_id = null)
	{
		$data['action'] = admin_url('product/validate');
		$data['product_id'] = null;
		$data['name'] = '';
		$data['description'] = '';
		$data['meta_description'] = '';
		$data['meta_keyword'] = '';
		$data['tag'] = '';
		$data['sku'] = '';
		$data['image'] = '';
		$data['price'] = 0;
		$data['base_price'] = 0;
		$data['tax_value'] = 0;
		$data['tax_type'] = 'Non Pajak';
		$data['category_id'] = 0;
		$data['sku'] = '';
		$data['manufacturer_id'] = 0;
		$data['points'] = 0;
		$data['weight'] = 0;
		$data['weight_class_id'] = 0;
		$data['quantity'] = 0;
		$data['unit_class_id'] = 0;
		$data['minimum'] = 0;
		$data['minimum_stock'] = 0;
		$data['sort_order'] = 0;
		$data['active'] = 0;
		$data['discounts'] = array();
		$data['images']	= array();
		$data['related'] = array();
		$data['category'] = '';
		$data['unique_code'] = '';
		$data['specification'] = '';
		$data['whatisinthebox'] = '';
		
		$product_image = 'no_image.jpg';
		
		$this->load->library('image');
		
		if ($product = $this->product_model->get_by(array('product_id' => (int)$product_id))) {
			$data['product_id'] = (int)$product['product_id'];
			$data['name'] = $product['name'];
			$data['description'] = $product['description'];
			$data['meta_description'] = $product['meta_description'];
			$data['meta_keyword'] = $product['meta_keyword'];
			$data['tag'] = $product['tag'];
			$data['sku'] = $product['sku'];
			$data['image'] = $product['image'];
			$data['price'] = (float)$product['price'];
			$data['base_price'] = (float)$product['base_price'];
			$data['tax_value'] = (float)$product['tax_value'];
			$data['tax_type'] = $product['tax_type'];
			$data['category_id'] = (int)$product['category_id'];
			$data['sku'] = $product['sku'];
			$data['manufacturer_id'] = (int)$product['manufacturer_id'];
			$data['points'] = (int)$product['points'];
			$data['weight'] = (int)$product['weight'];
			$data['weight_class_id'] = (int)$product['weight_class_id'];
			$data['quantity'] = (int)$product['quantity'];
			$data['unit_class_id'] = (int)$product['unit_class_id'];
			$data['minimum'] = (int)$product['minimum'];
			$data['minimum_stock'] = (int)$product['minimum_stock'];
			$data['sort_order'] = (int)$product['sort_order'];
			$data['active'] = (bool)$product['active'];
			$data['unique_code'] = $product['unique_code'];
			$data['specification'] = $product['specification'];
			$data['whatisinthebox'] = $product['whatisinthebox'];
			
			$product_image = $product['image'] ? $product['image'] : 'no_image.jpg';
			
			if ($category = $this->category_model->get_category($product['category_id'])) {
				if ($category['path']) {
					$data['category'] = $category['path'].' &raquo; '.$category['name'];
				} else {
					$data['category'] = $category['name'];
				}
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
			
			$data['related'] = $this->product_model->get_product_related($product_id);
		}
		
		$data['thumb'] = $this->image->resize($product_image, 150, 150);
		$data['no_image'] = $this->image->resize('no_image.jpg', 150, 150);
		$data['weight_classes'] = $this->weight_class_model->get_all();
		$data['unit_classes'] = $this->unit_class_model->get_all();
		$data['mobile'] = $this->agent->is_mobile();

		$this->output->set_output(json_encode(array(
			'content' => $this->load->layout(null)->view('admin/product_form', $data, true)
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
		
		$product_id = $this->input->post('product_id');
		
		$this->form_validation
		->set_rules('name', 'lang:label_name', 'trim|required')
		->set_rules('category', 'Kategori', 'trim|required')
		// ->set_rules('sku', 'SKU', 'trim|required|min_length[3]|callback_check_sku')
		->set_error_delimiters('', '');
		
		if ($this->form_validation->run() == false) {
			foreach ($this->form_validation->get_errors() as $field => $error) {
				$json['error'][$field] = $error;
			}
		} else {
			$post = $this->input->post(null, false);
			$post['active'] = (bool)$this->input->post('active');
			$post['price'] = $post['base_price'];
			if($post['active'] && $product_id)
			{		
				// $this->load->model('api/stocks_model', 'api_stocks_model');
					
				// $product = $this->product_model->get_product($product_id);	
			
				// $stock = $this->api_stocks_model->get_stock_balance($product['inventory_product_id']);
				
				// if($stock <= 0)
				// {
				// 	$json['error'] = array();
				// 	$json['error']['minimum'] = 'Stock must greater than 0 for active product';
					
				// 	$this->output->set_output(json_encode($json));
				// 	exit;
				// }
			}
			
			if ($product_id) {
				$this->product_model->edit_product($product_id, $post);
				
				$json['success'] = lang('success_updated');
			} else {
				$product_id = $this->product_model->create_product($post);
				$json['success'] = lang('success_created');
			}
			
			// $this->load->model('api/products_model', 'api_products_model');
			// $this->api_products_model->update_product($product_id, $post);
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	/**
	 * Check SKU
	 * 
	 * @access public
	 * @param string $sku
	 * @return bool
	 */
	public function check_sku($sku)
	{
		// $this->load->model('api/products_model', 'api_products_model');
		
		// if ($this->api_products_model->check_sku($sku, $this->input->post('product_id'))) {
		// 	$this->form_validation->set_message('check_sku', 'Kode Produk (SKU) ini sudah digunakan!');
		// 	return false;
		// }
		
		return true;
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
		
		$upload_path = DIR_IMAGE.'products';
		
		if ( ! file_exists($upload_path)) {
			@mkdir($upload_path, 0777);
		}
		
		$this->load->library('upload', array(
			'upload_path' => $upload_path,
			'allowed_types' => 'gif|jpg|png|jpeg|heif|tiff',
			'max_size' => '5120',
			'overwrite' => false,
			'encrypt_name'=>true,
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
	
	/**
	 * Auto complete
	 * 
	 * @access public
	 * @return json
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
			
			foreach ($this->product_model->get_products_autocomplete($params) as $product) {
				$json[] = array(
					'product_id' => $product['product_id'], 
					'name' => strip_tags(html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8')),
					'sku' => $product['sku'],
					'price' => $product['price'],
					'slug' => $product['slug']
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
	
	/**
	 * ActiveToggle
	 * 
	 * @access public
	 * @return void
	 */
	public function toggleActive()
	{
		$json = array();
		
		$product_id = $this->input->post('product_id');
		$status = $this->input->post('status');
		
		if ( ! $this->admin->has_permission()) {
			$json['error'] = lang('admin_error_delete');
		}
		
		if ( ! $product_id) {
			$json['error'] = lang('error_no_selected');
		}
		
		if (empty($json['error'])) {
			$this->product_model->ActiveToggle($product_id,$status);
			$json['success'] = 'Produk berhasil di ubah statusnya';
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
		
		$product_id = $this->input->post('product_id');
		
		if ( ! $this->admin->has_permission()) {
			$json['error'] = lang('admin_error_delete');
		}
		
		if ( ! $product_id) {
			$json['error'] = lang('error_no_selected');
		}
		
		if (empty($json['error'])) {
			//check product order
			$this->load->model('order_model');

			if (is_array($product_id)) {
				$qty = $this->order_model->get_qty_product_order_all_array($product_id);
			} else {
				$qty = $this->order_model->get_qty_product_order_all($product_id);
			}
			
			if($qty == 0) {
				$this->product_model->delete($product_id);
				$json['success'] = lang('success_deleted');
			}
			else $json['error'] = 'Produk tidak bisa dihapus karena ada pesanan';
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	/**
	 * Synchronize products
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
			
			$stocks = array();
			$ids = array();
			$tids = $this->input->post('id') ;
			for ($o=0;$o<count($tids);$o++){
				array_push($ids,(int)explode(";",$tids[$o])[0]);
				array_push($stocks,(int)explode(";",$tids[$o])[1]);
			}
			//$this->load->model('api/products_model', 'api_products_model');
			
			$ids= $this->input->post('id') ? $this->input->post('id') : array();
			if ($ids) {
				//$this->api_products_model->synch_products($ids,$stocks);
				
				$json['success'] = 'Sinkronisasi data produk berhasil!';	
			} else {
				$json['error'] = lang('error_no_selected');
			}
		}
		
		$this->output->set_output(json_encode($json));
	}
}

/**
 * Get product link
 * 
 * @access public
 * @param string $slug
 * @param int $category_id
 * @return string
 */
function get_product_link($slug, $category_id) {
	$_ci =& get_instance();
	$_ci->load->model('category_model');
	
	$path = 'product/';
	
	if ($category = $_ci->category_model->get_category($category_id)) {
		$paths = explode('-', $category['path_id']);
		foreach ($paths as $path_id) {
			if ($c = $_ci->category_model->get_category($path_id)) {
				$path .= $c['slug'].'/';
			}
		}
		
		$path .= $category['slug'].'/';
	}
	
	$path .= $slug;
	
	return site_url($path);
}

/**
 * Count stock
 * 
 * @access public
 * @param mixed $product_id
 * @return int
 */
function count_stock($product_id) {
	// $_ci =& get_instance();
	// $_ci->load->model('api/stocks_model', 'api_stocks_model');
	
	// return $_ci->api_stocks_model->get_stock_balance($product_id);
}