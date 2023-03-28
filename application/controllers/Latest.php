<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Latest extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$data = array();
		
		$this->load->library('image');
		$this->load->helper('format');
		
		$categories = $this->get_categories();
		
		$tabs_products = array();
		$products = $this->get_products($category['category_id']);
		foreach ($products as $product) {
			$tabs_products[] = array(
				'href' => store_url($product['slug']),
				'product_id' => $product['product_id'],
				'name' => $product['name'],
				'price' => $product['price'],
				'date_end' => $product['date_end'], 
				'special' => $product['discount'],
				'thumb' => $product['image'] ? $this->image->resize($product['image'], 150, 150) : $this->image->resize('no_image.jpg', 150, 150),
			);
		}
		
		return $this->load
		->view('module/latest', $data, true);
	}
	
	private function get_products($category_id = null)
	{
		return $this->db
		->select("p.product_id, r.slug, p.name, p.price, p.image, 
		(SELECT price FROM product_discount pd WHERE pd.product_id = p.product_id AND pd.quantity = '1' AND ((pd.date_start = '0000-00-00' OR pd.date_start < NOW()) AND (pd.date_end = '0000-00-00' OR pd.date_end > NOW())) ORDER BY pd.priority ASC, pd.price ASC LIMIT 1) AS discount, 
		(SELECT date_end FROM product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS date_end", false)
		->from('category_path cp')
		->join('product_category pc', 'cp.category_id = pc.category_id', 'left')
		->join('product p', 'pc.product_id = p.product_id', 'left')
		->join('route r', 'r.route_id = p.route_id', 'left')
		->join('product_store ps', 'ps.product_id = p.product_id', 'left')
		->join('category_store cs', 'cs.category_id = pc.category_id', 'left')
		->where('active', 1)
		->where('ps.store_id', (int)$this->config->item('store_id'))
		->where('cs.store_id', (int)$this->config->item('store_id'))
		->where('cp.path_id', (int)$category_id)
		->group_by('p.product_id')
		->order_by('p.sort_order', 'asc')
		->limit(12)
		->get()
		->result_array();
	}
}