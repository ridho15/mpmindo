<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Page_model extends MY_Model
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
	 * Get page menus
	 * 
	 * @access public
	 * @return array
	 */
	public function get_page_menus()
	{
		return $this->db
		->select('slug, title, footer_column')
		->where('active', 1)
		->order_by('sort_order', 'asc')
		->get('page')
		->result_array();
	}
	
	/**
	 * Get page by slug
	 * 
	 * @access public
	 * @param string $slug
	 * @return array
	 */
	public function get_page($slug = null)
	{
		return $this->db
		->where('slug', $slug)
		->where('active', 1)
		->get('page')
		->row_array();
	}
	
	/**
	 * Create new page
	 * 
	 * @access public
	 * @param array $data
	 * @return int | bool
	 */
	public function create($data = array())
	{
		$data['slug'] = $this->validate_slug(strtolower(url_title($data['title'])));
		return $this->insert($data);
	}
	
	/**
	 * Update existing page
	 * 
	 * @access public
	 * @param array $data
	 * @return int | bool
	 */
	public function edit($page_id, $data = array())
	{
		$data['slug'] = $this->validate_slug(strtolower(url_title($data['title'])), $page_id);
		return $this->update($page_id, $data);
	}
	
	/**
	 * Delete page
	 * 
	 * @access public
	 * @param int $page_id
	 * @return void
	 */
	public function delete($page_id = null)
	{
		$this->db
		->where('page_id', $page_id)
		->delete('page');
	}
	
	/**
	 * Validate slug
	 * 
	 * @access public
	 * @param string $slug
	 * @param int $page_id
	 * @param int $counter
	 * @return string
	 */
	public function validate_slug($slug = '', $page_id = null, $counter = null)
	{
		if ($page_id) {
			$this->db->where('page_id !=', $page_id);
		}
		
		$count = $this->db
		->select('slug')
		->from('page')
		->where('slug', $slug.($counter ? '-'.$counter : ''))
		->count_all_results();
		
		if ($count > 0) {
			if( ! $counter) {
				$counter = 1;
			} else {
				$counter++;
			}
		
			return $this->validate_slug($slug, $page_id, $counter);
		} else {
			return $slug.($counter ? '-'.$counter : '');
		}
	}
}