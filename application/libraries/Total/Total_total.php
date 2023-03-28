<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Total_total
{
	protected $sort_order = 8;
	
	/**
	 * Constructor
	 * 
	 * @access public
	 * @return void
	 */
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
		$total_data[] = array(
			'code' => 'total',
			'title' => 'Total',
			'text' => $this->ci->currency->format(max(0, $total)),
			'value' => max(0, $total),
			'sort_order' => $this->sort_order
		);
	}
}