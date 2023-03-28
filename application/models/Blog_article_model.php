<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Blog_Article_Model extends MY_Model
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
	
	public function get_article($key = false)
	{
		$this->db->select('ba.*, a.name as author');
		$this->db->from('blog_article ba');
		$this->db->join('admin a', 'a.admin_id = ba.admin_id', 'left');
		
		if (is_numeric($key)) {
			$this->db->where('ba.blog_article_id', (int)$key);
		} else {
			$this->db->where('ba.slug', $key);
		}
		
		$this->db->where('ba.active', 1);
		
		$result = $this->db->get()->row_array();
		
		if ($result) {
			$this->db->where('blog_article_id', (int)$result['blog_article_id']);
			$this->db->set('views', 'views+1', false);
			$this->db->update('blog_article');
		}
		
		return $result;
	}
	
	public function get_articles($data = array(), $count = false)
	{
		if ($count) {
			$this->db->select('ba.blog_article_id');
		} else {
			$this->db->select('ba.*, a.name as author');
		}
		
		if ( ! empty($data['blog_category_id'])) {
			$this->db->from('blog_category_article bca');
			$this->db->join('blog_article ba', 'ba.blog_article_id = bca.blog_article_id', 'left');
		} else {
			$this->db->from('blog_article ba');
		}
		
		$this->db->join('admin a', 'a.admin_id = ba.admin_id', 'left');
		$this->db->where('ba.active', 1);
		
		if ( ! empty($data['blog_category_id'])) {
			$this->db->where('bca.blog_category_id', (int)$data['blog_category_id']);
		}
		
		if (isset($data['year']) && isset($data['month'])) {
			$next_month = $data['month'] + 1;
			$from = date("Y-m-d H:i:s", mktime(0, 0, 0, $data['month'], 0, $data['year']));
			$to = date("Y-m-d H:i:s", mktime(23, 59, 59, $next_month, 0, $data['year']));
			
			$this->db->where('ba.date_added >', $from);
			$this->db->where('ba.date_added <', $to);
		}
		
		$this->db->order_by('ba.date_added', 'desc');
		
		if ( ! $count) {
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				
	
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}
				
				$this->db->limit((int)$data['limit'], (int)$data['start']);
			}
		}
		
		if ($count) {
			return $this->db->count_all_results();
		} else {
			return $this->db->get()->result_array();	
		}
	}
	
	public function create($data = array())
	{
		$blog_article_id = $this->insert($data);
		
		if ($blog_article_id) {
			$this->db
			->where('blog_article_id', $blog_article_id)
			->set('slug', $this->validate_slug(url_title(strtolower($data['title'])), $blog_article_id))
			->update('blog_article');
		}
		
		return $blog_article_id;
	}
	
	public function edit($blog_article_id, $data = array())
	{
		$data['slug'] = $this->validate_slug(url_title(strtolower($data['title'])), $blog_article_id);
		
		return $this->update($blog_article_id, $data);
	}
	
	public function delete($blog_article_id)
	{
		$this->db
		->where('blog_article_id', $blog_article_id)
		->delete(array('blog_article', 'blog_category_article'));
	}
	
	public function get_categories($blog_article_id)
	{
		return $this->db
		->select('bca.*, bc.name as category')
		->join('blog_category bc', 'bc.blog_category_id = bca.blog_category_id', 'left')
		->where('bca.blog_article_id', $blog_article_id)
		->get('blog_category_article bca')
		->result_array();
	}
	
	public function set_categories($blog_article_id, $categories = array())
	{
		$this->db
		->where('blog_article_id', $blog_article_id)
		->delete('blog_category_article');
		
		$blog_category_article = array();
		
		foreach ($categories as $category_id)
		{
			$blog_category_article[] = array(
				'blog_category_id' => (int)$category_id,
				'blog_article_id' => (int)$blog_article_id,
			);
		}
		
		if ($blog_category_article) $this->db->insert_batch('blog_category_article', $blog_category_article);
	}
	
	public function validate_slug($slug = '', $blog_article_id = null, $counter = null)
	{
		if ($blog_article_id) {
			$this->db->where('blog_article_id !=', $blog_article_id);
		}
		
		$count = $this->db
		->select('slug')
		->from('blog_article')
		->where('slug', $slug.($counter ? '-'.$counter : ''))
		->count_all_results();
		
		if ($count > 0) {
			if( ! $counter) {
				$counter = 1;
			} else {
				$counter++;
			}
		
			return $this->validate_slug($slug, $blog_article_id, $counter);
		} else {
			return $slug.($counter ? '-'.$counter : '');
		}
	}
}