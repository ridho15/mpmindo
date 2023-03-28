<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class About extends MY_Controller
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
	
	public function index()
	{
		$data = array();
		
		$this->load->model('article_model');
		
		// 9 is default about us article id
		if ($article = $this->article_model->get(9)) {
			$this->load->view('about', $article);
		} else {
			show_404();
		}
	}
}