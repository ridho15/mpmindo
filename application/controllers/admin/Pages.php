<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends Admin_Controller
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
		
		$this->load->model('page_model');
		$this->lang->load('admin_page');
	}
	
	/**
	 * Index
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		if ($this->input->post(null, true)) {
			$this->load->library('datatables');
		
			$this->datatables
			->select('page_id, title, sort_order, active, slug')
			->from('page');
					
			$this->output->set_output($this->datatables->generate('json'));
		} else {
			if ( ! $this->admin->has_permission()) {
				show_401($this->uri->uri_string());
			}
			
			$this->load
			->title(lang('heading_title'))
			->css(base_url('assets/js/plugins/summernote/summernote.css'))
			->js(base_url('assets/js/plugins/summernote/summernote.min.js'))
			->view('admin/page');	
		}
	}
	
	/**
	 * Create
	 * 
	 * @access public
	 * @return void
	 */
	public function create()
	{
		if (!$this->admin->has_permission()) {
			return $this->output->set_output(json_encode(array('error' => lang('admin_error_create'))));
		}
		
		$this->load->vars(array('heading_title' => lang('heading_create')));
		
		$this->form();
	}
	
	/**
	 * Edit
	 * 
	 * @access public
	 * @return void
	 */
	public function edit()
	{
		if (!$this->admin->has_permission()) {
			return $this->output->set_output(json_encode(array('error' => lang('admin_error_edit'))));
		}
		
		$this->load->vars(array('heading_title' => lang('heading_edit')));
		
		$this->form($this->input->get('page_id'));
	}
	
	/**
	 * Form
	 * 
	 * @access public
	 * @param int $page_id
	 * @return void
	 */
	public function form($page_id = null)
	{
		$data['action'] = admin_url('pages/validate');
		$data['page_id'] = null;
		$data['title'] = '';
		$data['content'] = '';
		$data['description'] = '';
		$data['meta_description'] = '';
		$data['meta_keyword'] = '';
		$data['sort_order'] = 0;
		$data['footer_column'] = 0;
		$data['active'] = 0;
		
		if ($page = $this->page_model->get($page_id)) {
			foreach ($page as $key => $val) {
				$data[$key] = $val;
			}
		}
				
		$this->output->set_output(json_encode(array(
			'content' => $this->load->layout(false)->view('admin/page_form', $data, true)
		)));
	}
	
	/**
	 * Validate
	 * 
	 * @access public
	 * @return void
	 */
	public function validate()
	{
		$this->form_validation
		->set_rules('title', 'lang:entry_title', 'trim|required|min_length[3]')
		->set_rules('content', 'lang:entry_content', 'trim|required|min_length[3]')
		->set_error_delimiters('', '');
		
		$json = array();
		
		if ($this->form_validation->run() === false) {
			foreach ($this->form_validation->get_errors() as $field => $error) {
				$json['error'][$field] = $error;
			}
		} else {
			$page_id = $this->input->post('page_id');
			
			$post['title'] = $this->input->post('title');
			$post['content'] = $this->input->post('content');
			$post['meta_description'] = $this->input->post('meta_description');
			$post['meta_keyword'] = $this->input->post('meta_keyword');
			$post['sort_order'] = (int)$this->input->post('sort_order');
			$post['footer_column'] = (int)$this->input->post('footer_column');
			$post['active'] = (bool)$this->input->post('active');

			if ((bool)$page_id) {
				$this->page_model->edit($page_id, $post);
				$json['success'] = lang('success_updated');
			} else {
				$this->page_model->create($post);
				$json['success'] = lang('success_created');
			}
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	/**
	 * Delete
	 * 
	 * @access public
	 * @return void
	 */
	public function delete()
	{	
		$json = array();
		
		$page_id = $this->input->post('page_id');
		
		if (!$this->admin->has_permission()) {
			$json['error'] = lang('admin_error_delete');
		}
		
		if (!$page_id) {
			$json['error'] = lang('error_no_selected');
		}
		
		if (empty($json['error'])) {
			if (is_array($page_id)) {
				foreach ($page_id as $id) {
					$this->page_model->delete($id);
				}
			} else {
				$this->page_model->delete($page_id);
			}
			
			$json['success'] = lang('success_deleted');
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	/**
	 * Page missing
	 * 
	 * @access public
	 * @return void
	 */
	public function missing()
	{	
		$this->output->set_status_header('404');
		
		$this->load
		->title('Page Not Found')
		->view('admin/page_missing');
	}
} 