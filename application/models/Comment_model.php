<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment_Model extends MY_Model
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
	
	public function get_comments($data = array())
	{
		$result = $this->db
		->select('c.comment_id, c.text, c.date_added, u.name as author, u.image, s.store_id, s.name as store')
		->join('user u', 'u.user_id = c.user_id', 'left')
		->join('store s', 's.user_id = u.user_id', 'left')
		->from('comment c');
		
		if (isset($data['product_id'])) {
			$result = $this->db->where('c.product_id', (int)$data['product_id']);
		}
		
		if (isset($data['parent_id'])) {
			$result = $this->db->where('c.parent_id', (int)$data['parent_id']);
		}
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	

			$result = $this->db->limit($data['limit'], $data['start']);
		}
		
		$result = $this->db
		->order_by('date_added', 'desc')
		->get()
		->result_array();
		
		return $result;
	}
	
	public function count_comments($data = array())
	{
		$result = $this->db->select('comment_id');
		
		if (isset($data['product_id'])) {
			$result = $this->db->where('product_id', (int)$data['product_id']);
		}
		
		if (isset($data['parent_id'])) {
			$result = $this->db->where('parent_id', (int)$data['parent_id']);
		}
		
		$result = $this->db->count_all_results('comment');
		
		return (int)$result;
	}
}