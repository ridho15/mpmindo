<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Coupon_Model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function count_coupons()
	{
		return $this->db->count_all_results('coupon');
	}

	public function get_coupon_histories($coupon_id, $start = 0, $limit = 10)
	{
		if ($start < 0) $start = 0;
		if ($limit < 1) $limit = 20;
		
		return $this->db
		->select('ch.order_id, c.name, ch.amount, ch.date_added', false)
		->from('coupon_history')
		->join('user c', 'ch.user_id = c.user_id', 'left')
		->where('ch.coupon_id', (int)$category_id)
		->order_by('ch.date_added', 'asc')
		->limit((int)$limit, (int)$start)
		->get()
		->result_array();
	}

	public function count_coupon_histories($coupon_id)
	{
		return $this->db
		->where('coupon_id', (int)$coupon_id)
		->count_all_results('coupon_history');
	}
	
	public function validate_coupon($code = '', $total = true)
	{
		$status = true;
		
		$coupon_query = $this->db
		->where('code', $code)
		->where('(date_start = \'0000-00-00\' OR date_start < NOW())', null, false)
		->where('(date_end = \'0000-00-00\' OR date_end > NOW())', null, false)
		->where('active', 1)
		->get('coupon')
		->row_array();
		
		if ($coupon_query) {
			if ($total && (float)$coupon_query['total'] >= (float)$total) {
				$status = false;
			}

			$coupon_history_total = $this->db
			->select('coupon_id')
			->where('coupon_id', (int)$coupon_query['coupon_id'])
			->count_all_results('coupon_history');
			
			if ($coupon_query['uses_total'] > 0 && ($coupon_history_total >= $coupon_query['uses_total'])) {
				$status = false;
			}
		} else {
			$status = false;
		}

		if ($status) {
			return $coupon_query;
		}
	}
	
	public function redeem($coupon_id, $order_id, $amount)
	{
		$this->db
		->set(array(
			'coupon_id'	=> (int)$coupon_id,
			'order_id' => (int)$quote_id,
			'amount' => (float)$amount,
			'date_added' => date('Y-m-d H:i:s', time())
		))->insert('coupon_history');
	}

	public function delete($coupon_id)
	{
		$this->db
		->where('coupon_id', $coupon_id)
		->delete('coupon');
	}
}