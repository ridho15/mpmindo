<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'models/api/Base_model.php';

class Products_model extends Base_model
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
	}
	
	/**
	 * Get iProducts multiple
	 * 
	 * @access public
	 * @param array $iproduct_ids
	 * @return array
	 */
	public function get_iproducts($iproduct_ids = array())
	{
		// $this->idb->set_dbprefix();
		
		// $query = $this->idb
		// /*->select('pr.product_id, pr.description, p.id as inventory_product_id, p.product_code as sku, p.product_name as name, p.tax_value, p.tax_type, TRUNCATE(p.limit_price - (p.limit_price / 11),2) AS base_price, TRUNCATE(p.limit_price - (p.limit_price / 11),2) AS price, c.category_id as category_id')*/
		// ->select('pr.product_id, pr.description, p.id as inventory_product_id, p.product_code as sku, p.product_name as name, p.limit_price as base_price, p.tax_value, p.tax_type, p.limit_price AS price, c.category_id as category_id')
		// ->from('knr_products p')
		// ->join('knr_stores s', 's.id = "'.(int)$this->config->item('store_id').'"', 'left')
		// ->join('knr_product_categories pc', 'pc.id = p.product_category_id', 'left')
		// ->join($this->edb->database.'.category c', 'c.inventory_category_id = pc.id', 'left')
		// ->join($this->edb->database.'.product pr', 'pr.inventory_product_id = p.id', 'left');
		
		// // ini jika hanya mengambil data produk yang hanya bermutasi dengan toko saja
		// // $query = $this->idb->where('SUBSTRING_INDEX(SUBSTRING_INDEX(p.mutation_place, "-", 1), "-", -1) = \'store\' AND SUBSTRING_INDEX(SUBSTRING_INDEX(p.mutation_place, "-", 2), "-", -1) = "'.(int)$this->config->item('store_id').'"', null, false);
		
		// if ($iproduct_ids) {
		// 	$query = $this->idb->where_in('p.id', $iproduct_ids);
		// }
		
		// $query = $this->idb->get()->result_array();
		
		// return $query;
		return array();
	}
	
	/**
	 * Get eProduct (single)
	 * 
	 * @access public
	 * @param array $params
	 * @return array
	 */
	public function get_eproduct($params)
	{
		// if ($params) {
		// 	return $this->edb
		// 	->select('p.product_id, p.inventory_product_id, p.sku, p.name, p.base_price, p.category_id, c.inventory_category_id, p.tax_value, p.tax_type, uc.title as unit')
		// 	->join('category c', 'c.category_id = p.category_id', 'left')
		// 	->join('unit_class uc', 'uc.unit_class_id = p.unit_class_id', 'left')
		// 	->where($params)
		// 	->get('product p')
		// 	->row_array();
		// } else {
		// 	return false;
		// }
		return array();
	}
	
	/**
	 * Synchronize products
	 * 
	 * @access public
	 * @return void
	 */
	public function synch_products($iproduct_ids = array(),$iproduct_stocks = array())
	{
		// $iproducts = $this->get_iproducts($iproduct_ids);
		
		// $this->load->model('product_model');
		// $products = [];
		// $pos_prod = 0;
		// foreach ($iproducts as $product) {
		// 	//di set menjadi always publish as active
		// 	$product['active'] = (int)$iproduct_stocks[$pos_prod] <= 0 ? 0:1;$pos_prod++;
		// 	if ($product['product_id']) {
		// 		$this->product_model->edit_product((int)$product['product_id'], $product);
		// 	} else {
		// 		$product['description'] = $product['name'];
				
		// 		$this->product_model->create_product($product);
		// 	}
		// }
	}
	
	/**
	 * Update product
	 *
	 * Untuk sementara hanya menambah produk di sistem inventory
	 * jika belum ada data saja
	 * 
	 * @access public
	 * @param int $product_id
	 * @return void
	 */
	public function update_product($product_id, $data)
	{
		// if ($eproduct = $this->get_eproduct(array('product_id' => $product_id))) {
		// 	if ( ! $eproduct['inventory_product_id']) {
		// 		$data = array(
		// 			'product_code' => $eproduct['sku'],
		// 			'product_name' => $eproduct['name'],
		// 			'limit_price' => (float)$eproduct['base_price'],
		// 			'tax_type' => $eproduct['tax_type'],
		// 			'tax_value' => $eproduct['tax_value'],
		// 			'product_category_id' => (int)$eproduct['inventory_category_id'],
		// 			'satuan' => $eproduct['unit'],
		// 			'mutation_place' => 'store-'.(int)$this->config->item('store_id'),
		// 			'big_capacity' => isset($data['big_capacity']) ? (int)$data['big_capacity'] : 1,
		// 			'big_sell_qty' => isset($data['big_sell_qty']) ? (int)$data['big_sell_qty'] : 1,
		// 			'cost' => isset($data['cost']) ? (float)$data['cost'] : 0,
		// 			'percent_limit' => isset($data['percent_limit']) ? (float)$data['percent_limit'] : 100,
		// 			'kd_merk' => isset($data['kd_merk']) ? $data['kd_merk'] : '',
		// 		);
				
		// 		$this->idb->insert('products', $data);
		// 		$iproduct_id = $this->idb->insert_id();
				
		// 		$product_stocks = array();
				
		// 		$this->load->model('api/places_model', 'api_places_model');
				
		// 		foreach ($this->api_places_model->get_places() as $place) {
		// 			$product_stocks[] = array(
		// 				'product_id' => (int)$iproduct_id,
		// 				'place_type' => $place['type'],
		// 				'place_id' => (int)$place['place_id'],
		// 				'min_qty' => 1,
		// 			);
		// 		}
				
		// 		$this->idb->insert_batch('product_stocks', $product_stocks);
				
		// 		$this->edb
		// 		->where('product_id', $product_id)
		// 		->set('inventory_product_id', $iproduct_id)
		// 		->update('product');
				
		// 		$this->load->model('api/action_logs_model', 'api_action_logs_model');
				
		// 		$this->api_action_logs_model->insert_log('PRODUK', 'TAMBAH', $this->admin->name().' telah menambah data produk '.$eproduct['name'].' melalui sistem ecommerce.', strtolower(str_replace(' ', '', $this->admin->name())));
		// 	}
		// }
	}
	
	/**
	 * Check SKU
	 * 
	 * @access public
	 * @param string $sku
	 * @param int $product_id
	 * @return bool
	 */
	public function check_sku($sku, $product_id = null)
	{
		// $check = $this->idb->select('id');
		
		// if ($eproduct = $this->get_eproduct(array('product_id' => $product_id))) {
		// 	if ($eproduct['inventory_product_id']) {
		// 		$check = $this->idb->where('id !=', $eproduct['inventory_product_id']);
		// 	}
		// }
		
		// $check = $this->idb->where('product_code', $sku)->count_all_results('products');
		
		// return (bool)$check;
		return true;
	}
	
	/**
	 * Get tax by SKU
	 * 
	 * @access public
	 * @param string $sku
	 * @return array | bool
	 */
	public function get_tax_by_sku($sku = false)
	{
		// if ($sku) {
		// 	return $this->idb
		// 	->select('tax_value, tax_type')
		// 	->where('product_code', $sku)
		// 	->get('products')
		// 	->row_array();
		// } else {
		// 	return false;
		// }
		return true;
	}
	
	/**
	 * Get iproduct name
	 * 
	 * @access public
	 * @param mixed $product_id
	 * @return string
	 */
	public function get_iproduct_name($product_id)
	{
		// $query = $this->idb
		// ->select('product_name')
		// ->where('id', $product_id)
		// ->get('products')
		// ->row_array();
		
		// return $query ? $query['product_name'] : '';
		return '';
	}
}