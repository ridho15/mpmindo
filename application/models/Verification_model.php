<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Verification_Model extends MY_Model
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
	 * Verifiy code
	 * 
	 * @access public
	 * @params $code
	 * @params $user_id
	 * @return boolean
	 */
	public function verifify($code, $user_id = null)
	{
		if ($user_id) {
			$this->db->where('user_id', (int)$user_id);
		}
		
		return (bool)$this->db
		->where('code', $code)
		->where('date_expired >=', time())
		->count_all_results('verification');
	}
}