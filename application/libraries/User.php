<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User
{
	private $ci;
	private $user_id = null;
	private $name 	= '';
	private $type = '';
	private $email = '';
	private $image = null;
	private $verified = false;
	private $telephone = '';
	private $address = '';

	public function __construct()
	{
		$this->ci =& get_instance();
		
		$this->ci->load->model('user_model');
		$this->ci->load->library('encryption', ['driver' => 'openssl']);
		$this->ci->load->helper('cookie');
		
		if ($user = $this->ci->user_model->get_user($this->ci->session->userdata('user_id'))) {
			$this->user_id = (int)$user['user_id'];
			$this->name	= $user['name'];
			$this->type = $user['type'];
			$this->email = $user['email'];
			$this->image = $user['image'];
			$this->verified = (bool)$user['verified'];
			$this->telephone = $user['telephone'];
			$this->address = $user['user_address'];
		} elseif ($user = get_cookie('user')) {
			$data = json_decode($this->ci->encryption->decrypt($user), true);
			
			$this->login($data['identity'], '', true, true);
		} else {
			$this->logout();
		}
	}
	
	public function login($string, $password, $remember = false, $override = false)
	{
		if ($user = $this->ci->user_model->login($string, $password, $override)) {
			$this->ci->session->set_userdata('user_id', $user['user_id']);
			
			$this->ci->user_model->update_last_login($user['user_id']);
			
			$this->user_id = (int)$user['user_id'];
			$this->name	= $user['name'];
			$this->type = $user['type'];
			$this->email = $user['email'];
			$this->image = $user['image'];
			$this->verified = (bool)$user['verified'];
			$this->telephone = $user['telephone'];
			$this->address = $user['user_address'];
			
			if ($remember) {
				$data['identity'] = $user['email'];
				
				set_cookie(array(
					'name'   => 'user',
					'value'  => $this->ci->encryption->encrypt(json_encode($data)),
					'expire' => strtotime('+6 months'),
				));
			}
				
			return true;
		}
		
		return false;
	}
	
	public function logout()
	{
		$this->ci->session->unset_userdata('user_id');
		
		if (get_cookie('user')) {
			delete_cookie('user');
		}
		
		$this->user_id	= null;
		$this->name	= '';
		$this->type = '';
		$this->email = '';
		$this->image = null;
		$this->verified = false;
		$this->telephone = '';
		$this->address = '';
	}
	
	public function is_logged($redirect = false)
	{
		if ($redirect) {
			$this->ci->session->set_flashdata('redirect', $redirect);
		}
		
		return (bool)$this->user_id;
	}
	
	public function user_id()
	{
		return (int)$this->user_id;
	}
	
	public function name()
	{
		return $this->name;
	}
	
	public function email()
	{
		return $this->email;
	}
	
	public function type()
	{
		return $this->type;
	}
	
	public function is_verified()
	{
		return (bool)$this->verified;
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
	
	public function get_balance()
	{
		$result = $this->ci->db
		->select_sum('amount')
		->where('user_id', (int)$this->user_id)
		->where('approved', 1)
		->get('user_transaction')
		->row_array();
		
		if ($result) {
			return (float)$result['amount'];
		} else {
			return 0;
		}
	}

	public function telephone()
	{
		return $this->telephone;
	}

	public function address()
	{
		return $this->address;
	}
}