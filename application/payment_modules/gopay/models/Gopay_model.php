<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gopay_model extends MY_Model 
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
		
		$this->lang->load('gopay');
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
			'code' => 'gopay',
			'title'=> lang('heading_title'),
			'sort_order' => 2
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