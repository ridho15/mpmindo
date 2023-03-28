<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Pagination extends CI_Pagination
{
	/**
	 * Initialize Preferences
	 * Rewrite to fix double page variables
	 *
	 * @access	public
	 * @param	array	initialization parameters
	 * @return	void
	 */
	function initialize(array $params = Array())
	{
		if (count($params) > 0)
		{
			foreach ($params as $key => $val)
			{
				if (isset($this->$key))
				{
					$this->$key = $val;
				}
			}
		}
		
		$ci =& get_instance();
		
		if ($ci->config->item('enable_query_strings') === true OR $this->page_query_string === true)
		{
			// bug fix for double page variables
			$this->base_url = preg_replace('/(.*)(?|&)page=[^&]+?(&)(.*)/i', '$1$2$4', $this->base_url . '&'); 
			$this->base_url = substr($this->base_url, 0, -1);
		}
	}
}