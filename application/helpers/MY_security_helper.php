<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('hash_string'))
{
	/*
	 * Generate hashed string for password, etc
	 * 
	 * @access public
	 * @param string $str
	 * @param string $hash
	 * @return string
	 */
	function hash_string($string = null, $hash = null)
	{
		if ( ! $hash) $hash = substr(md5(uniqid(rand(), true)), 0, 9);
		
		return $string ? do_hash($hash.do_hash($hash.do_hash($string))) : $hash;
	}
}

if ( ! function_exists('get_blowfish_code'))
{
	/**
	 * Get blowfish code
	 * 
	 * @access public
	 * @return void
	 */
	function get_blowfish_code()
	{
		return '$2a$09$';
	}
}

if ( ! function_exists('get_blowfish_password'))
{	
	/**
	 * Get blowfish code
	 * 
	 * @access public
	 * @param mixed $password
	 * @param mixed $username
	 * @return void
	 */
	function get_blowfish_password($password, $username)
	{
		$salt = get_blowfish_code();
		
		for ($i = 0; $i < 22; $i++) {
			$salt .= substr('./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', mt_rand(0, 63), 1);
		}
		
		$new_password = crypt($password.'{'.$username.'}', $salt);
		
		return str_replace(get_blowfish_code(), '', $newPassword);
	}
}