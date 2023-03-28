<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin
{
	private $ci;
	private $admin_id = null;
	private $name = '';
	private $username = '';
	private $group = '';
	private $email = '';
	private $image = null;
	private $permissions = array();
	private $is_distributor = '';
	private $warehouse_id = '';
	private $warehouse_name = '';
	
	/**
	 * Constructor
	 * 
	 * @return void
	 */
	public function __construct()
	{
		$this->ci =& get_instance();
		
		$this->ci->load->model('admin_model');
		//$this->ci->load->model('api/users_model', 'api_users_model');
		//$this->ci->load->model('api/action_logs_model', 'api_action_logs_model');
		$this->ci->load->library('encryption', ['driver' => 'openssl']);
		$this->ci->load->helper('cookie');
		$this->ci->load->helper('language');
		$this->ci->lang->load('admin');
		$this->ci->load->model('warehouse_model');
		
		if ($admin = $this->ci->admin_model->get_admin($this->ci->session->userdata('admin_id'))) {
			$this->admin_id = (int)$admin['admin_id'];
			$this->name	= $admin['name'];
			$this->username	= $admin['username'];
			$this->group = $admin['group_name'];
			$this->email = $admin['email'];
			$this->image = $admin['image'];
			$this->permissions = unserialize($admin['permission']);
			$this->is_distributor = $admin['is_distributor'];
			$this->warehouse_id = $admin['warehouse_id'];
			if($this->is_distributor == '1') {
				$ware = $this->ci->warehouse_model->get_warehouse($this->warehouse_id);
				$this->warehouse_name = $ware['code'].' - '.$ware['name'];
			}
		} elseif ($admin = get_cookie('admin')) {
			$data = json_decode($this->ci->encryption->decrypt($admin), true);
			
			$this->login($data['identity'], $data['password']);
		} else {
			$this->logout();
		}
	}
	
	/**
	 * Do login
	 * 
	 * @access public
	 * @param string $string
	 * @param string $password
	 * @return void
	 */
	public function login($string, $password, $remember = false)
	{
		if ($admin = $this->ci->admin_model->login($string, $password)) {
			$this->ci->session->set_userdata('admin_id', $admin['admin_id']);
			
			$this->ci->admin_model->update_last_login($admin['admin_id']);
			
			$this->admin_id = (int)$admin['admin_id'];
			$this->name	= $admin['name'];
			$this->username	= $admin['username'];
			$this->email = $admin['email'];
			$this->image = $admin['image'];
			$this->is_distributor = $admin['is_distributor'];
			$this->warehouse_id = $admin['warehouse_id'];
			if($this->is_distributor == '1') {
				$ware = $this->ci->warehouse_model->get_warehouse($this->warehouse_id);
				$this->warehouse_name = $ware['code'].' - '.$ware['name'];
			}

			if ($remember) {
				$data['identity'] = $admin['email'];
				$data['password'] = $password;
				
				set_cookie(array(
					'name' => 'admin',
					'value' => $this->ci->encryption->encrypt(json_encode($data)),
					'expire' => strtotime('+6 months'),
				));
			}
			
			//$this->inventory_login($string, $password);
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * Logout
	 * 
	 * @access public
	 * @return void
	 */
	public function logout()
	{
		$this->ci->session->unset_userdata('admin_id');
		
		if (get_cookie('admin')) {
			delete_cookie('admin');
		}
		
		$this->admin_id	= null;
		$this->name	= '';
		$this->username	= '';
		$this->group = '';
		$this->email = '';
		$this->image = null;
		$this->permissions = array();
		$this->is_distributor = '';
		$this->warehouse_id = '';
		$this->warehouse_name = '';

		$this->inventory_logout();
	}
	
	/**
	 * Is admin logged in?
	 * 
	 * @access public
	 * @return bool
	 */
	public function is_logged($admin_redirect = false)
	{
		if ($admin_redirect) {
			$this->ci->session->set_flashdata('admin_redirect', $admin_redirect);
		}
		
		return (bool)$this->admin_id;
	}
	
	/**
	 * Get current admin id
	 * 
	 * @access public
	 * @return int
	 */
	public function admin_id()
	{
		return (int)$this->admin_id;
	}
	
	/**
	 * Get current admin name
	 * 
	 * @access public
	 * @return string
	 */
	public function name()
	{
		return $this->name;
	}
	
	/**
	 * Get current admin username
	 * 
	 * @access public
	 * @return string
	 */
	public function username()
	{
		return $this->username;
	}
	
	/**
	 * Get current admin email
	 * 
	 * @access public
	 * @return string
	 */
	public function email()
	{
		return $this->email;
	}
	
	/**
	 * Get admin group name
	 * 
	 * @access public
	 * @return void
	 */
	public function group()
	{
		if ($this->admin_id < 0) {
			return 'Administrator';
		}
		
		return $this->group;
	}
	
	public function image()
	{
		if ($this->image == '') {
			$image = 'user.png';
		} else {
			$image = $this->image;
		}
		
		return $this->ci->image->resize($image, 50, 50);
	}
	
	/**
	 * Check permission access
	 * 
	 * @access public
	 * @param $action
	 * @param $permission
	 * @return bool
	 */
	public function has_permission($action = null, $permission = false)
	{
		if ($this->admin_id < 0) {
			return true;
		}
		
		if ( ! $action) {
			$action = $this->ci->router->fetch_method();
		}
		
		if ( ! $permission) {
			$permission = $this->ci->router->fetch_class();	
		}
		
		if (isset($this->permissions[$permission])) {
			if (in_array($action, $this->permissions[$permission])) {
				return true;	
			}
		}
		
		return false;
	}
	
	/**
	 * Inventory login
	 * 
	 * @access public
	 * @param mixed $string
	 * @param mixed $password
	 * @return void
	 */
	public function inventory_login($string, $password)
	{
		// if ($this->has_permission('index', 'inventory')) {
		// 	if ($user = $this->ci->api_users_model->login($string, $password)) {
		// 		$this->ci->session->set_userdata('knr_user_id', (int)$user['id']);
		// 		$this->ci->session->set_userdata('knr_level_id', (int)$user['level_id']);
		// 		$this->ci->session->set_userdata('knr_username', $user['username']);
		// 		$this->ci->session->set_userdata('knr_manage', $user['manage_type'].'-'.$user['manage_id']);
				
		// 		$this->ci->api_action_logs_model->insert_log('LOGIN', 'LOGIN', 'Username '.$user['username'].' masuk melalui E-Commerce System', $user['username']);	
		// 	} else {
		// 		$this->inventory_logout();
		// 	}
		// }
	}
	
	/**
	 * Inventory logout
	 * 
	 * @access public
	 * @return void
	 */
	public function inventory_logout()
	{
		$this->ci->session->unset_userdata('knr_user_id');
		$this->ci->session->unset_userdata('knr_level_id');
		$this->ci->session->unset_userdata('knr_username');
		$this->ci->session->unset_userdata('knr_manage');
	}
	
	/**
	 * Check if logged in the inventory system
	 * 
	 * @access public
	 * @return bool
	 */
	public function inventory_is_logged()
	{
		if ($this->ci->session->userdata('knr_user_id')) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Get is distributor
	 * 
	 * @access public
	 * @return string
	 */
	public function is_distributor()
	{
		return $this->is_distributor;
	}

	/**
	 * Get warehouse id
	 * 
	 * @access public
	 * @return string
	 */
	public function warehouse_id()
	{
		return $this->warehouse_id;
	}

	/**
	 * Get warehouse name
	 * 
	 * @access public
	 * @return string
	 */
	public function warehouse_name()
	{
		return $this->warehouse_name;
	}
}