<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends User_Controller
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
	 * Index
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		$this->load->library('currency');
		$this->load->helper('format');
		$data['user'] = $this->user_model->get($this->user->user_id());
		$data['balance'] = $this->currency->format($this->user->get_balance());
		
		$data['count_orders'] = $this->db
		->select('COUNT(DISTINCT(o.order_id)) as total', false)
		->from('order o')
		->where('SUBSTRING_INDEX(o.date_added,"-",1) = "'.date('Y', time()).'"', null, false)
		->where('o.user_id', (int)$this->user->user_id())
		->get()->row()->total;
		
		$this->load
		->breadcrumb('Akun', user_url())
		->view('user/dashboard', $data);
	}
}