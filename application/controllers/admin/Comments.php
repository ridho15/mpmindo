<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Comments extends Admin_Controller
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
		
		$this->load->library('form_validation');

		$this->load->helper('format');
		
		$this->lang->load('comment');
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
			->select('it.id, it.value, it.notes, it.response, it.rating_date, it.response_date, p.user_name, pr.name')
			->from('order_product_rating it')
			->join('order p', 'p.order_id = it.order_id', 'left')
			->join('product pr', 'pr.product_id = it.product_id', 'left')
			->where('it.rating_date != ', '')
			->order_by('it.rating_date', 'desc')
			->edit_column('rating_date', '$1', 'format_date(rating_date)')
			->edit_column('response_date', '$1', 'format_date(response_date)');
			
			$this->output->set_output($this->datatables->generate('json'));
		} else {
			// if ( ! $this->admin->has_permission()) {
			// 	show_401($this->uri->uri_string());
			// }
			
			$this->load
			->title(lang('heading_title'))
			->view('admin/comment');
		}
	}

	/**
	 * Response
	 * 
	 * @access private
	 * @return void
	 */
	public function response()
	{
		$this->load->model('order_model');

		$id = $this->input->get('id');

		$json = array();

		$json['error'] = '';
		
		if ($rating = $this->order_model->get_order_products_rating_by_id($id)) {
			$data['rating'] = $rating;
		} else {
			$json['error'] = 'Data tidak ditemukan';
		}

		$data['action']	= admin_url('comments/validate_response');
		
		$data['heading_title']	= 'Beri Respon';

		if($json['error'] != '') {
			$this->output->set_output(json_encode($json));
		}
		else {
			$this->output->set_output(json_encode(array(
				'content' => $this->load->layout(null)->view('admin/comment_response', $data, true)
			)));
		}
	}

	/**
	 * Validate response form
	 * 
	 * @access public
	 * @return json
	 */
	public function validate_response()
	{
		$this->load->model('order_model');

		$json = array();
		
		$id = $this->input->post('id');
		
		$this->form_validation
		->set_rules('id', 'ID', 'trim|required')
		->set_error_delimiters('', '');
		
		if ($this->form_validation->run() == false) {
			foreach ($this->form_validation->get_errors() as $field => $error) {
				$json['error'][$field] = $error;
			}
		} else {
			$post = $this->input->post(null, false);

			$this->order_model->update_response($id, $post['response']);
			$json['success'] = lang('success_updated');
		}
		
		$this->output->set_output(json_encode($json));
	}
}