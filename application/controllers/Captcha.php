<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Captcha extends MY_Controller
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
		check_ajax();
		
		$this->load->helper('captcha');
		$this->load->helper('string');
		
		$vals = array(
			'word' => random_string('alnum', 6),
			'img_path' => DIR_IMAGE.'captcha/',
			'img_url' => HTTP_IMAGE.'captcha/',
			'img_width' => '200',
			'img_height' => 30,
			'expiration' => 7200
		);
		
		$captcha = create_captcha($vals);
		
		$this->session->set_userdata('captcha', $captcha);
		
		$this->output->set_output($captcha['image']);
	}
}