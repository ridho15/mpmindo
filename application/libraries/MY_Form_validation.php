<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * MY_Form_validation Library Class
 *
 * @package		Alvamedia
 * @subpackage	Libraries
 * @category	Library
 * @author		Adi Setiawan
 * @link
 */
 
class MY_Form_validation extends CI_Form_validation
{
	public $CI;
	
	/**
	 * Get array of errors
	 * 
	 * @access public
	 * @return array
	 */
	public function get_errors()
	{
		$errors = array();
		
		foreach ($this->_field_data as $field => $data) {
			$error = $this->error($field);
			
			if ($error) {
				$errors[$field] = $error;
			}
		}
		
		return $errors;
	}
}