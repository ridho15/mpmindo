<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Review_Model extends MY_Model
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
	
	public function get_review($review_id)
	{
		return $this->db
		->select('r.*, p.name')
		->from('review r')
		->join('product p', 'r.product_id = p.product_id', 'left')
		->where('r.review_id', (int)$review_id)
		->get()
		->row_array();
	}
}