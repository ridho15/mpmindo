<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('reorder'))
{
	/**
	 * Re-order array
	 * 
	 * @access public
	 * @param array $array
	 * @param string $sort_key
	 * @param string $sort
	 * @return array
	 */
	function reorder($array, $sort_key = null, $sort = 'asc')
	{
		$sort_order = array();
	
		foreach ($array as $key => $value) {
			$sort_order[$key] = $value[$sort_key];
		}
		
		if ($sort == 'asc') {
			array_multisort($sort_order, SORT_ASC, $array);
		} else {
			array_multisort($sort_order, SORT_DESC, $array);
		}
		
		return $array;
	}
}

if ( ! function_exists('array_keys_multi'))
{
	/**
	 * Get all keys from multidimensional array
	 * 
	 * @access public
	 * @param array $array
	 * @return array
	 */
	function array_keys_multi($array)
	{
		$keys = array();
		
		foreach ($array as $key => $value) {
			$keys[] = $key;
			
			if (is_array($value) && count($value) > 0) {
				$keys = array_merge($keys, array_keys_multi($value));	
			}
		}
		
		return $keys;
	}
}