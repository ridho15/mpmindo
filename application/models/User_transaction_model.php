<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Transaction_Model extends MY_Model
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
	 * Get transactions
	 * 
	 * @access public
	 * @param array $data
	 * @return array
	 */
	public function get_transactions($data = array())
	{
		$this->db->where('user_id', (int)$this->user->user_id());
		
		$sort_data = array(
			'amount',
			'description',
			'date_added'
		);
		
		$sort	= (isset($data['sort']) and in_array($data['sort'], $sort_data)) ? $data['sort'] : 'date_added';
		$order	= (isset($data['order']) and ($data['order'] == 'desc')) ? 'desc' : 'asc';
		
		$this->db->order_by($sort, $order);

		if (isset($data['start']) or isset($data['limit']))
		{
			if ($data['start'] < 0) $data['start'] = 0;		
			if ($data['limit'] < 1) $data['limit'] = 20;

			$this->db->limit($data['limit'], $data['start']);
		}

		return $this->db
		->get('user_transaction')
		->result_array();
	}
	
	/**
	 * Count user transactions
	 * 
	 * @access public
	 * @return int
	 */
	public function count_transactions()
	{
		return $this->db
		->select('user_transaction_id')
		->where('user_id', (int)$this->user->user_id())
		->count_all_results('user_transaction');
	}
	
	/**
	 * Get transactions amount
	 * 
	 * @access public
	 * @return float
	 */
	public function get_total_amount()
	{
		$result = $this->db
		->select_sum('amount')
		->where('user_id', (int)$this->user->user_id())
		->group_by('user_id')
		->get('user_transaction')
		->row_array();
		
		return $result ? $result['amount'] : 0;
	}
	
	public function get_total_transactions()
	{
		return 0;
	}
}