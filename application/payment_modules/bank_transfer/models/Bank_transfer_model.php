<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank_transfer_model extends MY_Model 
{	
	/**
	 * Construction
	 * 
	 * @access public
	 * @return void
	 */	
	public function __construct()
	{
		parent::__construct();
		
		$this->lang->load('bank_transfer');
	}
	
	/**
	 * Get method data
	 * 
	 * @access public
	 * @return array
	 */
	public function get_method()
	{
		return [
			'code' => 'bank_transfer',
			'title'=> lang('heading_title'),
			'sort_order' => 1
		];
	}
	
	/**
	 * Install
	 * 
	 * @access public
	 * @return bool
	 */
	public function install()
	{
		return true;
	}
	
	/**
	 * Uninstall
	 * 
	 * @access public
	 * @return bool
	 */
	public function uninstall()
	{	
		return true;
	}
}