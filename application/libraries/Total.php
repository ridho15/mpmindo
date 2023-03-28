<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Total
{
	/**
	 * CI Instance
	 * 
	 * @var obj
	 * @access private
	 */
	private $ci;
	
	/**
	 * Drivers
	 * 
	 * @var array
	 * @access private
	 */
	private $drivers = array(
		'subtotal',
		'coupon',
		'tax',
		'credit',
		'shipping',
		'unique',
		'midtrans',
		'total',
	);
	
	/**
	 * Driver classes
	 * 
	 * @var array
	 * @access private
	 */
	private $classes = array();
	
	/**
	 * Constructor
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		$this->ci =& get_instance();
		
		$this->ci->load->library('shopping_cart');
		
		foreach ($this->drivers as $driver) {
			$file = dirname(__FILE__).'/Total/Total_'.$driver.'.php';
			
			if (file_exists($file)) {
				include_once($file);
				$class = 'Total_'.$driver;
				$this->classes[$driver] = new $class;
			}
		}
	}
	
	/**
	 * Get totals
	 * 
	 * @access public
	 * @return void
	 */
	public function get_totals()
	{
		$codes = func_get_args();
		
		$total_data = array();
		$total = 0;
		
		if (count($codes) > 0) {
			foreach ($codes as $code) {
				if (isset($this->classes[$code])) {
					$class = $this->classes[$code];
					$class->get($total_data, $total);
				}
			}
		} else {
			foreach ($this->classes as $class) {
				$class->get($total_data, $total);
			}
		}
		
		return array($total_data, $total);
	}
	
	/**
	 * Confirm callback
	 * 
	 * @access public
	 * @param int $order_id
	 * @return void
	 */
	public function confirm($order_id)
	{
		$order = $this->ci->db
		->where('order_id', (int)$order_id)
		->get('order')
		->row_array();
		
		$order_totals = $this->ci->db
		->where('order_id', (int)$order_id)
		->order_by('sort_order', 'asc')
		->get('order_total')
		->result_array();

		foreach ($order_totals as $order_total) {
			if (isset($this->classes[$order_total['code']]) && method_exists($this->classes[$order_total['code']], 'confirm')) {
				$this->classes[$order_total['code']]->confirm($order, $order_total);
			}
		}
	}
}