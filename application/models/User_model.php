<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Model extends MY_Model
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
	 * Create new user
	 * 
	 * @access public
	 * @param array $data
	 * @return int | bool
	 */
	public function create_user($data = array())
	{
		$this->load->helper('string');
		$this->load->helper('security');
		
		if (empty($data['password'])) {
			$data['password'] = random_string('alnum', 8);
		}
		
		$this->session->set_userdata('random_password', $data['password']);
		
		$data['date_added'] = date('Y-m-d H:i:s', time());
		$data['salt'] = hash_string();
		$data['password'] = hash_string($data['password'], $data['salt']);
		
		$username = strtolower(str_replace(' ', '', $data['name']));
		$username = substr($username,0,5).random_string('numeric', 4);
		
		$data['username'] = $username;
		
		$this->db
		->set($this->set_data($data))
		->insert('user');
		
		return $this->db->insert_id();
	}
	
	/**
	 * Update existing user
	 * 
	 * @access public
	 * @param array $data
	 * @return int | bool
	 */
	public function update_user($user_id, $data = array())
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
		->where('user_id', (int)$user_id)
		->update('user');
		
		return $this->db->affected_rows();
	}
	
	/**
	 * Get user
	 * 
	 * @access public
	 * @param int $user_id
	 * @return array
	 */
	public function get_user($user_id = null)
	{
		return $this->db
		->select('user.user_id, user.name, user.email, user.image, user.type, user.verified, user.telephone, user.user_address')
		->where('user.user_id', (int)$user_id)
		->get('user')
		->row_array();
	}

	public function get_user_xendit_plan($user_id = null)
	{
		return $this->db
		->select('user.xendit_plan')
		->where('user.user_id', (int)$user_id)
		->get('user')
		->row_array();
	}
	
	/**
	 * Get user login data
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
		
		$user = $this->db->select('user_id, name, email, salt, password, image, type, verified, telephone, user_address');
		$user = $this->db->where($field, $string);
		
		if ($force_login == false) {
			$user = $this->db->where('active', 1);
		}
			
		$user = $this->db->get('user')->row_array();
		
		if ($user) {
			$this->load->helper('security');
			
			if ($force_login) {
				return $user;
			}
			
			if (hash_string($password, $user['salt']) === $user['password']) {
				return $user;
			}
			
			return false;
		}
		
		return false;
	}
	
	/**
	 * Set user active
	 * 
	 * @access public
	 * @param int $user_id
	 * @param string $code
	 * @return void
	 */
	public function active($user_id, $code = false)
	{
		if ($code !== false) {
			$query = $this->db->select('user_id')
			->where('user_id', (int)$user_id)
			->where('code_activation', $code)
			->count_all_results('user');
			
			if ($query !== 1) {
				return false;
			}
			
			$this->db
			->where('user_id', (int)$user_id)
			->where('code_activation', $code)
			->set(array('code_activation' => '', 'active' => 1))
			->update('user');
	    } else {
	    	$this->db
			->where('user_id', (int)$user_id)
			->set(array('code_activation' => '', 'active' => 1))
			->update('user');
	    }

		return $this->db->affected_rows();
	}
	
	/**
	 * Deactive user
	 * 
	 * @access public
	 * @param int $user_id
	 * @return void
	 */
	public function deactivate($user_id = null)
	{
		if (empty($user_id)) {
			return false;
		}
		
		$this->load->helper('security');
		
		$this->db
		->where('user_id', (int)$user_id)
		->set(array('code_activation' => hash_string(microtime().$user_id), 'active' => 0))
		->update('user');

		return $this->db->affected_rows();
	}
	
	/**
	 * Change password
	 * 
	 * @access public
	 * @param int $user_id
	 * @param string $old Old password
	 * @param string $new New password
	 * @return bool
	 */
	public function change_password($user_id, $old, $new)
	{
		$user = $this->db
		->select('user_id, salt, password')
		->where('user_id', (int)$user_id)
		->get('user')
		->row_array();
		
		if ($user) {
			$this->load->helper('security');
			
			if (hash_string($old, $user['salt']) === $user['password']) {
				$this->db
				->where('user_id', (int)$user_id)
				->set(array('password' => hash_string($new, $user['salt'])))
				->update('user');
				
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
		->update('user');
		
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

		if ($user = $this->db->where('code_forgotten', $code)->get('user')->row_array()) {
			$this->db
			->where('user_id', (int)$user['user_id'])
			->set('code_forgotten', '')
			->update('user');
			
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

		if ($user = $this->db->where('code_forgotten', $code)->get('user')->row_array()) {
			$this->load->helper('string');
			$this->load->helper('security');
			
			$new_password = random_string('alnum', 8);
			$password = hash_string($new_password, $user['salt']);
			
			$this->db
			->where('user_id', (int)$user['user_id'])
			->set(array('password' => $password, 'code_forgotten' => '', 'active' => 1))
			->update('user');
			
			return ($this->db->affected_rows() == 1) ? $new_password : false;
		}

		return false;
	}
	
	
	/**
	 * Update last login
	 * 
	 * @access public
	 * @param int $user_id
	 * @return void
	 */
	public function update_last_login($user_id = null)
	{
		$this->db
		->where('user_id', (int)$user_id)
		->set(array('last_login' => date('Y-m-d H:i:s', time()), 'ip' => $this->input->ip_address()))
		->update('user');
	}
	
	/**
	 * Get user field data
	 * 
	 * @access public
	 * @param int $user_id
	 * @param int $field
	 * @return void
	 */
	public function get_user_data($user_id = null, $field = null)
	{
		if ( ! in_array($field, $this->db->list_fields('user'))) {
			return false;
		}
	
		$result = $this->db
		->select($field)
		->where('user_id', (int)$user_id)
		->get('user')
		->row_array();
		
		return $result ? $result[$field] : false;
	}
	
	/**
	 * Check email
	 * 
	 * @access public
	 * @param string $email
	 * @param int $user_id
	 * @return bool
	 */
	public function check_email($email, $user_id = null)
	{
		if ($user_id) {
			$this->db->where('user_id !=', (int)$user_id);
		}
		
		return (bool)$this->db
		->select('user_id')
		->where('email', strtolower($email))
		->count_all_results('user');
	}
	
	/**
	 * Check username
	 * 
	 * @access public
	 * @param string $email
	 * @param int $user_id
	 * @return bool
	 */
	public function check_username($username, $user_id = null)
	{
		if ($user_id) {
			$this->db->where('user_id !=', (int)$user_id);
		}
		
		return (bool)$this->db
		->select('user_id')
		->where('username', $username)
		->count_all_results('user');
	}
	
	/**
	 * Check telephone
	 * 
	 * @access public
	 * @param string $telephone
	 * @param int $user_id
	 * @return bool
	 */
	public function check_telephone($telephone, $user_id = null)
	{
		if ($user_id) {
			$this->db->where('user_id !=', (int)$user_id);
		}
		
		return (bool)$this->db
		->select('user_id')
		->where('telephone', (string)$telephone)
		->count_all_results('user');
	}
	
	/**
	 * Get users autocomplete
	 * 
	 * @access public
	 * @param array $data
	 * @return array
	 */
	public function get_users_autocomplete($data = array())
	{
		$this->db->from('user');
		
		if ( ! empty($data['type'])) {
			$this->db->where('type', $data['type']);
		}

		if ( ! empty($data['name'])) {
			$this->db->like('name', $data['name'], 'both');
			$this->db->or_like('user_id', (int)$data['name'], 'both');
		}
		
		$this->db->group_by('user_id');

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) $data['start'] = 0;
			if ($data['limit'] < 1) $data['limit'] = 20;
			
			$this->db->limit((int)$data['limit'], (int)$data['start']);
		}

		return $this->db->get()->result_array();
	}
	
	public function set_default_address($user_id, $address_id)
	{
		$this->db
		->where('user_id', (int)$user_id)
		->set('address_id', $address_id)
		->update('user');
	}
	
	/**
	 * Count users
	 * 
	 * @access public
	 * @param array $params
	 * @return int
	 */
	public function count_users($params)
	{
		$query = $this->db
		->select('user_id')
		->where('date_added >= ', $params['start'])
		->where('date_added <= ', $params['end']);
		
		if ( ! empty($params['gender'])) {
			$query = $this->db->where('gender', $params['gender']);
		}
		
		$query = $this->db->count_all_results('user');
		
		return (int)$query;
	}
}