<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_Model extends MY_Model
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
	 * Get path
	 * 
	 * @access private
	 * @param int $id
	 * @return array
	 */
	private function _get_path($id = null)
	{
		return $this->db
		->where('category_id', (int)$id)
		->order_by('level', 'asc')
		->get('category_path')
		->result_array();
	}
	
	public function tree_view($parent_id)
	{
		$data = array();
		
		$categories = $this->db
		->select('name, category_id, parent_id, sort_order, active')
		->where('parent_id', $parent_id)
		->get('category')
		->result_array();
			
		foreach ($categories as $category) {
			$data[] = [
				'category_id' => $category['category_id'],
				'text' => $category['name'],
				'href' => '#category-'.$category['category_id'],
				'tags' => array('<span onclick=\'editForm('.$category['category_id'].')\'>Edit</span>', '<span onclick=\'addForm('.$category['category_id'].')\'>Tambah</span>'),
				'nodes' => $this->tree_view($category['category_id'])
			];
		}
		
		return $data;
	}
	
	/**
	 * Insert
	 * 
	 * @access public
	 * @param array $data
	 * @return void
	 */
	public function insert($data = array())
	{
		$data['slug'] = $this->validate_slug(strtolower(url_title($data['name'])));
		$category_id = parent::insert($data);
		$level = 0;
		$paths = [];
		
		foreach ($this->_get_path($data['parent_id']) as $result) {
			$paths[] = [
				'category_id' => (int)$category_id,
				'path_id' => (int)$result['path_id'],
				'level' => (int)$level
			];
			
			$level++;
		}
		
		if ($paths) {
			$this->db->insert_batch('category_path', $paths);
		}
		
		$this->db->set([
			'category_id' => (int)$category_id,
			'path_id' => (int)$category_id,
			'level' => (int)$level
		])->insert('category_path');
	}
	
	/**
	 * Update
	 * 
	 * @access public
	 * @param int $category_id
	 * @param array $data
	 * @return void
	 */
	public function update($category_id = null, $data = array())
	{
		$data['slug'] = $this->validate_slug(strtolower(url_title($data['name'])), $category_id);
		
		parent::update($category_id, $data);
		
		$results = $this->db
		->where('path_id', (int)$category_id)
		->order_by('level', 'asc')
		->get('category_path')
		->result_array();
				
		if ($results) {
			foreach ($results as $category_path) {
				$this->db
				->where('category_id', (int)$category_path['category_id'])
				->where('level <', (int)$category_path['level'])
				->delete('category_path');
				
				$path = [];
				
				foreach ($this->_get_path($data['parent_id']) as $result) {
					$path[] = $result['path_id'];
				}
				
				foreach ($this->_get_path($category_path['category_id']) as $result) {
					$path[] = $result['path_id'];
				}

				$level = 0;

				foreach ($path as $path_id) {
					$this->db->replace('category_path' , [
						'category_id' => (int)$category_path['category_id'],
						'path_id' => (int)$path_id,
						'level' => (int)$level
					]);
					
					$level++;
				}
			}
		} else {
			$this->db
			->where('category_id', (int)$category_id)
			->delete('category_path');
			
			$level = 0;
			$paths = [];
			
			foreach ($this->_get_path($data['parent_id']) as $result) {
				$paths[] = array(
					'category_id' => (int)$category_id,
					'path_id' => (int)$result['path_id'],
					'level' => (int)$level
				);
				
				$level++;
			}
			
			if ($paths) {
				$this->db->insert_batch('category_path', $paths);
			}
			
			$this->db->replace('category_path' , [
				'category_id' => (int)$category_id,
				'path_id' => (int)$category_id,
				'level' => (int)$level
			]);
		}
	}
	
	/**
	 * Get categories
	 * 
	 * @access public
	 * @param array $data Parameters
	 * @return array
	 */
	public function get_categories($data = array())
	{
		$this->db->select('cp.category_id as category_id, group_concat(c1.slug order by cp.level separator "/") as path, group_concat(c1.name order by cp.level separator " &raquo; ") as name, c.parent_id, c.sort_order, c.active', false);
		$this->db->from('category_path cp');
		$this->db->join('category c', 'cp.path_id = c.category_id', 'left');
		$this->db->join('category c1', 'c.category_id = c1.category_id', 'left');
		$this->db->join('category c2', 'cp.category_id = c2.category_id', 'left');
		
		if ( ! empty($data['filter_name']))
		{
			$this->db->like('c2.name', $data['filter_name'], 'after');
		}
		
		if ( ! empty($data['filter_description']))
		{
			$this->db->or_like('c2.description', $data['filter_description'], 'after');
		}
		
		$this->db->group_by('cp.category_id');
		
		$sort_data = array(
			'name',
			'sort_order',
			'active'
		);
		
		$sort = (isset($data['sort']) && isset($data['sort'])) ? $data['sort'] : 'name';
		$order = (isset($data['order']) && ($data['order'] == 'desc')) ? 'desc' : 'asc';
		
		$this->db->order_by($sort, $order);
		
		if (isset($data['start']) or isset($data['limit']))
		{
			if ($data['start'] < 0) $data['start'] = 0;
			if ($data['limit'] < 1) $data['limit'] = 20;
			
			$this->db->limit((int)$data['limit'], (int)$data['start']);
		}
		
		return $this->db->get()->result_array();
	}
	
	public function get_subcategories($parent_id = 0, $limit = false)
	{
		$this->db
		->select('category_id, name, slug, menu_image, top')
		->from('category')
		->where('parent_id', (int)$parent_id)
		->where('active', 1);
		
		if ($limit) {
			$this->db->limit($limit);
		}
		
		return $this->db->order_by('sort_order', 'asc')
		->get()
		->result_array();
	}
	
	/**
	 * Get category filters
	 * 
	 * @access public
	 * @param int $category_id
	 * @return array
	 */
	public function get_category_filters($category_id)
	{
		return $this->db
		->select('f.name, f.filter_id, fg.name as group_name')
		->from('category_filter cf')
		->join('filter f', 'f.filter_id = cf.filter_id', 'left')
		->join('filter_group fg', 'fg.filter_group_id = f.filter_group_id', 'left')
		->where('cf.category_id', (int)$category_id)
		->order_by('f.sort_order', 'asc')
		->get()
		->result_array();
	}
	
	/**
	 * Count categories
	 * 
	 * @access public
	 * @return void
	 */
	public function count_categories()
	{
		return $this->db
		->select('category_id')
		->count_all_results('category');
	}
	
	/**
	 * Get category
	 * 
	 * @access public
	 * @param int $key
	 * @return array
	 */
	public function get_category($key)
	{
		$this->db
		->select("distinct *, (select group_concat(c1.name order by level separator ' &raquo; ') from ".$this->db->dbprefix('category_path')." cp left join ".$this->db->dbprefix('category')." c1 on (cp.path_id = c1.category_id and cp.category_id != cp.path_id) where cp.category_id = c.category_id group by cp.category_id) as path, (select group_concat(c1.category_id order by level separator '-') from ".$this->db->dbprefix('category_path')." cp left join ".$this->db->dbprefix('category')." c1 on (cp.path_id = c1.category_id and cp.category_id != cp.path_id) where cp.category_id = c.category_id group by cp.category_id) as path_id", false)
		->from('category c');
		
		if (is_numeric($key)) {
			$this->db->where('c.category_id', (int)$key);
		} else {
			$this->db->where('c.slug', $key);
		}
		
		return $this->db->get()->row_array();
	}
	
	/**
	 * Repair categories
	 * 
	 * @access public
	 * @param int $parent_id
	 * @return void
	 */
	public function repair_categories($parent_id = 0)
	{
		foreach ($this->db
		->where('parent_id', (int)$parent_id)
		->get('category')
		->result_array() as $category)
		{
			$this->db
			->where('category_id', (int)$category['category_id'])
			->delete('category_path');
			
			$level = 0;
			$paths = [];
			
			foreach ($this->_get_path($parent_id) as $result) {
				$paths[] = array(
					'category_id' => (int)$category['category_id'],
					'path_id' => (int)$result['path_id'],
					'level' => (int)$level
				);
				
				$level++;
			}
			
			if ($paths) {
				$this->db->insert_batch('category_path', $paths);
			}
			
			$this->db->replace('category_path' , [
				'category_id' => (int)$category['category_id'],
				'path_id' => (int)$category['category_id'],
				'level' => (int)$level
			]);

			$this->repair_categories($category['category_id']);
		}
	}
	
	/**
	 * Delete single category
	 * 
	 * @access public
	 * @param int $category_id
	 * @return void
	 */
	public function delete($category_id)
	{
		if (is_array($category_id)) {
			$this->db->where_in('category_id', (array)$category_id)->delete('category_path');
		
			foreach ($this->db
			->where_in('path_id', (array)$category_id)
			->get('category_path')
			->result_array() as $result) {
				$this->delete($result['category_id']);
			}
			
			$this->db->where_in('category_id', (array)$category_id);
		} else {
			$this->db->where('category_id', (int)$category_id)->delete('category_path');
		
			foreach ($this->db
			->where('path_id', (int)$category_id)
			->get('category_path')
			->result_array() as $result)
			{
				$this->delete($result['category_id']);
			}
			
			$this->db->where('category_id', (int)$category_id);
		}
		
		$this->db->delete(array(
			'category', 
			'product_category'
		));
	}
	
	/**
	 * Count products inside category
	 * 
	 * @access public
	 * @param int $category_id
	 * @return int
	 */
	public function count_products($category_id)
	{
		return $this->db
		->select('product_id')
		->where('category_id', (int)$category_id)
		->count_all_results('product_to_category');
	}
	
	/**
	 * Validate slug
	 * 
	 * @access public
	 * @param string $slug
	 * @param int $category_id
	 * @param int $counter
	 * @return string
	 */
	public function validate_slug($slug = '', $category_id = null, $counter = null)
	{
		if ($category_id) {
			$this->db->where('category_id !=', $category_id);
		}
		
		$count = $this->db
		->select('slug')
		->from('category')
		->where('slug', $slug.($counter ? '-'.$counter : ''))
		->count_all_results();
		
		if ($count > 0) {
			if( ! $counter) {
				$counter = 1;
			} else {
				$counter++;
			}
		
			return $this->validate_slug($slug, $category_id, $counter);
		} else {
			return $slug.($counter ? '-'.$counter : '');
		}
	}
	
	public function copy_products()
	{
		$products = [];
	}

	/**
	 * Get quantity product all
	 * 
	 * @access public
	 * @param int $category_id
	 * @return array
	 */
	public function get_qty_product_all($category_id = null)
	{
		$qty = $this->db
		->select('count(it.category_id) as qty')
		->from('product it')
		->where('it.category_id', (int)$category_id)
		->get()
		->row_array();
		
		return $qty['qty'];
	}

	/**
	 * Get quantity product all array
	 * 
	 * @access public
	 * @param int $category_id
	 * @return array
	 */
	public function get_qty_product_all_array($category_id = null)
	{
		$qty = $this->db
		->select('count(it.category_id) as qty')
		->from('product it')
		->where_in('it.category_id', $category_id)
		->get()
		->row_array();
		
		return $qty['qty'];
	}

	/**
	 * Get first category for shop
	 * 
	 * @access public
	 * @return array
	 */
	public function get_first_category() 
	{
		$category = $this->db
		->select('category_id, name, slug, menu_image, top')
		->from('category')
		->where('active', 1)
		->limit(1)
		->get()
		->row_array();

		return $category;
	}
}