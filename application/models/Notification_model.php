<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_model extends MY_Model
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
	 * Count notifications
	 * 
	 * @access public
	 * @param mixed $admin_id
	 * @param mixed $table
	 * @return bool
	 */
	public function count_notifications($admin_id, $table = null)
	{
		return (int)$this->db
		->where('admin_id', $admin_id)
		->where('read', 0)
		->where('table', $table)
		->count_all_results('notification');
	}
	
	/**
	 * Add notification
	 * 
	 * @access public
	 * @param array $data
	 * @return void
	 */
	public function add_notification($data = array())
	{
		$notifications = array();
		
		foreach ($this->admin_model->get_admins() as $admin) {
			$data['admin_id'] = $admin['admin_id'];
			$notifications[] = $data;
		}
		
		if ($notifications) {
			parent::insert_batch($notifications);
		}
	}
	
	/**
	 * Read notification
	 * 
	 * @access public
	 * @param mixed $admin_id
	 * @param mixed $table
	 * @param mixed $table_id
	 * @return void
	 */
	public function read_notification($admin_id, $table, $table_id)
	{
		$this->db
		->where('admin_id', $admin_id)
		->where('table', $table)
		->where('table_id', $table_id)
		->set('read', 1)
		->update('notification');
	}

	/**
	 * Add notification distributor
	 * 
	 * @access public
	 * @param array $data
	 * @return void
	 */
	public function add_notification_distributor($data = array(), $warehouse_id)
	{
		$notifications = array();
		
		foreach ($this->admin_model->get_distributors($warehouse_id) as $admin) {
			$data['admin_id'] = $admin['admin_id'];
			$notifications[] = $data;
		}
		
		if ($notifications) {
			parent::insert_batch($notifications);
		}
	}
}