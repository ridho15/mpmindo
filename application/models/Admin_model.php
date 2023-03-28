<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_Model extends MY_Model
{
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
	 * Create new admin
	 * 
	 * @access public
	 * @param array $data
	 * @return int | bool
	 */
	public function create_admin($data = array())
	{
		$this->load->helper('string');
		$this->load->helper('security');
		
		if (empty($data['password'])) {
			$data['password'] = random_string('alnum', 8);
		}
		
		$data['salt'] = hash_string();
		$data['password'] = hash_string($data['password'], $data['salt']);
		
		$this->db
		->set($this->set_data($data))
		->insert('admin');
		
		$admin_id = $this->db->insert_id();
		
		if ($admin_id) {
			$admin = $this->db
			->select('admin.admin_id, admin.name, admin.email, admin.username, admin.active, admin_group.name as group_name')
			->join('admin_group', 'admin_group.admin_group_id = admin.admin_group_id', 'left')
			->where('admin_id', (int)$admin_id)
			->get('admin')
			->row_array();
			
			if (empty($admin['username'])) {
				$username = strtolower(str_replace(' ', '', $admin['name']));
				$username = substr($username,0,5).random_string('numeric', 4);
				
				$this->db
				->where('admin_id', (int)$admin_id)
				->set(array('username' => $username))
				->update('admin');
			}
		}
		
		return $admin_id;
	}
	
	/**
	 * Update existing admin
	 * 
	 * @access public
	 * @param array $data
	 * @return int | bool
	 */
	public function update_admin($admin_id, $data = array())
	{
		$this->load->helper('string');
		$this->load->helper('security');
		
		if ( ! empty($data['password'])) {
			$data['salt'] = hash_string();
			$data['password'] = hash_string($data['password'], $data['salt']);
		} else {
			unset($data['password']);
		}
		
		$this->db
		->set($this->set_data($data))
		->where('admin_id', (int)$admin_id)
		->update('admin');
		
		return $this->db->affected_rows();
	}
	
	/**
	 * Get admin
	 * 
	 * @access public
	 * @param int $admin_id
	 * @return array
	 */
	public function get_admin($admin_id = null)
	{
		return $this->db
		->select('admin.admin_id, admin.name, admin.username, admin.email, admin.image, admin_group.name as group_name, admin_group.permission, admin.is_distributor, admin.warehouse_id')
		->join('admin_group', 'admin_group.admin_group_id = admin.admin_group_id', 'left')
		->where('admin_id', (int)$admin_id)
		->get('admin')
		->row_array();
	}
	
	/**
	 * Get admins
	 * 
	 * @access public
	 * @return array
	 */
	public function get_admins()
	{
		return $this->db
		->select('admin.admin_id, admin.name, admin.email, admin.image, admin_group.name as group_name, admin_group.permission, admin.is_distributor, admin.warehouse_id')
		->join('admin_group', 'admin_group.admin_group_id = admin.admin_group_id', 'left')
		->where('active', 1)
		->where('is_distributor','0')
		->get('admin')
		->result_array();
	}
	
	/**
	 * Get admin login data
	 * 
	 * @access public
	 * @param string $string
	 * @param string $password
	 * @param bool $force_login
	 * @return mixed
	 */
	public function login($string = '', $password = '', $force_login = false)
	{
		if (filter_var($string, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $string)) {
			$field = 'email';
		} else {
			$field = 'username';
		}
		
		$admin = $this->db->select('admin_id, name, email, salt, password, image, username, is_distributor, warehouse_id');
		$admin = $this->db->where($field, $string);
		
		if ($force_login === false) {
			$admin = $this->db->where('active', 1);
		}
		
		$admin = $this->db->get('admin')->row_array();
		
		if ($admin) {
			$this->load->helper('security');
			
			if (hash_string($password, $admin['salt']) === $admin['password']) {
				return $admin;
			}
			
			return false;
		}
		
		return false;
	}
	
	/**
	 * Set admin active
	 * 
	 * @access public
	 * @param int $admin_id
	 * @param string $code
	 * @return void
	 */
	public function active($admin_id, $code = false)
	{
		if ($code !== false) {
			$query = $this->db->select('admin_id')
			->where('admin_id', (int)$admin_id)
			->where('code_activation', $code)
			->count_all_results('admin');
			
			if ($query !== 1) {
				return false;
			}
			
			$this->db
			->where('admin_id', (int)$admin_id)
			->where('code_activation', $code)
			->set(array('code_activation' => '', 'active' => 1))
			->update('admin');
		} else {
			$this->db
			->where('admin_id', (int)$admin_id)
			->set(array('code_activation' => '', 'active' => 1))
			->update('admin');
		}

		return $this->db->affected_rows();
	}
	
	/**
	 * Deactive admin
	 * 
	 * @access public
	 * @param int $admin_id
	 * @return void
	 */
	public function deactivate($admin_id = null)
	{
		if (empty($admin_id)) {
			return false;
		}
		
		$this->load->helper('security');
		
		$this->db
		->where('admin_id', (int)$admin_id)
		->set(array('code_activation' => hash_string(microtime().$admin_id), 'active' => 0))
		->update('admin');

		return $this->db->affected_rows();
	}
	
	/**
	 * Change password
	 * 
	 * @access public
	 * @param int $admin_id
	 * @param string $old Old password
	 * @param string $new New password
	 * @return bool
	 */
	public function change_password($admin_id, $old, $new)
	{
		$admin = $this->db
		->select('admin_id, salt, password')
		->where('admin_id', (int)$admin_id)
		->get('admin')
		->row_array();
		
		if ($admin) {
			$this->load->helper('security');
			
			if (hash_string($old, $admin['salt']) === $admin['password']) {
				$this->db
				->where('admin_id', (int)$admin_id)
				->set(array('password' => hash_string($new, $admin['salt'])))
				->update('admin');
				
				return true;
			}
			
			return false;
		}
	}
	
	/**
	 * Request reset password code
	 * 
	 * @access public
	 * @param string $email
	 * @return mixed
	 */
	public function request_reset_password($email)
	{
		if (empty($email)) {
			return false;
		}
		
		$this->load->helper('security');

		$code = hash_string(microtime().$email);
		
		$this->db
		->where('email', strtolower($email))
		->set('code_forgotten', $code)
		->update('admin');
		
		return ($this->db->affected_rows() == 1) ? $code : false;
	}
	
	/**
	 * Cancel reset password
	 * 
	 * @access public
	 * @param string $code
	 * @return bool
	 */
	public function cancel_reset_password($code)
	{
		if (empty($code)) {
			return false;
		}

		if ($admin = $this->db->where('code_forgotten', $code)->get('admin')->row_array()) {
			$this->db
			->where('admin_id', (int)$admin['admin_id'])
			->set('code_forgotten', '')
			->update('admin');
			
			return $this->db->affected_rows();
		}

		return false;
	}
	
	/**
	 * Reset password
	 * 
	 * @access public
	 * @param string $code
	 * @return mixed
	 */
	public function reset_password($code)
	{
		if (empty($code)) {
			return false;
		}

		if ($admin = $this->db->where('code_forgotten', $code)->get('admin')->row_array()) {
			$this->load->helper('string');
			$this->load->helper('security');
			
			$new_password = random_string('alnum', 8);
			$password = hash_string($new_password, $admin['salt']);
			
			$this->db
			->where('admin_id', (int)$admin['admin_id'])
			->set(array('password' => $password, 'code_forgotten' => '', 'active' => 1))
			->update('admin');
			
			return ($this->db->affected_rows() == 1) ? $new_password : false;
		}

		return false;
	}
	
	
	/**
	 * Update last login
	 * 
	 * @access public
	 * @param int $admin_id
	 * @return void
	 */
	public function update_last_login($admin_id = null)
	{
		$this->db
		->where('admin_id', (int)$admin_id)
		->set(array('last_login' => date('Y-m-d H:i:s', time()), 'ip' => $this->input->ip_address()))
		->update('admin');
	}
	
	/**
	 * Get admin field data
	 * 
	 * @access public
	 * @param int $admin_id
	 * @param int $field
	 * @return void
	 */
	public function get_admin_data($admin_id = null, $field = null)
	{
		if ( ! in_array($field, $this->db->list_fields('admin'))) {
			return false;
		}
	
		$result = $this->db
		->select($field)
		->where('admin_id', (int)$admin_id)
		->get('admin')
		->row_array();
		
		return $result ? $result[$field] : false;
	}
	
	/**
	 * Check email
	 * 
	 * @access public
	 * @param string $email
	 * @param int $admin_id
	 * @return bool
	 */
	public function check_email($email, $admin_id = null)
	{
		if ($admin_id) {
			$this->db->where('admin_id !=', (int)$admin_id);
		}
		
		return (bool)$this->db
		->select('admin_id')
		->where('email', strtolower($email))
		->count_all_results('admin');
	}
	
	/**
	 * Check email
	 * 
	 * @access public
	 * @param string $email
	 * @param int $admin_id
	 * @return bool
	 */
	public function check_username($username, $admin_id = null)
	{
		if ($admin_id) {
			$this->db->where('admin_id !=', (int)$admin_id);
		}
		
		return (bool)$this->db
		->select('admin_id')
		->where('username', $username)
		->count_all_results('admin');
	}
	
	/**
	 * Check admin group
	 * 
	 * @access public
	 * @param int $admin_group_id
	 * @return bool
	 */
	public function check_admin_group($admin_group_id = null)
	{
		return (bool)$this->db
		->where('admin_group_id', (int)$admin_group_id)
		->count_all_results('admin');
	}

	/**
	 * Get distributors
	 * 
	 * @access public
	 * @return array
	 */
	public function get_distributors($warehouse_id)
	{
		return $this->db
		->select('admin.admin_id, admin.name, admin.email, admin.image, admin_group.name as group_name, admin_group.permission, admin.is_distributor, admin.warehouse_id')
		->join('admin_group', 'admin_group.admin_group_id = admin.admin_group_id', 'left')
		->where('active', 1)
		->where('is_distributor','1')
		->where('warehouse_id',$warehouse_id)
		->get('admin')
		->result_array();
	}
}