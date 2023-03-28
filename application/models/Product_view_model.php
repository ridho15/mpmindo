<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_view_model extends MY_Model
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
	 * Add record
	 * 
	 * @access public
	 * @param int $product_id
	 * @return void
	 */
	public function add($product_id = null)
	{
		$viewed_counts = $this->session->userdata('viewed_counts') ? $this->session->userdata('viewed_counts') : array();
		
		if ( ! in_array($product_id, $viewed_counts)) {
			array_push($viewed_counts, $product_id);
			
			$this->session->set_userdata('viewed_counts', $viewed_counts);
			
			if ($this->agent->is_mobile()) {
				$device = 'mobile';
			} else {
				$device = 'web';
			}
			
			$this->insert(array(
				'product_id' => $product_id,
				'date' => date('Y-m-d', time()),
				'viewed' => 1,
				'device' => $device
			));
		}
	}
	
	/**
	 * Create new record
	 * 
	 * @access public
	 * @param array $data
	 * @return void
	 */
	public function insert($data = array())
	{
		$product_view = $this->db
		->select('product_view_id')
		->where('product_id', $data['product_id'])
		->where('device', $data['device'])
		->where('date', $data['date'])
		->limit(1)
		->get('product_view')
		->row();
			
		if ($product_view) {
			$this->db
			->where('product_view_id', $product_view->product_view_id)
			->set('viewed', 'viewed+'.$data['viewed'], false)
			->update('product_view');
		} else {
			parent::insert(array(
				'product_id' => $data['product_id'],
				'date' => $data['date'],
				'viewed' => $data['viewed'],
				'device' => $data['device']
			));
		}
	}
	
	/**
	 * Get chart of product views
	 * 
	 * @access public
	 * @param array $data
	 * @return array
	 */
	public function get_chart_views($data = array())
	{
		$results = $this->db
		->select('SUM(pv.viewed) as total, u.name as product', false)
		->join('product_view pv', 'pv.product_id = u.product_id', 'left')
		->from('product u');
		
		if (isset($data['product_id'])) {
			$results = $this->db->where('pv.product_id', (int)$data['product_id']);
		}
		
		if (isset($data['group'])) {
			switch($data['group']) {
				case 'year':
					$results = $this->db->where('DATE_FORMAT(pv.date, "%Y") = "'.date('Y', time()).'"', null, false);
				break;
				case 'month':
					$results = $this->db->where('DATE_FORMAT(pv.date, "%Y-%m") = "'.date('Y-m', time()).'"', null, false);
				break;
				case 'date':
					$results = $this->db->where('pv.date', date('Y-m-d', time()));
				break;
			}
		}
		
		$results = $this->db
		->limit(10)
		->order_by('total', 'desc')
		->group_by('u.product_id')
		->get()
		->result_array();
		
		$orders = array();
		
		foreach ($results as $result) {
			$orders[$result['product']] = $result['total'];
		}
		
		return $orders;
	}
	
	public function count_viewed()
	{
		$query = $this->db->select_sum('viewed')->get('product_view')->row_array();
		
		return $query ? $query['viewed'] : 0;
	}
}