<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends Admin_Controller
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
		
		$this->load->helper('format');
		
		$this->load->model('notification_model');
	}
	
	/**
	 * Index
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		if ($this->input->post(null, true)) {
			$this->load->library('datatables');
			$this->load->helper('format');
		
			$this->datatables
			->select('n.notification_id, n.date_added, n.title, n.message, n.table, n.table_id, n.read')
			->join('admin a', 'a.admin_id = n.admin_id', 'left')
			->where('n.admin_id', $this->admin->admin_id())
			->from('notification n')
			->order_by('n.read asc, n.notification_id desc')
			->edit_column('date_added', '$1', 'format_date(date_added,true)');
			
			$this->output->set_output($this->datatables->generate('json'));
		} else {
			$this->load->title('Notifikasi')->view('admin/notification');	
		}
	}
	
	/**
	 * Info
	 * 
	 * @access public
	 * @return void
	 */
	public function info()
	{
		//$data['count_order_notifications'] = $this->notification_model->count_notifications($this->admin->admin_id(), 'order');
		$order_notifications = $this->notification_model->count_notifications($this->admin->admin_id(), 'order');
		$reg_sale_offline_notifications = $this->notification_model->count_notifications($this->admin->admin_id(), 'reg_sale_offline');
		$warranty_notifications = $this->notification_model->count_notifications($this->admin->admin_id(), 'warranty_claim');
		$data['count_notifications'] = $order_notifications + $reg_sale_offline_notifications + $warranty_notifications;
		$data['count_order_notifications'] = $order_notifications;
		$data['count_reg_sale_offline_notifications'] = $reg_sale_offline_notifications;
		$data['warranty_notifications'] = $warranty_notifications;

		$this->load->layout(false)->view('admin/notification_info', $data);
	}
}