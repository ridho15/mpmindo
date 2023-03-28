<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_Model extends MY_Model
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
	
	public function update_view($product_id)
	{
		$this->db->where('product_id', (int)$product_id);
		$this->db->set('viewed', 'viewed+1', false);
		$this->db->update('product');
	}
	
	/**
	 * Get a product
	 * 
	 * @access public
	 * @param string $key Can be product id or slug
	 * @return array
	 */
	public function get_product($key)
	{
		// $product = $this->db
		// ->select("DISTINCT *, p.name AS name, p.slug, p.description, 
		// 	(SELECT pi.image FROM product_image pi WHERE pi.product_id = p.product_id LIMIT 1) as image, 
		// 	(SELECT price FROM product_discount pd WHERE pd.product_id = p.product_id AND pd.quantity = '1' AND ((pd.date_start = '0000-00-00' OR pd.date_start < NOW()) AND (pd.date_end = '0000-00-00' OR pd.date_end > NOW())) ORDER BY pd.priority ASC, pd.price ASC LIMIT 1) AS discount,
		// 	(SELECT ss.name FROM stock_status ss WHERE ss.stock_status_id = p.stock_status_id) AS stock_status,
		// 	(SELECT wc.unit FROM weight_class wc WHERE p.weight_class_id = wc.weight_class_id) AS weight_class,
		// 	(SELECT AVG(rating) AS total FROM review r1 WHERE r1.product_id = p.product_id AND r1.active = '1' GROUP BY r1.product_id) AS rating,
		// 	(SELECT COUNT(*) AS total FROM review r2 WHERE r2.product_id = p.product_id AND r2.active = '1' GROUP BY r2.product_id) AS reviews, 
		// 	(SELECT quantity FROM product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.quantity > '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.quantity DESC LIMIT 1) AS wholesaler, 
		// 	(SELECT SUM(quantity) FROM inventory i WHERE i.product_id = p.product_id AND i.warehouse_id = '".(int)$this->config->item('warehouse_id')."') AS quantity, p.sort_order", false)
		// ->from('product p')
		// ->where((is_numeric($key)) ? array('p.product_id' => (int)$key) : array('p.slug' => $key))
		// ->where('p.active', 1);
		$product = $this->db
		->select("DISTINCT *, p.name AS name, p.slug, p.description, 
			(SELECT pi.image FROM product_image pi WHERE pi.product_id = p.product_id LIMIT 1) as image, 
			(SELECT price FROM product_discount pd WHERE pd.product_id = p.product_id AND pd.quantity = '1' AND ((pd.date_start = '0000-00-00' OR pd.date_start < NOW()) AND (pd.date_end = '0000-00-00' OR pd.date_end > NOW())) ORDER BY pd.priority ASC, pd.price ASC LIMIT 1) AS discount,
			(SELECT ss.name FROM stock_status ss WHERE ss.stock_status_id = p.stock_status_id) AS stock_status,
			(SELECT wc.unit FROM weight_class wc WHERE p.weight_class_id = wc.weight_class_id) AS weight_class,
			(SELECT AVG(rating) AS total FROM review r1 WHERE r1.product_id = p.product_id AND r1.active = '1' GROUP BY r1.product_id) AS rating,
			(SELECT COUNT(*) AS total FROM review r2 WHERE r2.product_id = p.product_id AND r2.active = '1' GROUP BY r2.product_id) AS reviews, 
			(SELECT quantity FROM product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.quantity > '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.quantity DESC LIMIT 1) AS wholesaler, 
			(SELECT count(0) FROM article i WHERE i.product_id = p.product_id AND i.warehouse_id = '".(int)$this->config->item('warehouse_id')."') AS quantity, p.sort_order", false)
		->from('product p')
		->where((is_numeric($key)) ? array('p.product_id' => (int)$key) : array('p.slug' => $key))
		->where('p.active', 1);
		
		$product = $this->db
		->get()
		->row_array();
		
		if ($product) {
			$product['rating'] = round($product['rating']);
			$product['reviews'] = $product['reviews'] ? $product['reviews'] : 0;
			
			return $product;
		}
		
		return array();
	}
	
	public function get_products($data = array())
	{
		$sql = "SELECT p.product_id, p.slug, 
		(SELECT AVG(rating) AS total FROM review r1 WHERE r1.product_id = p.product_id AND r1.active = '1' GROUP BY r1.product_id) AS rating, 
		(SELECT SUM(quantity) FROM inventory i WHERE i.product_id = p.product_id AND i.warehouse_id = ".(int)$this->config->item('warehouse_id').") AS quantity, 
		(SELECT k.sort_order FROM category k WHERE k.category_id = p.category_id) AS category_order, 
		(SELECT price FROM product_discount pd WHERE pd.product_id = p.product_id AND pd.quantity = '1' AND ((pd.date_start = '0000-00-00' OR pd.date_start < NOW()) AND (pd.date_end = '0000-00-00' OR pd.date_end > NOW())) ORDER BY pd.priority ASC, pd.price ASC LIMIT 1) AS discount FROM category_path cp LEFT JOIN product p ON(cp.category_id = p.category_id) WHERE p.active = 1";

		if (!empty($data['category_id'])) {
			$sql .= " AND cp.path_id = '".(int)$data['category_id']."'";
		}
		
		if (!empty($data['showcase_id'])) {
			$sql .= " AND p.showcase_id = '".(int)$data['showcase_id']."'";
		}

		if (!empty($data['name']) || !empty($data['tag'])) {
			$sql .= " AND (";

			if (!empty($data['name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['name'])));

				foreach ($words as $word) {
					$implode[] = "p.name LIKE '%".$this->db->escape_str($word)."%'";
				}

				if ($implode) {
					$sql .= " ".implode(" AND ", $implode)."";
				}

				if (!empty($data['description'])) {
					$sql .= " OR p.description LIKE '%".$this->db->escape_str($data['name'])."%'";
				}
			}

			if (!empty($data['name']) && !empty($data['tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['tag'])) {
				$sql .= "p.tag LIKE '%".$this->db->escape_str($data['tag'])."%'";
			}

			if (!empty($data['name'])) {
				$sql .= " OR LCASE(p.sku) = '".$this->db->escape_str(strtolower($data['name']))."'";
			}	

			$sql .= ")";
		}

		$sql .= " GROUP BY p.product_id";

		$sort_data = array(
			'name' => 'p.name',
			'sku' => 'p.sku',
			'price' => 'p.price',
			'rating' => 'rating',
			'sort_order' => 'p.sort_order',
			'date_added' => 'p.date_added'
		);
		
		//$sql .= " ORDER BY quantity DESC,";	
		$sql .= " ORDER BY category_order ASC,";	

		if (isset($data['sort']) && isset($sort_data[$data['sort']])) {
			if ($sort_data[$data['sort']] == 'p.name' || $sort_data[$data['sort']] == 'p.sku') {
				$sql .= " LCASE(".$sort_data[$data['sort']].")";
			} elseif ($sort_data[$data['sort']] == 'p.price') {
				$sql .= " (CASE WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			} else {
				$sql .= " ".$sort_data[$data['sort']];
			}
		} else {
			$sql .= " p.sort_order";	
		}

		if (isset($data['order']) && (strtoupper($data['order']) == 'DESC')) {
			$sql .= " DESC, LCASE(p.name) DESC";
		} else {
			$sql .= " ASC, LCASE(p.name) ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	

			$sql .= " LIMIT ".(int)$data['start'].",".(int)$data['limit'];
		}

		$product_data = array();

		foreach ($this->db->query($sql)->result_array() as $result) {
			$product_data[$result['product_id']] = $this->get_product($result['product_id']);
		}

		return $product_data;
	}
	
	public function count_products($data = array())
	{
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM category_path cp LEFT JOIN product p ON(cp.category_id = p.category_id) WHERE p.active = 1"; 

		if ( ! empty($data['category_id'])) {
			$sql .= " AND cp.path_id = '".(int)$data['category_id']."'";
		}
		
		if (!empty($data['showcase_id'])) {
			$sql .= " AND p.showcase_id = '".(int)$data['showcase_id']."'";
		}

		if ( ! empty($data['name']) || !empty($data['tag'])) {
			$sql .= " AND (";

			if ( ! empty($data['name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['name'])));

				foreach ($words as $word) {
					$implode[] = "p.name LIKE '%".$this->db->escape_str($word)."%'";
				}

				if ($implode) {
					$sql .= " ".implode(" AND ", $implode)."";
				}

				if ( ! empty($data['description'])) {
					$sql .= " OR p.description LIKE '%".$this->db->escape_str($data['name'])."%'";
				}
			}

			if ( ! empty($data['name']) && !empty($data['tag'])) {
				$sql .= " OR ";
			}

			if ( ! empty($data['tag'])) {
				$sql .= "p.tag LIKE '%".$this->db->escape_str(strtolower($data['tag']))."%'";
			}

			if ( ! empty($data['name'])) {
				$sql .= " OR LCASE(p.sku) = '".$this->db->escape_str(strtolower($data['name']))."'";
			}

			$sql .= ")";
		}

		$query = $this->db->query($sql)->row_array();

		return $query['total'];
	}
	
	/**
	 * Get products
	 * 
	 * @access public
	 * @param array $data
	 * @return array
	 */
	public function get_products_autocomplete($data = array())
	{
		$this->db->from('product p');
		
		if ( ! empty($data['filter_name'])) {
			$this->db->like('p.name', $data['filter_name'], 'after');
		}
		
		if ( ! empty($data['filter_description'])) {
			$this->db->or_like('p.description', $data['filter_description'], 'after');
		}

		if ( ! empty($data['filter_price'])) {
			$this->db->or_like('p.price', $data['filter_price'], 'after');
		}

		if (isset($data['filter_active']) && ! is_null($data['filter_active'])) {
			$this->db->where('p.active', $data['filter_active']);
		}
		
		$this->db->group_by('p.product_id');

		$sort_data = array(
			'name' => 'p.name',
			'price' => 'p.price',
			'active' => 'p.active',
			'sort_order' => 'p.sort_order'
		);
		
		$sort = (isset($data['sort']) && isset($sort_data[$data['sort']])) ? $sort_data[$data['sort']] : 'p.name';
		$order = (isset($data['order']) && ($data['order'] == 'desc')) ? 'desc' : 'asc';

		$this->db->order_by($sort, $order);

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) $data['start'] = 0;
			if ($data['limit'] < 1) $data['limit'] = 20;
			
			$this->db->limit((int)$data['limit'], (int)$data['start']);
		}

		return $this->db->get()->result_array();
	}
	
	/**
	 * Create product
	 * 
	 * @access public
	 * @param array $data
	 * @return void
	 */
	public function create_product($data)
	{
		$data['slug'] = $this->validate_slug(strtolower(url_title($data['name'])));
		$data['meta_title'] = 'Jual '.$data['name'];

		if (empty($data['meta_description'])) {
			$data['meta_description'] = substr($data['description'], 0, strrpos($data['description'], '.'));	
		}
		
		$product_id = $this->insert($data);
		
		if (isset($data['product_discount'])) $this->set_product_discounts($product_id, $data['product_discount']);
		if (isset($data['product_image'])) $this->set_product_images($product_id, $data['product_image']);
		if (isset($data['product_category'])) $this->set_product_categories($product_id, $data['product_category']);
		if (isset($data['product_related'])) $this->set_product_related($product_id, $data['product_related']);
		
		return $product_id;
	}
	
	/**
	 * Toggle Active product
	 * 
	 * @access public
	 * @param int $product_id
	 * @param array $data
	 * @return void
	 */
	public function ActiveToggle($product_id, $active)
	{
		$data['active'] = $active;
		$this->update($product_id, $data);
	}
	
	/**
	 * Edit product
	 * 
	 * @access public
	 * @param int $product_id
	 * @param array $data
	 * @return void
	 */
	public function edit_product($product_id, $data)
	{
		$data['slug'] = $this->validate_slug(strtolower(url_title($data['name'])), $product_id);
		$data['meta_title'] = 'Jual '.$data['name'];
		
		if (empty($data['meta_description'])) {
			$data['meta_description'] = substr($data['description'], 0, strrpos($data['description'], '.'));	
		}
		
		$this->update($product_id, $data);
		
		if (isset($data['product_discount'])) $this->set_product_discounts($product_id, $data['product_discount']);
		if (isset($data['product_image'])) $this->set_product_images($product_id, $data['product_image']);
		if (isset($data['product_category'])) $this->set_product_categories($product_id, $data['product_category']);
		if (isset($data['product_related'])) $this->set_product_related($product_id, $data['product_related']);
	}
	
	/**
	 * Validate slug
	 * 
	 * @access public
	 * @param string $slug
	 * @param int $product_id
	 * @param int $counter
	 * @return string
	 */
	public function validate_slug($slug = '', $product_id = null, $counter = null)
	{
		if ($product_id) {
			$this->db->where('product_id !=', $product_id);
		}
		
		$count = $this->db
		->select('slug')
		->from('product')
		->where('slug', $slug.($counter ? '-'.$counter : ''))
		->count_all_results();
		
		if ($count > 0) {
			if( ! $counter) {
				$counter = 1;
			} else {
				$counter++;
			}
		
			return $this->validate_slug($slug, $product_id, $counter);
		} else {
			return $slug.($counter ? '-'.$counter : '');
		}
	}
	
	/**
	 * Set product discounts
	 * 
	 * @access public
	 * @param int $product_id
	 * @param array $discounts
	 * @return void
	 */
	public function set_product_discounts($product_id, $discounts = array())
	{
		$this->db
		->where('product_id', (int)$product_id)
		->delete('product_discount');
		
		foreach ($discounts as $discount) {
			$this->db
			->set(array(
				'product_id' => (int)$product_id,
				'customer_group_id'	=> 0,
				'quantity' => (int)$discount['quantity'],
				'priority' => (int)$discount['priority'],
				'price' => (float)$discount['price'],
				'date_start' => ($discount['date_start'] != '') ? date('Y-m-d', strtotime($discount['date_start'])) : '0000-00-00', 
				'date_end' => ($discount['date_end'] != '') ? date('Y-m-d', strtotime($discount['date_end'])) : '0000-00-00'
			))->insert('product_discount');
		}
	}
	
	/**
	 * Set product images
	 * 
	 * @access public
	 * @param mixed $product_id
	 * @param array $images
	 * @return void
	 */
	public function set_product_images($product_id, $images = array())
	{
		$this->db
		->where('product_id', (int)$product_id)
		->delete('product_image');
		
		$product = $this->db
		->select('name')
		->where('product_id', (int)$product_id)
		->get('product')
		->row_array();
		
		$product_name = $product ? $product['name'] : false;
		$product_image = array();
		$count = 1;
		
		foreach ($images as $image) {
			$product_image[] = array(
				'product_id' => (int)$product_id,
				'alt' => (empty($image['alt']) && $product_name) ? strtolower($product_name.' '.$count) : '',
				'title' => (empty($image['title']) && $product_name) ? $product_name.' '.$count : '',
				'image' => html_entity_decode($image['image'], ENT_QUOTES, 'UTF-8'),
				'sort_order' => (empty($image['sort_order'])) ? $count : 0
			);
			
			$count++;
		}
		
		if ($product_image) {
			$this->db->insert_batch('product_image', $product_image);
		}
	}
	
	/**
	 * Set product categories
	 * 
	 * @access public
	 * @param int $product_id
	 * @param array $categories
	 * @return void
	 */
	public function set_product_categories($product_id, $categories = array())
	{
		$this->db
		->where('product_id', (int)$product_id)
		->delete('product_category');
		
		foreach ($categories as $category_id) {
			$this->db
			->set(array(
				'product_id' => (int)$product_id,
				'category_id' => (int)$category_id
			))->insert('product_category');
		}
	}
	
	/**
	 * Set product related
	 * 
	 * @access public
	 * @param int $product_id
	 * @param array $categories
	 * @return void
	 */
	public function set_product_related($product_id, $relates = array())
	{
		$this->db
		->where('product_id', (int)$product_id)
		->delete('product_related');
		
		foreach ($relates as $related_id) {
			$this->db
			->set(array(
				'product_id' => (int)$product_id,
				'related_id' => (int)$related_id
			))->insert('product_related');
		}
	}
	
	/**
	 * Get product discounts
	 * 
	 * @access public
	 * @param int $product_id
	 * @return array
	 */
	public function get_product_discounts($product_id)
	{
		return $this->db
		->where('product_id', (int)$product_id)
		->order_by('quantity', 'asc')
		->order_by('priority', 'asc')
		->order_by('price', 'asc')
		->get('product_discount')
		->result_array();
	}
	
	/**
	 * Get product images
	 * 
	 * @access public
	 * @param int $product_id
	 * @return array
	 */
	public function get_product_images($product_id)
	{
		return $this->db
		->where('product_id', (int)$product_id)
		->get('product_image')
		->result_array();
	}
	
	/**
	 * Get product categories
	 * 
	 * @access public
	 * @param int $product_id
	 * @return array
	 */
	public function get_product_categories($product_id)
	{
		$categories = array();

		foreach ($this->db
		->select('category_id')
		->where('product_id', (int)$product_id)
		->get('product_category')
		->result_array() as $result) {
			$categories[] = $result['category_id'];
		}

		return $categories;
	}
	
	/**
	 * Get related products
	 * 
	 * @access public
	 * @param int $product_id
	 * @return array
	 */
	public function get_product_related($product_id)
	{
		return $this->db
		->select("DISTINCT *, p.name AS name, p.slug, p.description, 
			(SELECT pi.image FROM product_image pi WHERE pi.product_id = p.product_id LIMIT 1) as image, 
			(SELECT price FROM product_discount pd WHERE pd.product_id = p.product_id AND pd.quantity = '1' AND ((pd.date_start = '0000-00-00' OR pd.date_start < NOW()) AND (pd.date_end = '0000-00-00' OR pd.date_end > NOW())) ORDER BY pd.priority ASC, pd.price ASC LIMIT 1) AS discount,
			(SELECT ss.name FROM stock_status ss WHERE ss.stock_status_id = p.stock_status_id) AS stock_status,
			(SELECT wc.unit FROM weight_class wc WHERE p.weight_class_id = wc.weight_class_id) AS weight_class,
			(SELECT AVG(rating) AS total FROM review r1 WHERE r1.product_id = p.product_id AND r1.active = '1' GROUP BY r1.product_id) AS rating,
			(SELECT COUNT(*) AS total FROM review r2 WHERE r2.product_id = p.product_id AND r2.active = '1' GROUP BY r2.product_id) AS reviews, 
			(SELECT SUM(quantity) FROM inventory i WHERE i.product_id = p.product_id AND i.warehouse_id = '".(int)$this->config->item('warehouse_id')."') AS quantity, p.sort_order", false)
		->from('product_related pr')
		->join('product p', 'p.product_id = pr.related_id', 'left')
		->where('pr.product_id', $product_id)
		->where('p.active', 1)
		->order_by('quantity', 'desc')
		->get()
		->result_array();
		// return $this->db
		// ->select("DISTINCT *, p.name AS name, p.slug, p.description, 
		// 	(SELECT pi.image FROM product_image pi WHERE pi.product_id = p.product_id LIMIT 1) as image, 
		// 	(SELECT price FROM product_discount pd WHERE pd.product_id = p.product_id AND pd.quantity = '1' AND ((pd.date_start = '0000-00-00' OR pd.date_start < NOW()) AND (pd.date_end = '0000-00-00' OR pd.date_end > NOW())) ORDER BY pd.priority ASC, pd.price ASC LIMIT 1) AS discount,
		// 	(SELECT ss.name FROM stock_status ss WHERE ss.stock_status_id = p.stock_status_id) AS stock_status,
		// 	(SELECT wc.unit FROM weight_class wc WHERE p.weight_class_id = wc.weight_class_id) AS weight_class,
		// 	(SELECT AVG(rating) AS total FROM review r1 WHERE r1.product_id = p.product_id AND r1.active = '1' GROUP BY r1.product_id) AS rating,
		// 	(SELECT COUNT(*) AS total FROM review r2 WHERE r2.product_id = p.product_id AND r2.active = '1' GROUP BY r2.product_id) AS reviews, 
		// 	(SELECT count(0) FROM article i WHERE i.product_id = p.product_id AND i.warehouse_id = '2') AS quantity, p.sort_order", false)
		// ->from('product_related pr')
		// ->join('product p', 'p.product_id = pr.related_id', 'left')
		// ->where('pr.product_id', $product_id)
		// ->where('p.active', 1)
		// ->order_by('quantity', 'desc')
		// ->get()
		// ->result_array();
	}
	
	/**
	 * Delete product
	 * 
	 * @access public
	 * @param int $product_id
	 * @return void
	 */
	public function delete($product_id)
	{
		if (is_array($product_id)) {
			$this->db->where_in('product_id', (array)$product_id);
		} else {
			$this->db->where('product_id', (int)$product_id);
		}
		
		$this->db->delete(array(
			'product', 
			'product_discount', 
			'product_image', 
			'product_category', 
			'product_related', 
			'comment',
			'review'
		));
	}
	
	public function get_product_specials($data = array())
	{			
		$this->db->select('p.product_id, p.name, p.category_id, p.slug, p.description, p.price, ps.price as discount, p.tax_value, p.tax_type, p.cod, (SELECT pi.image FROM product_image pi WHERE pi.product_id = p.product_id LIMIT 1) as image, (SELECT AVG(rating) FROM review r1 WHERE r1.product_id = ps.product_id AND r1.active = 1 GROUP BY r1.product_id) AS rating, (SELECT quantity FROM product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.quantity > 1 AND ((pd2.date_start = \'0000-00-00\' OR pd2.date_start < NOW()) AND (pd2.date_end = \'0000-00-00\' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.quantity DESC LIMIT 1) AS wholesaler, (SELECT SUM(quantity) FROM inventory i WHERE i.product_id = p.product_id AND i.warehouse_id = "'.(int)$this->config->item('warehouse_id').'") AS quantity', false)
		->from('product_discount ps')
		->join('product p', 'ps.product_id = p.product_id', 'left')
		->where('p.active', 1);
		
		$this->db
		->where('ps.quantity', 1)
		->where('(ps.date_start = \'0000-00-00\' || ps.date_start < NOW())', null, false)
		->where('(ps.date_end = \'0000-00-00\' || ps.date_end > NOW())', null, false)
		->group_by('ps.product_id')
		->order_by('quantity', 'desc');
		
		$sort_data = array(
			'p.name',
			'ps.price',
			'rating',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sort = $data['sort'];
		} else {
			$sort = 'p.sort_order';
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$order = 'desc';
		} else {
			$order = 'asc';
		}
		
		$this->db->order_by($sort, $order);

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}
			
			$this->db->limit($data['limit'], $data['start']);
		}
		
		return $this->db->get()->result_array();
	}
	
	public function count_product_specials()
	{
		$this->db
		->select('SELECT COUNT(DISTINCT ps.product_id) AS total')
		->join('product p', 'ps.product_id = p.product_id', 'left')
		->where('p.active', 1);
		
		return (int)$this->db
		->where('(ps.date_start = \'0000-00-00\' || ps.date_start < NOW())', null, false)
		->where('(ps.date_end = \'0000-00-00\' || ps.date_end > NOW())', null, false)
		->group_by('ps.product_id')
		->count_all_results('product_discount ps');
	}

	/**
	 * Get quantity product for sale
	 * 
	 * @access public
	 * @param int $product_id
	 * @return array
	 */
	public function get_qty_product($product_id = null)
	{
		$qty = $this->db
		->select('sum(quantity) as qty')
		->from('inventory it')
		->where('it.product_id', (int)$product_id)
		->where('it.warehouse_id !=', 0)
		->get()
		->row_array();
		
		return $qty;
	}

	public function get_product_by_slug($slug = null)
	{
		$sql = "SELECT p.product_id, p.slug, p.category_id, 
		(SELECT AVG(rating) AS total FROM review r1 WHERE r1.product_id = p.product_id AND r1.active = '1' GROUP BY r1.product_id) AS rating, 
		(SELECT SUM(quantity) FROM inventory i WHERE i.product_id = p.product_id AND i.warehouse_id = ".(int)$this->config->item('warehouse_id').") AS quantity, 
		(SELECT price FROM product_discount pd WHERE pd.product_id = p.product_id AND pd.quantity = '1' AND ((pd.date_start = '0000-00-00' OR pd.date_start < NOW()) AND (pd.date_end = '0000-00-00' OR pd.date_end > NOW())) ORDER BY pd.priority ASC, pd.price ASC LIMIT 1) AS discount FROM category_path cp LEFT JOIN product p ON(cp.category_id = p.category_id) WHERE p.active = 1";

		if($slug != null) {
			$sql .= " and p.slug = '".$slug."'";
		}

		$sql .= " GROUP BY p.product_id";

		$sql .= " ORDER BY quantity DESC";	

		$product_data = array();

		foreach ($this->db->query($sql)->result_array() as $result) {
			$product_data[$result['product_id']] = $this->get_product($result['product_id']);
		}

		return $product_data;
	}

	/**
	 * Get product view history
	 * 
	 * @access public
	 * @param int $product_id
	 * @param int $user_id
	 * @return array
	 */
	public function get_product_view_history($user_id,$product_id)
	{
		return $this->db
		->select("product_id", false)
		->from('product_view_user pr')
		->where('pr.user_id', $user_id)
		->where('pr.product_id != ', $product_id)
		->group_by("product_id")
		->order_by("id")
		->get()
		->result_array();
	}

	public function get_product_by_id($product_id = null)
	{
		$sql = "SELECT p.product_id, p.slug, p.category_id, 
		(SELECT AVG(rating) AS total FROM review r1 WHERE r1.product_id = p.product_id AND r1.active = '1' GROUP BY r1.product_id) AS rating, 
		(SELECT SUM(quantity) FROM inventory i WHERE i.product_id = p.product_id AND i.warehouse_id = ".(int)$this->config->item('warehouse_id').") AS quantity, 
		(SELECT price FROM product_discount pd WHERE pd.product_id = p.product_id AND pd.quantity = '1' AND ((pd.date_start = '0000-00-00' OR pd.date_start < NOW()) AND (pd.date_end = '0000-00-00' OR pd.date_end > NOW())) ORDER BY pd.priority ASC, pd.price ASC LIMIT 1) AS discount FROM category_path cp LEFT JOIN product p ON(cp.category_id = p.category_id) WHERE p.active = 1";

		if($product_id != null) {
			$sql .= " and p.product_id = '".$product_id."'";
		}

		$sql .= " GROUP BY p.product_id";

		$sql .= " ORDER BY quantity DESC";	

		$product_data = array();

		foreach ($this->db->query($sql)->result_array() as $result) {
			$product_data[$result['product_id']] = $this->get_product($result['product_id']);
		}

		return $product_data;
	}
}