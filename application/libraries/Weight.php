<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Weight
{
	private $ci;
	
	/**
	 * Weight classes data
	 * 
	 * @var array
	 * @access private
	 */
	private $weights = array();
	
	/**
	 * Constructor
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		$this->ci =& get_instance();
		
		$this->ci->load->model('weight_class_model');
		
		foreach ($this->ci->weight_class_model->get_all() as $weight) {
			$this->weights[$weight['weight_class_id']] = $weight;
		}
	}
	
	/**
	 * Convert weight class to another class
	 * 
	 * @access public
	 * @param int $value Weight value
	 * @param int $from Weight id as source
	 * @param int $to Weight id as destination
	 * @return number
	 */
	public function convert($value = 0, $from = null, $to = null)
	{
		if ($from == $to) return $value;
		
		$from = (isset($this->weights[$from])) ? $this->weights[$from]['value'] : 0;
		$to = (isset($this->weights[$to])) ? $this->weights[$to]['value'] : 0;
		
		if ($from != 0 && $to != 0) {
			return $value * ($to/$from);
		}
		
		return $value;
	}
	
	/**
	 * Get weight unit
	 * 
	 * @access public
	 * @param int $id Weight id
	 * @return string Weight unit
	 */
	public function get_unit($id = null)
	{
		return (isset($this->weights[$id])) ? $this->weights[$id]['unit'] : '';
	}
	
	/**
	 * Format weight class
	 * 
	 * @access public
	 * @param int $value Weight value
	 * @param int $id Weight id
	 * @param string $decimal_point Decimal separtor
	 * @param string $thousand_point Thousand separator
	 * @return string
	 */
	public function format($value = 0, $id = null, $decimal_point = ',', $thousand_point = '.')
	{
		return (isset($this->weights[$id]))
		? number_format($value, 2, $decimal_point, $thousand_point).$this->weights[$id]['unit']
		: number_format($value, 2, $decimal_point, $thousand_point);
	}
}