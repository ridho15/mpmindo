<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Showcase extends User_Controller 
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('form_validation');
		$this->load->helper('form');
		$this->load->model('showcase_model');
		
		if ( ! $this->user->store_id()) {
			redirect(user_url());
		}
	}
	
	public function index()
	{
		if ($this->input->post(null, true)) {
			$this->load->library('datatables');
			
			$this->datatables
			->select('showcase_id, name')
			->from('showcase')
			->where('store_id', $this->user->store_id());
			
			$this->output->set_output($this->datatables->generate('json'));
		} else {
			$this->load->view('user/showcase');	
		}
	}
	
	public function create()
	{
		check_ajax();
		
		$this->load->vars(array('heading_title' => 'Tambah Showcase'));
		
		$this->form();
	}
	
	public function edit($showcase_id = null)
	{
		check_ajax();
		
		$this->load->vars(array('heading_title' => 'Edit Showcase'));
		
		$this->form($showcase_id);
	}
	
	private function form($showcase_id = null)
	{
		if ($this->input->post()) {
			$json = array();
			
			$this->form_validation->set_rules('name', 'Nama Showcase', 'trim|required');
			
			if ($this->form_validation->run() == false) {
				foreach ($this->form_validation->get_errors() as $field => $error) {
					$json['errors'][$field] = $error;
				}
			} else {
				$post['name'] = $this->input->post('name');
				$post['store_id'] = $this->user->store_id();
				
				if ($showcase_id) {
					$this->showcase_model->update($showcase_id, $post);
					$json['success'] = 'Showcase telah berhasil diupdate!';
				} else {	
					$this->showcase_model->insert($post);
					$json['success'] = 'Showcase baru telah berhasil ditambahkan!';
				}
			}
			
			$this->output->set_output(json_encode($json));
		} else {
			$data['action']	= user_url('showcase/create');
			$data['showcase_id'] = null;
			$data['name'] = '';
			
			if ($showcase = $this->showcase_model->get($showcase_id)) {
				$data['action']	= user_url('showcase/edit/'.$showcase['showcase_id']);
				$data['showcase_id'] = $showcase['showcase_id'];
				$data['name'] = $showcase['name'];
			}
			
			$this->output->set_output(json_encode(array(
				'content' => $this->load->layout(false)->view('user/showcase_form', $data, true, true)
			)));
		}
	}
	
	public function delete()
	{
		check_ajax();
		
		$json = array();
		
		$showcase_id = $this->input->post('showcase_id');
		
		if (empty($json['error'])) {
			$this->showcase_model->delete($showcase_id);
			
			$json['success'] = 'Berhasil menghapus Showcase!';
		}
		
		$this->output->set_output(json_encode($json));
	}
}