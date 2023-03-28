<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Download extends Admin_Controller
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
	 * Download file
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		$this->load->model('upload_model');
		
		$upload_id = $this->input->post('upload_id');
		
		if ($upload = $this->upload_model->get($upload_id)) {
			$data = file_get_contents($upload['full_path']);
			$name = $upload['client_name'];
			
			$this->load->helper('download');
			
			force_download($name, $data);
		} else {
			show_404();
		}
	}
}