<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Compare_product extends MY_Controller
{
	private $limit = 12;
	
	/**
	 * Constructor
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('currency');
		$this->load->model('category_model');
		$this->load->model('product_model');
		
		$this->load->helper('format');
	}
	
	/**
	 * Index
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		$segments = $this->uri->segment_array();

		if (count($segments) < 2) {
			show_404();
		} else {
			$data = array();

			$category = false;
			$slugs = $segments;
			array_shift($segments);
			$slug = array_pop($segments);
			$path = '';
			$data['category_id'] = null;
			
			foreach ($slugs as $slug) {
				if ($category = $this->category_model->get_category($slug)) {
					$path .= $category['slug'].'/';
				}
			}
			$product_compare = $this->product_model->get_product_by_slug($slug);

			foreach ($product_compare as $product) {

				$href = ($path != '') ? 'product/'.$path.$product['slug'] : 'product/'.$product['slug'];
				$name = strip_tags(html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8'));
				$this->load->model('order_model');
				$ratings = $this->order_model->get_rating_values($product['product_id']);
	
				$quantity = $this->product_model->get_qty_product($product['product_id']);

				// $products = array(
				// 	'product_id' => $product['product_id'],
				// 	'thumb' => $this->image->resize($product['image'] ? $product['image'] : 'no_image.jpg', 180, 180),
				// 	'name' => $name,
				// 	'description' => $product['description'],
				// 	'price' => calculate_tax($product['price'], $product['tax_value'], $product['tax_type']),
				// 	'discount' => (float)$product['discount'] ? calculate_tax($product['discount'], $product['tax_value'], $product['tax_type']) : false,
				// 	'quantity' => (int)$product['quantity'],
				// 	'wholesaler' => ($product['wholesaler'] > 1) ? true : false,
				// 	'discount_percent' => (float)$product['discount'] ? ceil(($product['price']-$product['discount'])/$product['price']*100).'%' : false,
				// 	'href' => site_url($href),
				// 	'count_comment' => $ratings['count'],
				// 	'rating_value' => $ratings['value'],
				// 	'quantity_sale' => $this->order_model->get_sum_sale($product['product_id']),
				// 	'specification' => $product['specification'],
				// 	'whatisinthebox' => $product['whatisinthebox']
				// );
				$products = array(
					'product_id' => $product['product_id'],
					'thumb' => $this->image->resize($product['image'] ? $product['image'] : 'no_image.jpg', 180, 180),
					'name' => $name,
					'description' => $product['description'],
					'price' => calculate_tax($product['price'], $product['tax_value'], $product['tax_type']),
					'discount' => (float)$product['discount'] ? calculate_tax($product['discount'], $product['tax_value'], $product['tax_type']) : false,
					'quantity' => (int)$quantity['qty'],
					'wholesaler' => ($product['wholesaler'] > 1) ? true : false,
					'discount_percent' => (float)$product['discount'] ? ceil(($product['price']-$product['discount'])/$product['price']*100).'%' : false,
					'href' => site_url($href),
					'count_comment' => $ratings['count'],
					'rating_value' => $ratings['value'],
					'quantity_sale' => $this->order_model->get_sum_sale($product['product_id']),
					'specification' => $product['specification'],
					'whatisinthebox' => $product['whatisinthebox']
				);
				$data['product'] = $products;
			}

			$product_names = $this->db
			->select('name, slug', false)
			->from('product o')
			->order_by('name')
			->get()
			->result_array();

			$data['product_names'] = $product_names;

			$this->load->view('compare_product', $data);
		}
	}
	/**
	 * Get Product Autocomplete
	 * 
	 * @access public
	 * @return json
	 */
	public function find_product()
	{
		$json = array();
		
		$slug = $this->input->get('slug');

		$product_compare = $this->product_model->get_product_by_slug($slug);

		foreach ($product_compare as $product) {

			$data['category'] = false;
			$data['category_link'] = false;
			$path = '';
			$category = $this->category_model->get_category($product['category_id']);
			
			if ($category) {
				$data['category'] = $category['name'];
				$path_ids = explode('-', $category['path_id']);
				
				foreach ($path_ids as $category_id) {
					if ($category_path = $this->category_model->get($category_id)) {
						$path .= $category_path['slug'].'/';
						$data['category'] = $category_path['name'];
					}
				}
				
				$path .= $category['slug'].'/';
				$data['category_link'] = site_url('category/'.$path);
			}

			$name = strip_tags(html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8'));
			$this->load->model('order_model');
			$ratings = $this->order_model->get_rating_values($product['product_id']);

			$quantity = $this->product_model->get_qty_product($product['product_id']);

			// $products = array(
			// 	'product_id' => $product['product_id'],
			// 	'thumb' => $this->image->resize($product['image'] ? $product['image'] : 'no_image.jpg', 180, 180),
			// 	'name' => $name,
			// 	'description' => $product['description'],
			// 	'price' => calculate_tax($product['price'], $product['tax_value'], $product['tax_type']),
			// 	'discount' => (float)$product['discount'] ? calculate_tax($product['discount'], $product['tax_value'], $product['tax_type']) : false,
			// 	'quantity' => (int)$product['quantity'],
			// 	'wholesaler' => ($product['wholesaler'] > 1) ? true : false,
			// 	'discount_percent' => (float)$product['discount'] ? ceil(($product['price']-$product['discount'])/$product['price']*100).'%' : false,
			// 	'href' => site_url('product/'.$path.$product['slug']),
			// 	'count_comment' => $ratings['count'],
			// 	'rating_value' => $ratings['value'],
			// 	'quantity_sale' => $this->order_model->get_sum_sale($product['product_id']),
			// 	'specification' => $product['specification'],
			// 	'whatisinthebox' => $product['whatisinthebox']
			// );
			$products = array(
				'product_id' => $product['product_id'],
				'thumb' => $this->image->resize($product['image'] ? $product['image'] : 'no_image.jpg', 180, 180),
				'name' => $name,
				'description' => $product['description'],
				'price' => calculate_tax($product['price'], $product['tax_value'], $product['tax_type']),
				'discount' => (float)$product['discount'] ? calculate_tax($product['discount'], $product['tax_value'], $product['tax_type']) : false,
				'quantity' => (int)$quantity['qty'],
				'wholesaler' => ($product['wholesaler'] > 1) ? true : false,
				'discount_percent' => (float)$product['discount'] ? ceil(($product['price']-$product['discount'])/$product['price']*100).'%' : false,
				'href' => site_url('product/'.$path.$product['slug']),
				'count_comment' => $ratings['count'],
				'rating_value' => $ratings['value'],
				'quantity_sale' => $this->order_model->get_sum_sale($product['product_id']),
				'specification' => $product['specification'],
				'whatisinthebox' => $product['whatisinthebox']
			);
			$data['product'] = $products;
		}
	
		$this->output->set_output(json_encode(array(
			'content' => $this->load->layout(null)->view('compare_product_result', $data, true)
		)));
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
}