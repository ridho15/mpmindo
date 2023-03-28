<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class comment extends User_Controller
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
		if ($this->input->post(null, true)) {
			$this->load->library('datatables');
			$this->load->helper('format');
		
			$this->datatables
			->select('c.comment_id, c.text, c.date_added, s.name as store, s.logo as store_logo, s.domain as store_domain, p.name as product, p.category_id as product_category_id, p.slug as product_slug, (SELECT image FROM product_image pi WHERE pi.product_id = c.product_id ORDER BY sort_order ASC LIMIT 0,1) as product_image', false)
			->from('comment c')
			->join('product p', 'p.product_id = c.product_id', 'left')
			->join('store s', 's.store_id = p.store_id', 'left')
			->where('c.user_id', $this->user->user_id())
			->edit_column('date_added', '$1', 'format_date(date_added)')
			->add_column('product_link', '$1', 'get_product_link(product_slug,product_category_id)')
			->add_column('image_link', '$1', 'get_image_link(product_image)');
			
			$this->output->set_output($this->datatables->generate('json'));
		} else {
			$this->load->title('Ulasan')->view('user/comment');	
		}
	}
}

function get_product_link($slug, $category_id) {
	$_ci =& get_instance();
	$_ci->load->model('category_model');
	
	$path = 'product/';
	
	if ($category = $_ci->category_model->get_category($category_id)) {
		$paths = explode('-', $category['path_id']);
		foreach ($paths as $path_id) {
			if ($c = $_ci->category_model->get_category($path_id)) {
				$path .= $c['slug'].'/';
			}
		}
		
		$path .= $category['slug'].'/';
	}
	
	$path .= $slug;
	
	return site_url($path);
}

function get_image_link($image) {
	$_ci =& get_instance();
	$_ci->load->library('image');
	
	return $_ci->image->resize($image, 60, 60);
}