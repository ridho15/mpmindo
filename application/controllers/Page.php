<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends MY_Controller
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
		
		$this->load->model('product_model');
		$this->load->model('category_model');
		$this->load->model('page_model');
		
		$this->load->helper('format');
	}
	
	/**
	 * Index
	 * 
	 * @access public
	 * @param bool $slug (default: false)
	 * @return void
	 */
	public function index($slug = false)
	{
		if ($page = $this->page_model->get_page($slug)) {
			foreach ($page as $key => $value) {
				$data[$key] = $value;
			}
			
			$this->load
			->title($page['title'])
			->metadata('description', $page['meta_description'])
			->metadata('keywords', $page['meta_keyword'])
			->breadcrumb($data['title'], site_url('page/'.$data['slug']))
			->view('page', $data);
		} else {
			show_404();
		}
	}
	
	/**
	 * Home
	 * 
	 * @access public
	 * @return void
	 */
	public function home()
	{
		$data = array();
		$this->load->model('category_model');
		
		$data['categories'] = array();
		
		foreach ($this->category_model->get_subcategories(0) as $category) {
			if ($category['top']) {
				$data['categories'][] = array(
					'name' => $category['name'],
					'href' => site_url('category/'.$category['slug']),
					'menu_image' => $category['menu_image']
				);
			}
		}
		
		$this->load->model('banner_model');
		
		$data['banners'] = array();
		
		if ($this->agent->is_mobile()) {
			$banners = $this->banner_model->get_images($this->config->item('slideshow_mobile_banner_id'));
		} else {
			$banners = $this->banner_model->get_images($this->config->item('slideshow_banner_id'));	
		}
		
		foreach ($banners as $banner) {
			if ($banner['active']) {
				if ($this->agent->is_mobile()) {
					$image = $this->image->resize($banner['image'] ? $banner['image'] : 'no_image.jpg', 480);
				} else {
					$image = $this->image->resize($banner['image'] ? $banner['image'] : 'no_image.jpg', 1230);
				}
				
				$data['banners'][] = array(
					'image' => $image,
					'title' => $banner['title'],
					'subtitle' => $banner['subtitle'],
					'link' => $banner['link'],
					'link_title' => $banner['link_title'],
				);
			}
 		}
 		
 		$data['banner_ads'] = array();
		
		$banner_ads = $this->banner_model->get_images($this->config->item('ads_banner_id'));
		
		foreach ($banner_ads as $banner) {
			if ($banner['active']) {
				$image = $this->image->resize($banner['image'] ? $banner['image'] : 'no_image.jpg', 480);
				
				$data['banner_ads'][] = array(
					'image' => $image,
					'title' => $banner['title'],
					'subtitle' => $banner['subtitle'],
					'link' => $banner['link'],
					'link_title' => $banner['link_title'],
				);
			}
 		}
 		
 		$data['specials'] = $this->_get_specials();
 		$data['carousels'] = $this->_get_carousels();
		
		if ($this->config->item('footer_cards')) {
			$this->load->helper('array');
			$data['footer_cards'] = reorder($this->config->item('footer_cards'), 'sort_order', 'asc');
		} else {
			$data['footer_cards'] = array();
		}
		
		$this->load
		->title($this->config->item('site_name'), $this->config->item('seo_title'))
		->metadata('description', $this->config->item('meta_description'))
		->metadata('keyword', $this->config->item('meta_keywords'))
		->view('home', $data);
	}
	
	/**
	 * Get specials
	 * 
	 * @access public
	 * @return array
	 */
	public function _get_specials()
	{
		$products = array();
		
		foreach ($this->product_model->get_product_specials() as $product) {
			$path = '';
			
			if ($category = $this->category_model->get_category($product['category_id'])) {
				if ($category['path_id']) {
					$category_ids = explode('-', $category['path_id']);
					
					foreach ($category_ids as $category_id) {
						if ($category_path = $this->category_model->get_category($category_id)) {
							$path .= $category_path['slug'].'/';
						}
					}
				}
					
				$path .= $category['slug'].'/';
			}
							
			$path .= $product['slug'];
				
			if ($this->is_mobile) {
				$name = substrwords(strip_tags(html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8')), 28);
			} else {
				$name = strip_tags(html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8'));
			}
			
			$products[] = array(
				'product_id' => $product['product_id'],
				'thumb' => $this->image->resize($product['image'] ? $product['image'] : 'no_image.jpg', 200, 200),
				'name' => $name,
				'description' => substrwords(strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8')), 100),
				'price'	=> calculate_tax($product['price'], $product['tax_value'], $product['tax_type']),
				'discount' => (float)$product['discount'] ? calculate_tax($product['discount'], $product['tax_value'], $product['tax_type']) : false,
				'discount_percent' => (float)$product['discount'] ? ceil(($product['price']-$product['discount'])/$product['price']*100).'%' : false,
				'href' => site_url('product/'.$path),
				'quantity' => (int)$product['quantity']
			);
		}
			
		return $products;
	}
	
	/**
	 * Get carousels
	 * 
	 * @access public
	 * @return array
	 */
	public function _get_carousels()
	{
		$carousel_categories = $this->config->item('carousel_categories') ? $this->config->item('carousel_categories') : array();
		
		$categories = array();
		
		foreach ($carousel_categories as $category_id) {
			if ($category = $this->category_model->get_category($category_id)) {
				$params = array(
					'category_id' => (int)$category['category_id'],
					'start' => 0,
					'limit' => 8
				);
				
				$products = array();
						
				foreach ($this->product_model->get_products($params) as $product) {
					$path = '';
			
					if ($category = $this->category_model->get_category($product['category_id'])) {
						if ($category['path_id']) {
							$category_ids = explode('-', $category['path_id']);
							foreach ($category_ids as $category_id) {
								if ($category_path = $this->category_model->get_category($category_id)) {
									$path .= $category_path['slug'].'/';
								}
							}
						}
						
						$path .= $category['slug'].'/';
					}
								
						$name = strip_tags(html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8'));
					$path .= $product['slug'];
					$products[] = array(
						'product_id' => $product['product_id'],
						'thumb' => $this->image->resize($product['image'] ? $product['image'] : 'no_image.jpg', 200, 200),
						'name' => $name,
						'description' => substrwords(strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8')), 28),
						'price' => calculate_tax($product['price'], $product['tax_value'], $product['tax_type']),
						'discount' => (float)$product['discount'] ? calculate_tax($product['discount'], $product['tax_value'], $product['tax_type']) : false,
						'discount_percent' => (float)$product['discount'] ? ceil(($product['price']-$product['discount'])/$product['price']*100).'%' : false,
						'href' => site_url('product/'.$path),
						'quantity' => (int)$product['quantity']
					);
				}
				
				$href = '';
				
				$ids = explode('-', $category['path_id']);
				
				foreach ($ids as $id) {
					if ($cat = $this->category_model->get($id)) {
						$href .= $cat['slug'].'/';
					}
				}
				
				$href .= $category['slug'];
				
				$categories[] = array(
					'category_id' => $category['category_id'],
					'href' => site_url('category/'.$href),
					'name' => $category['name'],
					'image' => $this->image->resize($category['image'], 300, 300),
					'products' => $products,
				);	
			}
		}
				
		return $categories;
	}
	
	/**
	 * Success
	 * 
	 * @access public
	 * @return void
	 */
	public function success()
	{
		$data = $this->session->flashdata('action_success');
		
		if ($data) {
			$this->load
			->title($data['title'], $this->config->item('site_name'))
			->view('page_success', $data);
		} else {
			show_404();
		}
	}
}