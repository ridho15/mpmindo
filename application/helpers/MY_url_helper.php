<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('url_title'))
{
	/**
	 * Create URL Title
	 *
	 * Takes a "title" string as input and creates a
	 * human-friendly URL string with a "separator" string 
	 * as the word separator.
	 *
	 * @access	public
	 * @param	string	the string
	 * @param	string	the separator
	 * @return	string
	 */
	function url_title($str, $separator = '-', $lowercase = false)
	{
		if ($separator == 'dash') 
		{
		    $separator = '-';
		}
		else if ($separator == 'underscore')
		{
		    $separator = '_';
		}
		
		$q_separator = preg_quote($separator);

		$trans = array(
			'&'						=> $separator.'dan'.$separator,
			'&.+?;'                 => '',
			'[^a-z0-9 _-]'          => '',
			'\s+'                   => $separator,
			'('.$q_separator.')+'   => $separator
		);

		$str = strip_tags($str);

		foreach ($trans as $key => $val)
		{
			$str = preg_replace("#".$key."#i", $val, $str);
		}

		if ($lowercase === true)
		{
			$str = strtolower($str);
		}

		return trim($str, $separator);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('admin_url'))
{
	/**
	 * Generate admin URL
	 * 
	 * @access public
	 * @param string $uri
	 * @return string
	 */
	function admin_url($uri = '')
	{
		$path = 'admin';
		
		if (defined('PATH_ADMIN'))
		{
			$path = PATH_ADMIN;
		}
		
		return site_url($path.'/'.$uri);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('user_url'))
{
	/**
	 * Generate admin URL
	 * 
	 * @access public
	 * @param string $uri
	 * @return string
	 */
	function user_url($uri = '')
	{
		$path = 'user';
		
		if (defined('PATH_USER'))
		{
			$path = PATH_USER;
		}
		
		return site_url($path.'/'.$uri);
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('build_query_string'))
{
	/**
	 * Build query string
	 * 
	 * @access public
	 * @param array $params Query parameters
	 * @param int $start Confirm if query string must to start from the first
	 * @return void
	 */
	function build_query_string($params = array(), $start = false)
	{
		$ci =& get_instance();
		
		$query  = '';
		
		if ($params > 0)
		{
			$query .= $start ? '?' : '';
			
			foreach ($params as $key => $val)
			{
				$query .= '&'.$key.'='.$val;
			}
		}
		
		return $query;
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('show_401'))
{
	/**
	 * Unauthorized page
	 * 
	 * @access public
	 * @param string $page
	 * @return void
	 */
	function show_401()
	{
		require(APPPATH.'controllers/error.php');
		$_error = new Error;
		$_error->http('401');
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('show_503'))
{
	/**
	 * If under maintenance
	 * 
	 * @access public
	 * @return void
	 */
	function show_503()
	{
		require(APPPATH.'controllers/error.php');
		$_error = new Error;
		$_error->http('503');
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('check_ajax'))
{
	/**
	 * Check ajax request
	 * 
	 * @access public
	 * @return void
	 */
	function check_ajax()
	{
		$ci =& get_instance();
		
		if ( ! $ci->input->is_ajax_request())
		{
			show_404();
		}
	}
}