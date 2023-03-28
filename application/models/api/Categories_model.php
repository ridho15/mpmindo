<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'models/api/Base_model.php';

class Categories_model extends Base_model
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
	 * Get inventory categories
	 * 
	 * @access public
	 * @return array
	 */
	public function get_icategories()
	{
		// $this->idb->set_dbprefix();
		
		// return $this->idb
		// ->select('c.category_id, pc.id as inventory_category_id, pc.category_name as name, c.parent_id, 
		// 	(CASE WHEN c.description IS NULL THEN pc.category_name ELSE c.description END) AS description, 
		// 	(CASE WHEN c.meta_description IS NULL THEN pc.category_name ELSE c.meta_description END) AS meta_description, 
		// 	(CASE WHEN c.meta_title IS NULL THEN pc.category_name ELSE c.meta_title END) AS meta_title')
		// ->from('knr_product_categories pc')
		// ->join($this->edb->database.'.category c', 'c.inventory_category_id = pc.id', 'left')
		// ->get()
		// ->result_array();

		return array();
	}
	
	/**
	 * Synchronize categories
	 * 
	 * @access public
	 * @return void
	 */
	public function synch_categories()
	{
		// $this->load->model('category_model');
		
		// $categories = [];
		
		// foreach ($this->get_icategories() as $icategory) {
		// 	if ($icategory['category_id']) {
		// 		$this->category_model->update($icategory['category_id'], $icategory);
		// 	} else {
		// 		$this->category_model->insert($icategory);
		// 	}
		// }
	}
}