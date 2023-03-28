<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Total_credit
{
	protected $sort_order = 4;
	
	public function __construct()
	{
		$this->ci =& get_instance();
	}
	
	/**
	 * Get total
	 * 
	 * @access public
	 * @param array &$total_data
	 * @param float &$total
	 * @return void
	 */
	public function get(&$total_data, &$total)
	{
		if ($this->ci->config->item('credit:status')) {
			$balance = $this->user->get_balance();
			
			if ((float)$balance) {
				$credit = ($balance > $total) ? $total : $balance;
				
				if ($credit > 0) {
					$total_data[] = array(
						'code' => 'credit',
						'title' => 'Saldo Anda',
						'text' => $this->ci->currency->format(-$credit),
						'value' => -$credit,
						'sort_order' => $this->sort_order
					);
					
					$total -= $credit;
				}
			}
		}
	}
	
	/**
	 * Confirm
	 * 
	 * @access public
	 * @param array $order_info
	 * @param array $order_total
	 * @return void
	 */
	public function confirm($order_info, $order_total)
	{
		if ($order_info['user_id']) {
			$this->ci->db
			->set(array(
				'user_id' => (int)$order_info['user_id'],
				'order_id' => (int)$order_info['order_id'],
				'description' => sprintf('ID Order: #%s', (int)$order_info['order_id']),
				'amount' => (float)$order_total['value'],
				'date_added' => date('Y-m-d', time()),
			))->insert('user_transaction');			
		}
	}
}