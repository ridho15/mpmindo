<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Blog_Category_Model extends MY_Model
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
	
	public function get_category($key = false)
	{
		$this->db->select('*');
		$this->db->from('blog_category');
		
		if (is_numeric($key)) {
			$this->db->where('blog_category_id', (int)$key);
		} else {
			$this->db->where('slug', $key);
		}
		
		$this->db->where('active', 1);
		
		return $this->db->get()->row_array();
	}
	
	/**
	 * Create new blog category
	 * 
	 * @access public
	 * @param array $data
	 * @return int | bool
	 */
	public function create($data = array())
	{
		$blog_category_id = $this->insert($data);
		
		if ($blog_category_id) {
			$this->db
			->where('blog_category_id', $blog_category_id)
			->set('slug', $this->validate_slug(url_title(strtolower($data['name'])), $blog_category_id))
			->update('blog_category');
		}
		
		return $blog_category_id;
	}
	
	/**
	 * Update existing blog category
	 * 
	 * @access public
	 * @param array $data
	 * @return int | bool
	 */
	public function edit($blog_category_id, $data = array())
	{
		$data['slug'] = $this->validate_slug(url_title(strtolower($data['name'])), $blog_category_id);
		
		return $this->update($blog_category_id, $data);
	}
	
	/**
	 * Delete blog category
	 * 
	 * @access public
	 * @param int $blog_category_id
	 * @return void
	 */
	public function delete($blog_category_id)
	{
		$this->db
		->where('blog_category_id', $blog_category_id)
		->delete(array('blog_category', 'blog_category_article'));
	}
	
	public function get_category_menus()
	{
		return $this->db
		->select("(SELECT COUNT(blog_category_id) FROM ".$this->db->dbprefix('blog_category_article')." bca LEFT JOIN ".$this->db->dbprefix('blog_article')." ba ON(ba.blog_article_id = bca.blog_article_id) WHERE bca.blog_category_id = bc.blog_category_id AND ba.active = '1') as article_num, bc.blog_category_id, bc.name, bc.slug", false)
		->from('blog_category bc')
		->where('bc.active', 1)
		->order_by('bc.sort_order', 'asc')
		->get()
		->result_array();
	}
	
	public function get_archive() {
		return $this->db
		->select('COUNT(blog_article_id) as article_num, DATE_FORMAT(date_added, "%M %Y") as date_str, DATE_FORMAT(date_added, "%m") as month, DATE_FORMAT(date_added, "%Y") as year', FALSE)
		->where('active', 1)
		->order_by('date_added', 'desc')
		->group_by('date_str')
		->get('blog_article')
		->result_array();
	}
	
	public function get_autocomplete($data = array())
	{
		$sql = "SELECT * FROM ".$this->db->dbprefix('blog_category');
		
		if ( ! empty($data['name'])) {
			$sql .= " WHERE name LIKE '%".$this->db->escape_str($data['name'])."%'";
		}
		
		$sql .= " GROUP BY blog_category_id";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) $data['start'] = 0;
			if ($data['limit'] < 1) $data['limit'] = 20;
			
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		return $this->db->query($sql)->result_array();
	}
	
	public function validate_slug($slug = '', $blog_category_id = null, $counter = null)
	{
		if ($blog_category_id) {
			$this->db->where('blog_category_id !=', $blog_category_id);
		}
		
		$count = $this->db
		->select('slug')
		->from('blog_category')
		->where('slug', $slug.($counter ? '-'.$counter : ''))
		->count_all_results();
		
		if ($count > 0) {
			if( ! $counter) {
				$counter = 1;
			} else {
				$counter++;
			}
		
			return $this->validate_slug($slug, $blog_category_id, $counter);
		} else {
			return $slug.($counter ? '-'.$counter : '');
		}
	}
}