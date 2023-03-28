<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Article_model extends MY_Model
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
	 * Get unique code
	 * 
	 * @access public
	 * @param int $product_id
	 * @return int
	 */
	public function get_unique_code($product_id)
	{
		$result = $this->db
		->select('unique_code')
		->where('product_id', (int)$product_id);
		
		$result = $this->db->get('product')->row_array();
		
		return ($result) ? $result['unique_code'] : '';
	}

	/**
	 * Get last article
	 * 
	 * @access public
	 * @param int $product_id
	 * @return int
	 */
	public function get_last_article($product_id)
	{
		$result = $this->db
		->select('product_code')
		->where('product_id', (int)$product_id)
		->order_by('id', 'desc')
		->limit(1);
		
		$result = $this->db->get('article')->row_array();
		
		return ($result) ? $result['product_code'] : '';
	}

	/**
	 * Get list of products
	 * 
	 * @access public
	 * @return array
	 */
	public function get_products()
	{
		$this->db->select('product_id, name');
		$this->db->from('product');
		$this->db->order_by('name', 'asc');
		
		return $this->db->get()->result_array();
	}

	/**
	 * Get article detail
	 * 
	 * @access public
	 * @param int $article_id
	 * @return array
	 */
	public function get_article_detail($article_id = null)
	{
		$article_detail = array();
		
		$article_query = $this->db
		->select('product_code, wf.name as product_name, it.id as article_id, wr.name as warehouse_name')
		->join('product wf', 'wf.product_id = it.product_id', 'left')
		->join('warehouse wr', 'wr.warehouse_id = it.warehouse_id', 'left')
		->from('article it')
		->where('it.id', (int)$article_id)
		->get()
		->row_array();
		
		if ($article_query) {	
			$article = $article_query;
			
			$article['details'] = $this->db
			->select('p.inventory_trans_id, description, date_added', false)
			->from('article_history i')
			->join('inventory_trans p', 'p.inventory_trans_id = i.inventory_trans_id', 'left')
			->where('i.article_id', (int)$article_id)
			->order_by('date_added, id', 'desc')
			->get()
			->result_array();
		}
		
		return $article;
	}

	/**
	 * Get article with product code and warehouse
	 * 
	 * @access public
	 * @param int $product_id
	 * @param int $warehouse_id
	 * @return array
	 */
	public function get_article_select($product_id = null, $warehouse_id = null)
	{
		$article_detail = array();
		
		$articles = $this->db
		->select('product_code, id')
		->from('article it')
		->where('it.product_id', (int)$product_id)
		->where('it.warehouse_id', (int)$warehouse_id)
		->where('it.order_id', 0)
		->order_by('product_code','asc')
		->get()
		->result_array();
		
		return $articles;
	}

	/**
	 * update article
	 * 
	 * @access public
	 * @param int $article_id
	 * @param int $warehouse
	 * @return array
	 */
	public function update_article($article_id, $warehouse)
	{
		$this->db->where('id', (int)$article_id);
		$this->db->set('warehouse_id', $warehouse);
		$this->db->update('article');
	}

	/**
	 * Get quantity article for sale
	 * 
	 * @access public
	 * @param int $product_id
	 * @return array
	 */
	public function get_qty_article($product_id = null)
	{
		$qty = $this->db
		->select('count(id) as qty')
		->from('article it')
		->where('it.product_id', (int)$product_id)
		->where('it.warehouse_id', 2)
		->where('it.order_id', 0)
		->get()
		->row_array();
		
		return $qty;
	}

	/**
	 * Get articles autocomplete
	 * 
	 * @access public
	 * @param array $data
	 * @return array
	 */
	public function get_articles_autocomplete($data = array())
	{
		$this->db->from('article');
		
		if ( ! empty($data['filter_name'])) {
			$this->db->like('product_code', $data['filter_name'], 'after');
		}

		$this->db->where_not_in('warehouse_id', array(0,2));
		
		$this->db->group_by('id');

		$this->db->order_by('product_code', 'asc');

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) $data['start'] = 0;
			if ($data['limit'] < 1) $data['limit'] = 20;
			
			$this->db->limit((int)$data['limit'], (int)$data['start']);
		}

		return $this->db->get()->result_array();
	}

	/**
	 * Get article warehouse
	 * 
	 * @access public
	 * @param int $article_id
	 * @return int
	 */
	public function get_article_warehouse($article_id)
	{
		$result = $this->db
		->select('warehouse_id')
		->where('id', (int)$article_id)
		->order_by('id', 'desc')
		->limit(1);
		
		$result = $this->db->get('article')->row_array();
		
		return ($result) ? $result['warehouse_id'] : '';
	}

	/**
	 * Get article with product code
	 * 
	 * @access public
	 * @param int $product_code
	 * @return int
	 */
	public function get_article_product_code($product_code)
	{
		$result = $this->db
		->select('warehouse_id, id')
		->where('product_code', $product_code)
		->order_by('id', 'desc')
		->limit(1);
		
		$result = $this->db->get('article')->row_array();
		
		return ($result) ? $result: array();
	}

	/**
	 * Get product code
	 * 
	 * @access public
	 * @param int $article_id
	 * @return int
	 */
	public function get_product_code($article_id)
	{
		$result = $this->db
		->select('product_code')
		->where('id', (int)$article_id)
		->order_by('id', 'desc')
		->limit(1);
		
		$result = $this->db->get('article')->row_array();
		
		return ($result) ? $result['product_code'] : '';
	}

	/**
	 * Get quantity article all
	 * 
	 * @access public
	 * @param int $product_id
	 * @return array
	 */
	public function get_qty_article_all($product_id = null)
	{
		$qty = $this->db
		->select('count(id) as qty')
		->from('article it')
		->where('it.product_id', (int)$product_id)
		->get()
		->row_array();
		
		return $qty['qty'];
	}

	/**
	 * Get quantity article all array
	 * 
	 * @access public
	 * @param int $product_id
	 * @return array
	 */
	public function get_qty_article_all_array($product_id = null)
	{
		$qty = $this->db
		->select('count(id) as qty')
		->from('article it')
		->where_in('it.product_id', $product_id)
		->get()
		->row_array();
		
		return $qty['qty'];
	}

	/**
	 * Get article with product code and warehouse for order
	 * 
	 * @access public
	 * @param int $product_id
	 * @param int $warehouse_id
	 * @return array
	 */
	public function get_article_order_select($product_id = null, $warehouse_id = null, $quantity)
	{
		$article_detail = array();
		
		$articles = $this->db
		->select('product_code, id')
		->from('article it')
		->where('it.product_id', (int)$product_id)
		->where('it.warehouse_id', (int)$warehouse_id)
		->where('it.order_id', 0)
		->order_by('product_code','asc')
		->limit($quantity)
		->get()
		->result_array();
		
		return $articles;
	}

	/**
	 * update article order
	 * 
	 * @access public
	 * @param int $article_id
	 * @param int $warehouse
	 * @return array
	 */
	public function update_article_order($article_id, $order_id)
	{
		$this->db->where('id', (int)$article_id);
		$this->db->set('order_id', (int)$order_id);
		$this->db->update('article');
	}

	/**
	 * Get article for print
	 * 
	 * @access public
	 * @param int $article_id
	 * @return array
	 */
	public function get_article_print_all($article_id = null)
	{
		$articles = $this->db
		->select('product_code, wf.name as product_name, it.id as article_id, wr.name as warehouse_name, invoice_no')
		->join('product wf', 'wf.product_id = it.product_id', 'left')
		->join('warehouse wr', 'wr.warehouse_id = it.warehouse_id', 'left')
		->join('order or', 'or.order_id = it.order_id', 'left')
		->from('article it')
		->where('it.id', (int)$article_id)
		->order_by('it.product_code', 'asc')
		->get()
		->result_array();
		
		return $articles;
	}

	/**
	 * Get quantity article all array
	 * 
	 * @access public
	 * @param array $article_id
	 * @return array
	 */
	public function get_article_print_all_array($article_id = null)
	{
		$articles = $this->db
		->select('product_code, wf.name as product_name, it.id as article_id, wr.name as warehouse_name, invoice_no')
		->join('product wf', 'wf.product_id = it.product_id', 'left')
		->join('warehouse wr', 'wr.warehouse_id = it.warehouse_id', 'left')
		->join('order or', 'or.order_id = it.order_id', 'left')
		->from('article it')
		->where_in('it.id', $article_id)
		->order_by('it.product_code', 'asc')
		->get()
		->result_array();
		
		return $articles;
	}
}