<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Base_model extends CI_Model
{
	protected $edb;
	protected $idb;
	
	/**
	 * Constructor
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->edb = $this->load->database('default', true);
		$this->idb = $this->load->database('inventory', true);
	}
}