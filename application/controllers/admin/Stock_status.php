<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stock_Status extends Admin_Controller
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
		$this->load->helper('form');
	}
	
	public function index()
	{
		if ($this->input->post(null, true))
		{
			$this->load->library('datatables');
		
			$this->datatables
			->select('stock_status_id, name')
			->from('stock_status');
					
			$this->output->set_output($this->datatables->generate('json'));
		}
		else
		{
			if ( ! $this->auth->has_permission())
			{
				show_401($this->uri->uri_string());
			}
			
			$this->template
			->title('Status Stock')
			->view('admin/stock_status');
		}	
	}
	
	/**
	 * Create new stock status
	 * 
	 * @access public
	 * @return void
	 */
	public function create()
	{
		check_ajax();
		
		if ( ! $this->auth->has_permission())
		{
			return $this->output->set_output(json_encode(array('error' => lang('auth_error_create'))));
		}
		
		$this->template->title('Tambah Status Stock');
		
		$this->form();
	}
	
	/**
	 * Edit existing status stock
	 * 
	 * @access public
	 * @param int $stock_status_id
	 * @return void
	 */
	public function edit()
	{
		check_ajax();
		
		if ( ! $this->auth->has_permission())
		{
			return $this->output->set_output(json_encode(array('error' => lang('auth_error_edit'))));
		}
		
		$this->template->title('Edit Status Stock');
		
		$this->form($this->input->get('stock_status_id'));
	}
	
	/**
	 * Load status stock form
	 * 
	 * @access private
	 * @param int $stock_status_id
	 * @return void
	 */
	private function form($stock_status_id = null)
	{
		$this->load->model('stock_status_model');
		
		$data['action'] = admin_url('stock_status/validate');
		$data['stock_status_id'] = null;
		$data['name'] = '';
		
		if ($stock_status = $this->stock_status_model->get($stock_status_id))
		{
			$data['stock_status_id'] = (int)$stock_status['stock_status_id'];
			$data['name'] = $stock_status['name'];
		}
		
		$this->output->set_output(json_encode(array(
			'content' => $this->template->set_layout(null)->view('admin/stock_status_form', $data, true, true)
		)));
	}
	
	/**
	 * Validate status stock form
	 * 
	 * @access public
	 * @return json
	 */
	public function validate()
	{
		check_ajax();
		
		$json = array();
		
		$stock_status_id = $this->input->post('stock_status_id');
		
		$this->form_validation
		->set_rules('name', 'Nama', 'trim|required|min_length[3]|max_length[128]')
		->set_error_delimiters('', '');
		
		if ($this->form_validation->run() == false)
		{
			if (form_error('name') !== '')
			{
				$json['error']['name'] = form_error('name');
			}
		}
		else
		{
			$data['name'] = $this->input->post('name');
			
			$this->load->model('stock_status_model');
			
			if ($stock_status_id)
			{
				$this->stock_status_model->update($stock_status_id, $data);
				
				$json['success'] = 'Status Stock telah berhasil diperbarui.';
			}
			else
			{
				if ($this->stock_status_model->insert($data))
				{
					$json['success'] = 'Status Stock baru telah ditambahkan.';
				}
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
		check_ajax();
		
		$json = array();
		
		$stock_status_id = $this->input->post('stock_status_id');
		
		if ( ! $this->auth->has_permission())
		{
			$json['error'] = lang('auth_error_delete');
		}
		
		if (empty($json['error']))
		{
			$this->load->model('stock_status_model');
			
			$this->stock_status_model->delete($stock_status_id);
			
			$json['success'] = 'Berhasil menghapus Status Stock';
		}
		
		$this->output->set_output(json_encode($json));
	}
} 