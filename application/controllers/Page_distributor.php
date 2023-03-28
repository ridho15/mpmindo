<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page_distributor extends MY_Controller
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
		
		$this->load->model('list_distributor_model');
		$this->load->model('warehouse_model');
		$this->load->model('location_model');
		$this->lang->load('list_distributor');
	}
	
	/**
	 * Index admins data
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		// $data['wholesaler']=$this->list_distributor_model->get_wholesaler();
		// $data['reseller']=$this->list_distributor_model->get_reseller();
		// $data['list_distributor']=$this->list_distributor_model->get_list_distributor();
		$data['province']=$this->list_distributor_model->get_province();
		$this->load->view('page_distributor',$data);
	}

} 