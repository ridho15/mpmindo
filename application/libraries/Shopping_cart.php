<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shopping_cart
{
	/**
	 * CI Instance
	 * 
	 * @var object
	 * @access private
	 */
	private $ci;
	
	/**
	 * Items data
	 * 
	 * @var array
	 * @access private
	 */
	private $data = array();
	
	/**
	 * Shopping cart content
	 * 
	 * @var array
	 * @access private
	 */
	private $content = array();
	
	/**
	 * Constructor
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		$this->ci =& get_instance();
		
		$this->content = $this->ci->session->userdata('cart');
		
		if ( ! $this->content || ! is_array($this->content)) {
			$this->content = array();
		}
		
		$this->ci->load->library('weight');
	}
	
	/**
	 * Get product
	 * 
	 * @access public	
	 * @param int $product_id
	 * @return array
	 */
	private function _get_product($product_id)
	{
		$product = $this->ci->db
		->select('p.product_id, p.name, p.sku, p.minimum, p.price, p.weight, p.weight_class_id, p.category_id, p.slug, p.shipping, p.tax_value, p.tax_type, (SELECT pi.image FROM product_image pi WHERE pi.product_id = p.product_id LIMIT 1) AS image')
		->from('product p')
		->where('p.product_id', (int)$product_id)
		->where('p.active', 1)
		->get()
		->row_array();
		
		if ($product) {
			$quantity = $this->ci->db
				->select("(SELECT count(0) FROM article i WHERE i.product_id = p.product_id AND i.warehouse_id = '2' AND p.product_id = ".(int)$product['product_id'].") AS quantity", false)
				->from('product p')
				->get()
				->row_array();

			$product['quantity'] = $quantity['quantity'];

			// $this->ci->load->model('api/stocks_model', 'api_stocks_model');
			//$product['quantity'] = $this->ci->api_stocks_model->get_stock_balance($product['product_id']);
			
			return $product;
		} else {
			return array();
		}
	}
	
	/**
	 * Get product discount
	 * 
	 * @access private
	 * @param int $product_id
	 * @param int $discount_quantity
	 * @return array
	 */
	private function _get_product_discount($product_id, $discount_quantity)
	{
		return $this->ci->db
		->select('price')
		->where('product_id', (int)$product_id)
		->where('quantity >', 1)
		->where('quantity <=', (int)$discount_quantity)
		->where("(date_start = '0000-00-00' || date_start < NOW())", null, false)
		->where("(date_end = '0000-00-00' || date_end > NOW())", null, false)
		->order_by('quantity', 'desc')
		->order_by('priority', 'asc')
		->order_by('price', 'asc')
		->limit(1)
		->get('product_discount')
		->row_array();
	}
	
	/**
	 * Get produc special
	 * 
	 * @access private
	 * @param int $product_id
	 * @return array
	 */
	private function _get_product_special($product_id)
	{
		return $this->ci->db
		->select('price')
		->where('product_id', (int)$product_id)
		->where('quantity', 1)
		->where("(date_start = '0000-00-00' || date_start < NOW())", null, false)
		->where("(date_end = '0000-00-00' || date_end > NOW())", null, false)
		->order_by('priority', 'asc')
		->order_by('price', 'asc')
		->limit(1)
		->get('product_discount')
		->row_array();
	}
	
	/**
	 * Get products data
	 * 
	 * @access public
	 * @return void
	 */
	public function get_products()
	{
		if ( ! $this->data) {
			foreach ($this->content as $product_id => $quantity) {
				$stock = true;
				
				if ($product = $this->_get_product($product_id)) {
					$price = $product['price'];
					
					$discount_quantity = 0;

					foreach ($this->content as $product_id_2 => $quantity_2) {
						if ($product_id_2 == $product_id) {
							$discount_quantity += $quantity_2;
						}
					}
					
					if ($product_special = $this->_get_product_special($product_id)) {
						$price = $product_special['price'];
					}
					
					if ($product_discount = $this->_get_product_discount($product_id, $discount_quantity)) {
						$price = $product_discount['price'];
					}
					
					if ( ! $product['quantity'] || ((int)$product['quantity'] < $quantity)) {
						$stock = false;
					}
					
					$price_base = $price;
					$total_base = $price * $quantity;
						
					// if ($product['tax_value']) {
					// 	$tax = $price * ($product['tax_value']/100);
					// 	$tax_total = $tax * $quantity;
						
					// 	if ($product['tax_type'] == 'Inklusif') {
					// 		$price_base = $price - $tax;
					// 		$total_base = ($price * $quantity) - $tax_total;
					// 	}
					// } else {
						$tax_total = 0;
					//}

					$this->data[$product_id] = array(
						'key' => $product_id,
						'product_id' => $product['product_id'],
						'name' => $product['name'],
						'sku' => $product['sku'],
						'image'	=> $product['image'],
						'quantity' => $quantity,
						'minimum' => $product['minimum'],
						'stock'	=> $stock,
						'price'	=> $price,
						'price_base' => $price_base,
						'total'	=> $price * $quantity,
						'total_base' => $total_base,
						'weight' => $product['weight'] * $quantity,
						'weight_class_id' => $product['weight_class_id'],
						'category_id' => $product['category_id'],
						'slug' => $product['slug'],
						'shipping' => $product['shipping'],
						'tax_value' => (float)$product['tax_value'],
						'tax_type' => $product['tax_type'],
						'tax_total' => $tax_total
					);
				} else {
					$this->remove($product_id);
				}
			}
		}

		return $this->data;
	}
	
	/**
	 * Add new item into shopping cart
	 * 
	 * @access public
	 * @param int $product_id
	 * @param int $quantity
	 * @return void
	 */
	public function add($product_id, $quantity = 1)
	{
		if ((int)$quantity && ((int)$quantity > 0)) {
			if ( ! isset($this->content[$product_id])) {
				$this->content[$product_id] = (int)$quantity;
			} else {
				$this->content[$product_id] += (int)$quantity;
			}
			
			$this->ci->session->set_userdata('cart', $this->content);
		}

		$this->data = array();
	}
	
	/**
	 * Update an item from shopping cart
	 * 
	 * @access public
	 * @param string $key
	 * @param int $quantity
	 * @return void
	 */
	public function update($key, $quantity)
	{
		if ((int)$quantity && ((int)$quantity > 0)) {
			$this->content[$key] = (int)$quantity;
			
			$this->ci->session->set_userdata('cart', $this->content);
		} else {
			$this->remove($key);
		}

		$this->data = array();
	}
	
	/**
	 * Remove an item from shopping cart
	 * 
	 * @access public
	 * @param string $key
	 * @return void
	 */
	public function remove($key)
	{
		if (isset($this->content[$key])) {
			unset($this->content[$key]);
			
			$this->ci->session->set_userdata('cart', $this->content);
		}

		$this->data = array();
	}
	
	/**
	 * Clear shopping cart
	 * 
	 * @access public
	 * @return void
	 */
	public function clear()
	{
		$this->content = array();
		$this->ci->session->set_userdata('cart', array());
		
		$this->data = array();
	}
	
	/**
	 * Get weight
	 * 
	 * @access public
	 * @return void
	 */
	public function get_weight()
	{
		$weight = 0;

		foreach ($this->get_products() as $product) {
			$weight += $this->ci->weight->convert($product['weight'], $product['weight_class_id'], $this->ci->config->item('weight_class_id'));
		}

		return $weight;
	}
	
	/**
	 * Get sub total
	 * 
	 * @access public
	 * @return float
	 */
	public function get_sub_total()
	{
		$total = 0;

		foreach ($this->get_products() as $product) {
			$total += $product['total'];
		}

		return $total;
	}
	
	/**
	 * Get total
	 * 
	 * @access public
	 * @return float
	 */
	public function get_total()
	{
		$total = 0;

		foreach ($this->get_products() as $product) {
			$total += $product['price'] * $product['quantity'];
		}

		return $total;
	}
	
	/**
	 * Count products
	 * 
	 * @access public
	 * @return int
	 */
	public function count_products()
	{
		$product_total = 0;

		foreach ($this->get_products() as $product) {
			$product_total += $product['quantity'];
		}

		return $product_total;
	}
	
	/**
	 * Is has product?
	 * 
	 * @access public
	 * @return int
	 */
	public function has_products()
	{
		return (bool)count($this->get_products());
	}
	
	/**
	 * Is has stock?
	 * 
	 * @access public
	 * @return int
	 */
	public function has_stock()
	{
		// sementara
		return true;
		$stock = true;

		foreach ($this->get_products() as $product) {
			if ( ! $product['stock']) {
				$stock = false;
				break;
			}
		}

		return $stock;
	}
	
	/**
	 * Is shippable product(s)?
	 * 
	 * @access public
	 * @return int
	 */
	public function has_shipping()
	{
		$shipping = false;

		foreach ($this->get_products() as $product) {
			if ((bool)$product['shipping']) {
				$shipping = true;
				break;
			}
		}

		return $shipping;
	}
	
	/**
	 * Check if has tax
	 * 
	 * @access public
	 * @return bool
	 */
	public function has_tax()
	{
		$taxed = false;

		foreach ($this->get_products() as $product) {
			if ($product['tax_value']) {
				$taxed = true;
				break;
			}
		}

		return $taxed;
	}
}