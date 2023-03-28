<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	protected $is_mobile = false;
	
	/**
	 * Constructor
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('language');
		$this->load->helper('form');
		$this->lang->load('default');
		
		$this->_set_config();
		
		if ( ! $this->input->is_cli_request()) {
			if ($this->config->item('maintenance') && ! $this->admin->is_logged() && $this->uri->segment(1) !== PATH_ADMIN && $this->uri->segment(1) !== 'captcha') {
				$this->output->set_status_header('503');
				
				exit($this->load->layout(false)->view('maintenance', array(), true));
			}
		}
		
		$this->load->model('page_model');
		$this->load->vars('pages', $this->page_model->get_page_menus());
		
		$this->load->layout('default');
		
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->library('user');
		$this->load->library('asset');
		
		$this->load->breadcrumb('Home', site_url());
		
		if ($this->input->get('user_location')) {
			$this->session->set_userdata('user_location', $this->input->get('user_location'));
		}
		
		$this->is_mobile = (bool)$this->agent->is_mobile();
	}
	
	/**
	 * Set config from database
	 * 
	 * @access private
	 * @return void
	 */
	private function _set_config()
	{
		$this->load->model('setting_model');
		
		foreach ($this->setting_model->get_settings() as $group => $setting) {
			foreach ($setting as $key => $value) {
				if ($group == 'config' && ! $this->config->item($key)) {
					$this->config->set_item($key, $value);
				} else {
					$this->config->set_item($group, $setting);
				}
			}
		}
		
		$this->load->library('image');
		
		$this->load->vars(array(
			'_logo' => $this->image->resize($this->config->item('logo'), 150, 50),
			'_favicon' => $this->image->resize($this->config->item('icon'), 32, 32),
			'_copyright' => sprintf(lang('text_copyright'), $this->config->item('company')),
			'_categories' => $this->_get_categories(),
			'_payment_logos' => $this->_get_payment_logos()
		));
	}
	
	public function _get_payment_logos()
	{
		$this->load->model('banner_model');
		
		$data['banners'] = array();
		
		$banners = $this->banner_model->get_images(5);
		
		foreach ($banners as $banner) {
			if ($banner['active']) {
				$data['banners'][] = array(
					'image' => $this->image->resize($banner['image'], 60, 30),
					'title' => $banner['title'],
					'subtitle' => $banner['subtitle'],
					'link' => $banner['link'],
					'link_title' => $banner['link_title'],
				);
			}
 		}
 		
 		return $data['banners'];
	}
	
	public function _get_categories()
	{			
		$this->load->model('category_model');
		
		$categories = array();
		
		foreach ($this->category_model->get_subcategories(0) as $category) {
			$children = array();

			foreach ($this->category_model->get_subcategories($category['category_id']) as $subcategory) {
				$subchildren = array();
				
				foreach ($this->category_model->get_subcategories($subcategory['category_id']) as $subsubcategory) {
					$subchildren[] = array(
						'category_id' => $subsubcategory['category_id'],
						'name' => ucwords(strtolower($subsubcategory['name'])),
						'href' => site_url('category/'.$category['slug'].'/'.$subcategory['slug'].'/'.$subsubcategory['slug'])
					);
				}
			
				$children[] = array(
					'category_id' => $subcategory['category_id'],
					'name' => ucwords(strtolower($subcategory['name'])),
					'href' => site_url('category/'.$category['slug'].'/'.$subcategory['slug']),
					'children' => $subchildren
				);
			}
			
			$categories[] = array(
				'category_id' => $category['category_id'],
				'name' => ucwords(strtolower($category['name'])),
				'href' => site_url('category/'.$category['slug']),
				'children' => $children
			);
		}
		
		return $categories;
	}
}

class Admin_Controller extends MY_Controller
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
		
		$this->load->library('admin');
		$this->load->desktop();
		$this->load->layout('admin');
		$this->lang->load('admin_common');
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$this->_check_access();
		
		// global variables
		
		$this->load->vars(array(
			'_name' => $this->admin->name(),
			'_image' => $this->admin->image()
		));
	}
	
	/**
	 * Check access permission
	 * 
	 * @access private
	 * @return void
	 */
	private function _check_access()
	{
		$ignored_pages = array(PATH_ADMIN.'/login', PATH_ADMIN.'/logout', PATH_ADMIN.'/reset');
		$current_page = $this->uri->segment(1, '').'/'.$this->uri->segment(2, 'index');	
				
		if (in_array($current_page, $ignored_pages)) {
			if ($this->admin->is_logged() && $current_page != PATH_ADMIN.'/logout') {
				redirect(PATH_ADMIN);
			}
			
			return true;
		}
		
		if ( ! $this->admin->is_logged() && $current_page != PATH_ADMIN.'/login') {
			$this->session->set_userdata('admin_redirect', $this->uri->uri_string());
			redirect(PATH_ADMIN.'/login');
		}

		return true;
	}
}

class User_Controller extends MY_Controller
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
		
		$this->_check_access();
		
		$this->load->vars(array(
			'_name' => $this->user->name(),
			'_image' => $this->user->image(),
			'_is_logged' => $this->user->is_logged(),
			'_is_verified' => $this->user->is_verified(),
		));
	}
	
	/**
	 * Check access permission
	 * 
	 * @access private
	 * @return void
	 */
	private function _check_access()
	{
		$ignored_pages = array(PATH_USER.'/login', PATH_USER.'/facebook_login', PATH_USER.'/google_login', PATH_USER.'/logout', PATH_USER.'/reset');
		$current_page = $this->uri->segment(1, '').'/'.$this->uri->segment(2, 'index');
		
		if (in_array($current_page, $ignored_pages)) {
			if ($this->user->is_logged() && $current_page != PATH_USER.'/logout') {
				redirect(PATH_USER);
			}
			
			return true;
		}
		
		if ( ! $this->user->is_logged() && $current_page != 'user/login') {
			$this->session->set_userdata('redirect', $this->uri->uri_string());
			redirect(PATH_USER.'/login');
		}

		return true;
	}
}

class Murdock_Controller extends MY_Controller
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
	 * Check login
	 * 
	 * @access public
	 * @return void
	 */
	public function _check_login()
	{
		return $this->user->is_logged();
	}
}