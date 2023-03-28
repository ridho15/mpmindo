<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends MY_Controller
{
	private $limit = 20;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('currency');
		$this->load->model('product_model');
		$this->load->model('category_model');
		
		$this->load->helper('format');
	}
	
	/**
	 * Search results
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		$term = $this->input->get('term') ? $this->input->get('term') : '!@#$%^&*';
		$products = array();
		$categories = array();
				
		$this->load->library('pagination');
	
		$sort = ($this->input->get('sort')) ? $this->input->get('sort') : 'p.sort_order';
		$order = ($this->input->get('order')) ? $this->input->get('order') : 'asc';
		$page = ($this->input->get('page')) ? $this->input->get('page') : 1;
		$category_id = ($this->input->get('category_id')) ? $this->input->get('category_id') : 0;
			
		$query  = '';
		$query .= '&term='.$term;
		$query .= '&category_id='.$category_id;
				
		$params = array(
			'sort'	=> $sort,
			'order' => $order,
			'category_id' => $category_id,
			'name' => $term,
			'start' => ($page -1) * $this->limit,
			'limit' => $this->limit
		);
				
					
		$sorts = array();

		$sorts[] = array(
			'text' => 'Default',
			'value' => 'p.sort_order-asc',
			'href' => site_url('search?sort=p.sort_order&order=asc'.$query)
		);

		$sorts[] = array(
			'text' => 'Nama A &raquo; Z',
			'value' => 'p.name-asc',
			'href' => site_url('search?sort=p.name&order=asc'.$query)
		);
		
		$sorts[] = array(
			'text' => 'Nama Z &raquo; A',
			'value' => 'p.name-desc',
			'href' => site_url('search?sort=p.name&order=desc'.$query)
		);

		$sorts[] = array(
			'text' => 'Harga Rendah &raquo; Tinggi',
			'value' => 'p.price-asc',
			'href' => site_url('search?sort=p.price&order=asc'.$query)
		);
				
		$sorts[] = array(
			'text' => 'Harga Tinggi &raquo; Rendah',
			'value' => 'p.price-desc',
			'href' => site_url('search?sort=p.price&order=desc'.$query)
		);
				
		foreach ($this->product_model->get_products($params) as $product) {
			$path = '';
			
			if ($category = $this->category_model->get_category($product['category_id'])) {
				if ($category['path_id']) {
					$category_ids = explode('-', $category['path_id']);
					foreach ($category_ids as $category_idx) {
						if ($category_path = $this->category_model->get_category($category_idx)) {
							$path .= $category_path['slug'].'/';
						}
					}
				}
					
				$path .= $category['slug'].'/';
			}
					
			$quantity = $this->product_model->get_qty_product($product['product_id']);

			// $products[] = array(
			// 	'product_id' => $product['product_id'],
			// 	'thumb'	=> $this->image->resize($product['image'] ? $product['image'] : 'no_image.jpg', 180, 180),
			// 	'name' => strip_tags(html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8')),
			// 	'description' => substrwords(strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8')), 100),
			// 	'price' => $this->currency->format($product['price']),
			// 	'discount' => (float)$product['discount'] ? $this->currency->format($product['discount']) : false,
			// 	'quantity' => (int)$product['quantity'],
			// 	'wholesaler' => ($product['wholesaler'] > 1) ? true : false,
			// 	'discount_percent' => (float)$product['discount'] ? ceil(($product['price']-$product['discount'])/$product['price']*100).'%' : false,
			// 	'href' => site_url('product/'.$path.$product['slug'])
			// );
			$products[] = array(
				'product_id' => $product['product_id'],
				'thumb'	=> $this->image->resize($product['image'] ? $product['image'] : 'no_image.jpg', 180, 180),
				'name' => strip_tags(html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8')),
				'description' => substrwords(strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8')), 100),
				'price' => $this->currency->format($product['price']),
				'discount' => (float)$product['discount'] ? $this->currency->format($product['discount']) : false,
				'quantity' => (int)$quantity['qty'],
				'wholesaler' => ($product['wholesaler'] > 1) ? true : false,
				'discount_percent' => (float)$product['discount'] ? ceil(($product['price']-$product['discount'])/$product['price']*100).'%' : false,
				'href' => site_url('product/'.$path.$product['slug']),
				'href_title' => $path.$product['slug']
			);
		}
			
		$query  = '';
		$query .= '?term='.$term;
		$query .= '&sort='.$sort;
		$query .= '&order='.$order;
		$query .= '&category_id='.$category_id;
		
		$config = array(
			'base_url' => site_url('search').$query,
			'total_rows' => $this->product_model->count_products($params),
			'per_page' => $this->limit,
			'query_string_segment' => 'page',
			'page_query_string' => true,
			'full_tag_open' => '<ul class="pagination">',
			'full_tag_close' => '</ul>',
			'num_tag_open' => '<li>',
			'num_tag_close' => '</li>',
			'cur_tag_open' => '<li class="active"><a>',
			'cur_tag_close' => '</a></li>',
			'first_tag_open' => '<li>',
			'first_tag_close' => '</li>',
			'last_tag_open'	=> '<li>',
			'last_tag_close' => '</li>',
			'prev_tag_open'	=> '<li>',
			'prev_tag_close' => '</li>',
			'next_tag_open'	=> '<li>',
			'next_tag_close' => '</li>',
			'use_page_numbers' => true
		);
				
		$this->pagination->initialize($config);
				
			
		$data['products'] = $products;
		$data['term'] = $term;
		$data['sorts'] = $sorts;
		$data['order'] = $order;
		$data['sort'] = $sort;
		$data['category_id'] = $category_id;
		$data['heading_title'] = 'Pencarian Produk';
		$data['pagination'] = $this->pagination->create_links();
		$data['category_id'] = $this->input->get('category_id');
		$data['total'] = $this->product_model->count_products($params);
				
		$this->load
		->title('Pencarian Produk', $this->config->item('site_name'))
		->metadata('description', $this->config->item('meta_description'))
		->breadcrumb('Pencarian Produk', site_url('search'))
		->view('search', $data);
	}
	
	/**
	 * Ajax
	 * 
	 * @access public
	 * @return void
	 */
	public function ajax()
	{
		check_ajax();
		
		$json = array();
		
		if ($this->input->get('query')) {	
			$params = array(
				'name' => $this->input->get('query'),
				'description' => true,
				'start' => 0,
				'limit' => 15
			);
			
			foreach ($this->product_model->get_products($params) as $product) {
				$category = $this->category_model->get_category($product['category_id']);
				$json[] = array(
					'id' => $product['product_id'], 
					'name' => strip_tags(html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8')),
					'category' => 'Kategori ' . ($category ? $category['name'] : ''),
					'category_id' => $category ? $category['category_id'] : 0,
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