<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'models/api/Base_model.php';

class Users_model extends Base_model
{
	private $salt = '$2a$09$';
	
	/**
	 * Constructor
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Get user
	 * 
	 * @access public
	 * @param int $user_id
	 * @return array
	 */
	public function get_user($user_id)
	{
		// return $this->idb
		// ->select('users.id, users.first_name, users.last_name, users.username, users.email, users.level_id, users.manage_type, users.manage_id, levels.level_name as level')
		// ->join('levels', 'levels.id = users.level_id', 'left')
		// ->where('users.id', (int)$user_id)
		// ->get('users')
		// ->row_array();
		return array();
	}
	
	public function get_users($level = array(), $place_type = '', $place_id = '')
	{
		// $query = $this->idb->select('id, username');
		
		// if (count($level) > 0) {
		// 	$query = $this->idb->where_in('level_id', $level);
		// }
		
		// if ($place_type != '' && $place_id != '') {
		// 	$query = $this->idb->where('manage_type', $place_type)->where('manage_id', $place_id);
		// }
		
		// $query = $this->idb->order_by('id', 'asc')->get('users')->result_array();
		
		// return $query;
		return array();
	}
	
	/**
	 * Login
	 * 
	 * @access public
	 * @param string $string
	 * @param string $password
	 * @param bool $force_login
	 * @return bool | array
	 */
	public function login($string = '', $password = '', $force_login = false)
	{
		// $string = htmlentities(stripslashes(trim($string)));
		// $password = htmlentities(stripslashes(trim($password)));
		
		// if (filter_var($string, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $string)) {
		// 	$field = 'email';
		// } else {
		// 	$field = 'username';
		// }
		
		// $user = $this->idb->select('id, first_name, username, email, password, level_id, manage_type, manage_id');
		// $user = $this->idb->where($field, $string);
		
		// if ($force_login === false) {
		// 	$user = $this->idb->where('status', 'Aktif');
		// }
		
		// $user = $this->idb->get('users')->row_array();
		
		// if ($user) {
		// 	if (crypt($password.'{'.$user['username'].'}', $this->salt.$user['password']) == $this->salt.$user['password']) {
		// 		return $user;
		// 	}
		// }
		
		// return false;
		return true;
	}
	
	/**
	 * Get blowfish password
	 * 
	 * @access private
	 * @param string $password
	 * @param string $username
	 * @return string
	 */
	private function get_blowfish_password($password, $username)
	{
		// $salt = $this->salt;
		
		// for ($i = 0; $i < 22; $i++) {
		// 	$salt .= substr('./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', mt_rand(0, 63), 1);
		// }
		
		// $new_password = crypt($password.'{'.$username.'}', $salt);
		
		// return str_replace($this->salt, '', $new_password);
		return '';
	}
}