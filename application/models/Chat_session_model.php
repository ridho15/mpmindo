<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Chat_session_model extends MY_Model
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
	
	public function count_average_duration()
	{
		$query = $this->db
		->select('AVG(time_end-time_start) AS time')
		->where('time_end !=', null)->get('chat_session')
		->row_array();
		
		return $query ? $query['time'] : 0;
	}
}