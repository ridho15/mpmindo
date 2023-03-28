<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'models/api/Base_model.php';

class Action_logs_model extends Base_model
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
	 * Insert action log
	 * 
	 * @access public
	 * @param string $module
	 * @param string $type
	 * @param string $desc
	 * @param string $username
	 * @return void
	 */
	public function insert_log($module, $type, $desc, $username)
	{
		// $data = array(
		// 	'action_date' => date('Y-m-d H:i:s'),
		// 	'action_module' => $module,
		// 	'action_type' => $type,
		// 	'action_desc' => $desc,
		// 	'action_by' => $username,	
		// 	'ip_address' => $this->input->ip_address()
		// );
		
		// $this->idb->insert('action_logs', $data);
	}
}