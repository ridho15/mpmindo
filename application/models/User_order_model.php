<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_Order_Model extends MY_Model
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
	
	public function get_features($user_id)
	{
		$result = $this->db
		->select('service_feature')
		->from('service_order')
		->where('user_id', (int)$user_id)
		->where('active', 1)
		->where('date_start < NOW() AND date_end > NOW()', null, false)
		->get()
		->row_array();
		
		if ($result) {
			return unserialize($result['service_feature']);
		} else {
			return array();
		}
	}
}