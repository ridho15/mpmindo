<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Special extends MY_Controller
{
	private $limit = 20;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('currency');
		$this->load->model('product_model');
		$this->load->helper('format');
		$this->load->helper('form');
	}
	
	public function index()
	{
		$breadcrumbs = array();
		
		$breadcrumbs[] = array(
			'text' => 'Home',
			'href' => site_url()
		);
		
		$breadcrumbs[] = array(
			'text' => 'Special',
			'href' => site_url('special')
		);
					
		$this->load->vars('breadcrumbs', $breadcrumbs);
			
		$products = array();
		$categories = array();
				
		$this->load->library('pagination');
	
		$sort = ($this->input->get('sort')) ? $this->input->get('sort') : 'p.sort_order';
		$order = ($this->input->get('order')) ? $this->input->get('order') : 'asc';
		$page = ($this->input->get('page')) ? $this->input->get('page') : 1;
		$city_id = ($this->input->get('city_id')) ? $this->input->get('city_id') : 0;
		$province_id = ($this->input->get('province_id')) ? $this->input->get('province_id') : 0;
				
		$query  = '';
		$query .= '&province_id='.$province_id;
		$query .= '&city_id='.$city_id;
				
		$params = array(
			'sort'	=> $sort,
			'order' => $order,
			'city_id' => $city_id,
			'province_id' => $province_id,
			'start' => ($page -1) * $this->limit,
			'limit' => $this->limit
		);
				
		$sorts = array();

		$sorts[] = array(
			'text' => 'Default',
			'value' => 'p.sort_order-asc',
			'href' => site_url('special?sort=p.sort_order&order=asc'.$query)
		);

		$sorts[] = array(
			'text' => 'Nama A &raquo; Z',
			'value' => 'p.name-asc',
			'href' => site_url('special?sort=p.name&order=asc'.$query)
		);
		
		$sorts[] = array(
			'text' => 'Nama Z &raquo; A',
			'value' => 'p.name-desc',
			'href' => site_url('special?sort=p.name&order=desc'.$query)
		);

		$sorts[] = array(
			'text' => 'Harga Rendah &raquo; Tinggi',
			'value' => 'p.price-asc',
			'href' => site_url('special?sort=p.price&order=asc'.$query)
		);
				
		$sorts[] = array(
			'text' => 'Harga Tinggi &raquo; Rendah',
			'value' => 'p.price-desc',
			'href' => site_url('special?sort=p.price&order=desc'.$query)
		);
				
		$sorts[] = array(
			'text'=> 'Rating Terendah &raquo; Tertinggi',
			'value' => 'rating-desc',
			'href'=> site_url('special?sort=rating&order=desc'.$query)
		); 

		$sorts[] = array(
			'text'=> 'Rating Tertinggi &raquo; Terendah',
			'value' => 'rating-asc',
			'href'=> site_url('special?sort=rating&order=asc'.$query)
		);
				
						$name = strip_tags(html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8'));
		foreach ($this->product_model->get_product_specials($params) as $product) {
			$href = '';
					
			$products[] = array(
				'product_id'		=> $product['product_id'],
				'thumb'				=> $this->image->resize($product['image'] ? $product['image'] : 'no_image.jpg', 180, 180),
				'name'				=> $name,
				'description'		=> substr(strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8')), 0, 100).'...',
				'price'				=> $this->currency->format($product['price']),
				'discount'			=> (float)$product['discount'] ? $this->currency->format($product['discount']) : false,
				'wholesaler'		=> ($product['wholesaler'] > 1) ? true : false,
				'discount_percent'	=> (float)$product['discount'] ? ceil(($product['price']-$product['discount'])/$product['price']*100).'%' : false,
				'rating'			=> $product['rating'],
				'href'				=> site_url('special/'.$product['slug']),
				'store'				=> $product['store'],
				'location'			=> $product['location'],
			);
		}
		
		$query  = '';
		$query .= '?sort='.$sort;
		$query .= '&order='.$order;
		$query .= '&province_id='.$province_id;
		$query .= '&city_id='.$city_id;
				
		$config = array(
			'base_url'				=> site_url('special').$query,
			'total_rows'			=> $this->product_model->count_product_specials($params),
			'per_page'				=> $this->limit,
			'query_string_segment'	=> 'page',
			'page_query_string'		=> true,
			'full_tag_open'			=> '<ul class="pagination">',
			'full_tag_close'		=> '</ul>',
			'num_tag_open'			=> '<li>',
			'num_tag_close'			=> '</li>',
			'cur_tag_open'			=> '<li class="active"><a>',
			'cur_tag_close'			=> '</a></li>',
			'first_tag_open'		=> '<li>',
			'first_tag_close'		=> '</li>',
			'last_tag_open'			=> '<li>',
			'last_tag_close'		=> '</li>',
			'prev_tag_open'			=> '<li>',
			'prev_tag_close'		=> '</li>',
			'next_tag_open'			=> '<li>',
			'next_tag_close'		=> '</li>',
			'use_page_numbers'		=> true
		);
				
		$this->pagination->initialize($config);
				
		$data['products'] = $products;
		$data['sorts'] = $sorts;
		$data['order'] = $order;
		$data['sort'] = $sort;
		$data['province_id'] = $province_id;
		$data['city_id'] = $city_id;
		$data['heading_title'] = 'Special';
		$data['pagination'] = $this->pagination->create_links();
		
		$this->load->model('location_model');
		
		$data['provinces'] = $this->location_model->get_provinces();
				
		$this->load
		->title('Produk spesial', $this->config->item('site_name'))
		->metadata('description', $this->config->item('meta_description'))
		->view('special', $data);
	}
	
	public function product($slug)
	{
		$breadcrumbs = array();
		
		$breadcrumbs[] = array(
			'text' => 'Home',
			'href' => site_url()
		);
		
		$breadcrumbs[] = array(
			'text' => 'Special',
			'href' => site_url('special')
		);
		
		$data = $this->product_model->get_product($slug);
		
		if ($data) {
			$data['images'] = array();
			$images = $this->product_model->get_product_images($data['product_id']);
			foreach ($images as $image) {
				$data['images'][] = array(
					'popup' => $this->image->resize($image['image'], 500, 500),
					'thumb' => $this->image->resize($image['image'], 50, 50),
				);
			}
					
			if (isset($image)) {
				$data['popup'] = $this->image->resize($image['image'], 500, 500);
				$data['thumb'] = $this->image->resize($image['image'], 350, 350);
			}
					
			$this->load->library('currency');
			
			$data['price'] = $this->currency->format($data['price']);
			
			if ($data['discount']) {
				$data['discount'] = $this->currency->format($data['discount']);
			} else {
				$data['discount'] = false;
			}
			
			$this->load->library('weight');
			
			$data['weight'] = $this->weight->format($data['weight'], $data['weight_class_id']);
			
			$breadcrumbs[] = array(
				'text' => $data['name'],
				'href' => site_url('special/'.$data['slug'])
			);
			
			$data['breadcrumbs'] = $breadcrumbs;
			$data['discounts'] = array();
			
			$discounts = $this->product_model->get_product_discounts($data['product_id']);
			
			foreach ($discounts as $discount) {
				$data['discounts'][] = array(
					'quantity' => $discount['quantity'],
					'price' => $this->currency->format($discount['price'])
				);
			}
			
			$store = $this->store_model->get_store($data['store_id']);
			
			$data['store_name'] = $store['name'];
			$data['store_logo'] = $this->image->resize($store['logo'], 80);
			$data['store_url'] = site_url('store/'.$store['domain']);
			$data['store_last_login'] = format_time_ago($store['last_login']);
			
			$data['product_sold_count'] = $this->db
			->select('SUM(op.quantity) AS count', false)
			->join('order_product op', 'op.order_id = so.order_id', 'left')
			->join('product p', 'op.product_id = p.product_id', 'left')
			->from('store_order so')
			->where('so.store_id', $data['store_id'])
			->where('p.store_id', $data['store_id'])
			->where('so.order_status_id', $this->config->item('order_complete_status_id'))
			->get()
			->row()
			->count;
					
			$data['review_count'] = $this->db
			->select('review_id')
			->where('store_id', $data['store_id'])
			->where('active', 1)
			->count_all_results('review');
			
			$data['quality_rate'] = $this->db
			->select_avg('quality')
			->from('review')
			->where('store_id', $data['store_id'])
			->where('active', 1)
			->get()
			->row_array();
			
			$data['accuracy_rate'] = $this->db
			->select_avg('accuracy')
			->from('review')
			->where('store_id', $data['store_id'])
			->where('active', 1)
			->get()
			->row_array();
			
			$data['sold'] = $this->db
			->select('SUM(op.quantity) AS count', false)
			->join('order_product op', 'op.order_id = so.order_id', 'left')
			->join('product p', 'op.product_id = p.product_id', 'left')
			->from('store_order so')
			->where('op.product_id', $data['product_id'])
			->where('p.product_id', $data['product_id'])
			->where('so.order_status_id', $this->config->item('order_complete_status_id'))
			->get()
			->row()
			->count;
			
			$store_shippings = $this->store_model->get_store_shipping($store['store_id']);
			$this->load->library('rajaongkir');
			$couriers = $this->rajaongkir->get_couriers();
					
			foreach ($store_shippings as $key => $setting) {
				if (isset($couriers[$key])) {
					$data['store_shippings'][] = $couriers[$key];
				}
			}
					
			$this->product_model->update_view($data['product_id']);
			
			$this->load
			->title($data['meta_title'], $this->config->item('site_name'))
			->metadata('description', $data['meta_description'])
			->metadata('keyword', $data['meta_keyword'])
			->view('product', $data);
		} else {
			show_404();
		}
	}
}