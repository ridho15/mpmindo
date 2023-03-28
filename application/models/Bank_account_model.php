<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bank_Account_Model extends MY_Model
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
	
	public function get_bank_account($bank_account_id = null)
	{
		return $this->db
		->select('ba.*, b.name, b.code')
		->join('bank b', 'b.bank_id = ba.bank_id', 'left')
		->from('bank_account ba')
		->where('ba.bank_account_id', $bank_account_id)
		->get()
		->row_array();
	}
	
	public function get_bank_accounts()
	{
		return $this->db
		->select('ba.*, b.name, b.code')
		->join('bank b', 'b.bank_id = ba.bank_id', 'left')
		->from('bank_account ba')
		->where('ba.active', 1)
		->get()
		->result_array();
	}
}