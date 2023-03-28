<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends MY_Controller
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
		
		if ( ! $this->input->is_cli_request()) {
			exit('CLI is required!');
		}
		
		$this->load->database();
	}
	
	/**
	 * Check stock. Mengirim info stock yang akan habis ke admin
	 * Run every hour
	 * 
	 * @access public
	 * @return void
	 */
	public function check_stock()
	{
		$results = $this->db
		->select('SUM(i.quantity) as total, p.name, p.product_id, p.minimum_stock', false)
		->from('inventory i')
		->join('product p', 'p.product_id = i.product_id', 'left')
		->where('i.warehouse_id', (int)$this->config->item('warehouse_id'))
		->where('(SELECT SUM(i.quantity)) <= p.minimum_stock', null, false)
		->order_by('total', 'asc')
		->group_by('i.product_id')
		->get()
		->result_array();
		
		if ($results) {
			$data['products'] = $results;
			
			$this->load->library('email');
				
			$this->email->from('halo@mpmindo.id', $this->config->item('site_name'));
			$this->email->to($this->config->item('email'));
			$this->email->subject('Informasi Stock');
			$this->email->message($this->load->layout(false)->view('emails/stock_notification', $data, true));
			$this->email->send();
		
			echo 'Executed for : '.count($results). ' item(s).';
			die();
		} else {
			echo 'Everything is OK!';
			die();
		}
	}
}