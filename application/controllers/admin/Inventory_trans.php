<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_trans extends Admin_Controller
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
		
		$this->load->model('inventory_model');
		$this->load->model('inventory_trans_model');
		$this->load->model('warehouse_model');
		$this->load->model('article_model');
		$this->load->model('article_history_model');

		$this->load->helper('format');
		
		$this->lang->load('admin_inventory_trans');
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
			->select('it.inventory_trans_id, wf.name as warehouse_from, wt.name as warehouse_to, it.description, it.date_added')
			->join('warehouse wf', 'wf.warehouse_id = it.warehouse_from', 'left')
			->join('warehouse wt', 'wt.warehouse_id = it.warehouse_to', 'left')
			->from('inventory_trans it')
			->edit_column('date_added', '$1', 'format_date(date_added)');
			
			if($this->admin->warehouse_id() != 0) {
				$this->datatables->where('it.warehouse_from', $this->admin->warehouse_id());
				$this->datatables->or_where('it.warehouse_to', $this->admin->warehouse_id());
			}
			
			$this->output->set_output($this->datatables->generate('json'));
		} else {
			// if ( ! $this->admin->has_permission()) {
			// 	show_401($this->uri->uri_string());
			// }
			
			$data['warehouses'] = $this->warehouse_model->get_all_warehouse();
			
			$this->load
			->title(lang('heading_title'))
			->view('admin/inventory_trans', $data);
		}
	}
	
	/**
	 * Detail
	 * 
	 * @access public
	 * @return void
	 */
	public function detail()
	{
		if ( ! $this->admin->has_permission('index')) {
			return $this->output->set_output(json_encode(array('error' => lang('admin_error_index'))));
		}
		
		if ($inventory_trans = $this->inventory_trans_model->get_inventory_trans($this->input->get('inventory_trans_id'))) {
			$data = array();
			
			foreach ($inventory_trans as $key => $value) {
				$data[$key] = $value;
			}
			
			$this->load->title(lang('heading_title').' #'.$data['inventory_trans_id']);
			
			$this->output->set_output(json_encode(array(
				'content' => $this->load->layout(null)->view('admin/inventory_trans_detail', $data, true, true)
			)));
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
		check_ajax();
		
		// if ( ! $this->admin->has_permission()) {
		// 	return $this->output->set_output(json_encode(array('error' => lang('admin_error_create'))));
		// }
		
		$this->load->title(lang('heading_create'));
		
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
		if ( ! $this->admin->has_permission()) {
			return $this->output->set_output(json_encode(array('error' => lang('admin_error_edit'))));
		}
		
		$this->load->title(lang('heading_edit'));
		
		$this->form($this->input->get('inventory_id'));
	}
	
	/**
	 * Form
	 * 
	 * @access private
	 * @return void
	 */
	private function form()
	{
		$this->inventory_id = $this->input->get('inventory_id');
		
		$json = array();
		
		$data['action']	= admin_url('inventory_trans/validate');
		
		$data['warehouse_froms'][0] = lang('text_inbound');
		$data['warehouse_tos'][0] = lang('text_outbound');
		
		foreach ($this->warehouse_model->get_all_warehouse() as $warehouse) {
			if(($this->admin->warehouse_id() != 0 && $this->admin->warehouse_id() == $warehouse['warehouse_id']) || $this->admin->warehouse_id() == 0) {
				$data['warehouse_froms'][$warehouse['warehouse_id']] = $warehouse['code'].' ('.$warehouse['name'].')';
				$data['warehouse_tos'][$warehouse['warehouse_id']] = $warehouse['code'].' ('.$warehouse['name'].')';
			}
		}
		
		if ($inventory_trans = $this->inventory_trans_model->get_inventory_trans($this->input->get('inventory_trans_id'))) {
			$data['heading_title']	= 'Edit Mutasi Stok #'.$inventory_trans['inventory_trans_id'];
			$data['date_added'] = date('d-m-Y', strtotime($inventory_trans['date_added']));
			$data['inventory_trans_id'] = (int)$inventory_trans['inventory_trans_id'];
			$data['warehouse_from'] = (int)$inventory_trans['warehouse_from'];
			$data['warehouse_to'] = (int)$inventory_trans['warehouse_to'];
			$data['description'] = $inventory_trans['description'];
			$data['inventories'] = $inventory_trans['inventories'];
		} else {
			$data['heading_title']	= 'Tambah Mutasi Stok';
			$data['inventory_trans_id'] = null;
			$data['date_added'] = date('d-m-Y', time());
			$data['warehouse_from'] = 0;
			$data['warehouse_to'] = 0;
			$data['description'] = '';
			$data['inventories'] = array();
		}
		
		$this->output->set_output(json_encode(array(
			'content' => $this->load->layout(null)->view('admin/inventory_trans_form', $data, true, true)
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
		->set_rules('description', 'lang:label_note', 'trim|required|min_length[3]')
		->set_error_delimiters('', '');
		
		$json = array();
		
		if ($this->form_validation->run() == false) {
			foreach (array('description') as $field) {
				if (form_error($field) !== '') {
					$json['error'][$field] = form_error($field);
				}
			}
		} else {
			$post['warehouse_from'] = (int)$this->input->post('warehouse_from');
			$post['warehouse_to'] = (int)$this->input->post('warehouse_to');
			$post['type'] = $this->input->post('type');
			$post['description'] = $this->input->post('description');
			$post['date_added'] = date('Y-m-d h:i:s', strtotime($this->input->post('date_added')));
			
			if ($this->input->post('inventories')) {
				$inventories = $this->input->post('inventories');
			} else {
				$inventories = array();
			}
			
			if (count($inventories) < 1) {
				$json['warning'] = lang('error_item_required');
			}
			
			if ((int)$this->input->post('warehouse_from') == (int)$this->input->post('warehouse_to')) {
				$json['warning'] = lang('error_same_origin');
			}
			
			foreach ($inventories as $inventory) {
				$warehouse = $this->warehouse_model->get((int)$this->input->post('warehouse_from'));
				if ($warehouse && ($this->inventory_model->check_stock((int)$inventory['product_id'], (int)$this->input->post('warehouse_from')) < (int)$inventory['quantity'])) {
					$json['warning'] = sprintf(lang('error_not_enough_stock'), $warehouse['name']);
					break;
				}
			}

			if ( ! $json) {
				if ((bool)$this->input->post('inventory_trans_id')) {
					$inventory_trans_id = $this->input->post('inventory_trans_id');
					
					$this->inventory_trans_model->update((int)$this->input->post('inventory_trans_id'), $post);
					$this->inventory_model->delete_by(array('trans_table' => 'inventory_trans', 'trans_table_id' => (int)$this->input->post('inventory_trans_id')));
						
					$json['success'] = lang('success_updated');
				} else {
					$inventory_trans_id = $this->inventory_trans_model->insert($post);

					$json['success'] = lang('success_created');
				}
				
				$post_inventories = array();
				
				if ($inventory_trans_id) {	
					if ($this->input->post('warehouse_from')) {
						foreach ($inventories as $inventory) {
							$post_inventories[] = array(
								'trans_table' => 'inventory_trans',
								'trans_table_id' => $inventory_trans_id,
								'product_id' => $inventory['product_id'],
								'warehouse_id' => (int)$this->input->post('warehouse_from'),
								'quantity' => (int)$inventory['quantity'] * -1,
								'date_added' => $post['date_added']
							);
						}
					}
					
					if ($this->input->post('warehouse_to')) {
						foreach ($inventories as $inventory) {
							$post_inventories[] = array(
								'trans_table' => 'inventory_trans',
								'trans_table_id' => $inventory_trans_id,
								'product_id' => $inventory['product_id'],
								'warehouse_id' => (int)$this->input->post('warehouse_to'),
								'quantity' => (int)$inventory['quantity'],
								'date_added' => $post['date_added']
							);
						}
					}
					
					if ($post_inventories) $this->inventory_model->insert_batch($post_inventories);
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
		$json = array();
		
		$inventory_trans_id = $this->input->post('inventory_trans_id');
		
		if ( ! $this->admin->has_permission()) {
			$json['error'] = lang('admin_error_delete');
		}
		
		if (empty($json['error'])) {
			if (is_array($inventory_trans_id)) {
				foreach ($inventory_trans_id as $id) {
					$this->inventory_model->delete_by(array('trans_table' => 'inventory_trans', 'trans_table_id' => (int)$id));
				}
			} else {
				$this->inventory_model->delete_by(array('trans_table' => 'inventory_trans', 'trans_table_id' => (int)$inventory_trans_id));
			}
			
			$this->inventory_trans_model->delete($inventory_trans_id);
			
			$json['success'] = lang('success_deleted');
		}
		
		$this->output->set_output(json_encode($json));
	}
}