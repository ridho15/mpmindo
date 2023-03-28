<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order_Shipping extends Admin_Controller
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
		
		$this->load->model('order_model');
		$this->load->helper('form');
		$this->load->library('form_validation');
	}
	
	public function index()
	{
		if ($this->input->post(null, true)) {
			$this->load->library('datatables');
			$this->load->helper('format');
			
			$this->datatables
			->select('so.store_order_id, so.order_id, o.user_name, CONCAT_WS(" - ", o.user_city, o.user_province) as location, so.total, os.name as status, o.date_added, s.name as store, s.domain as store_domain', false)
			->from('store_order so')
			->join('order o', 'o.order_id = so.order_id', 'left')
			->join('order_status os', 'os.order_status_id = so.order_status_id', 'left')
			->join('store s', 's.store_id = so.store_id', 'left')
			->edit_column('total', '$1', 'format_money(total)')
			->edit_column('date_added', '$1', 'format_date(date_added)');
			
			$this->output->set_output($this->datatables->generate('json'));
		} else {
			if ( ! $this->admin->has_permission()) {
				show_401($this->uri->uri_string());
			}
			
			$this->load->view('admin/order_shipping');
		}
	}
	
	public function detail($order_store_id = null)
	{
		$this->load->helper('format');
		$this->load->model('store_order_model');
		$this->load->library('currency');
		
		if ($order = $this->store_order_model->get_store_order($order_store_id)) {
			foreach ($order as $key => $value) {
				$data[$key] = $value;
			}
			
			$this->load->model('order_status_model');
			
			$data['order_statuses'] = $this->order_status_model->get_all();
			$data['subtotal'] = $this->currency->format($data['subtotal']);
			$data['shipping_cost'] = $this->currency->format($data['shipping_cost']);
			
			if ($data['admin_fee'] > 0) {
				$data['total'] = $this->currency->format($data['total']-$data['admin_fee']);
			} else {
				$data['total'] = $this->currency->format($data['total']);
			}
			
			$data['admin_fee'] = $data['admin_fee'] > 0 ? $this->currency->format($data['admin_fee']*(-1)) : false;
			
			$data['status']	= $data['status'];
			$data['date_added']	= format_date($data['date_added']);
			
			$data['products'] = array();
			
			$key = 1;
			
			foreach ($this->order_model->get_store_order_products($data['order_id'], $data['store_id']) as $product)
			{
				$product['price'] = $this->currency->format($product['price']);
				$product['total'] = $this->currency->format($product['total']);
				
				$data['products'][$key] = $product;
				
				$key++;
			}
			
			$data['totals'] = array();
			
			$address = array(
				'name' => $data['user_name'],
				'address' => $data['user_address'],
				'telephone' => $data['user_telephone'],
				'postcode' => $data['user_postcode'],
				'subdistrict' => $data['user_subdistrict'],
				'city' => $data['user_city'],
				'province' => $data['user_province']
			);
			
			
			$data['shipping_address'] = format_address($address);
			$data['action'] = admin_url('order_shipping/add_history');
			
			// jika order sdh close atau cancel, maka tidak dapat diupdate lagi
			if ($this->config->item('order_complete_status_id') == $data['order_status_id']) {
				$data['update'] = false;
			} elseif ($this->config->item('order_cancel_status_id') == $data['order_status_id']) {
				$data['update'] = false;
			} else {
				$data['update'] = true;
			}
			
			$this->load
			->title('Dropship Order #'.$order_store_id)
			->view('admin/order_shipping_detail', $data);
		} else {
			show_404();
		}
	}
	
	public function history()
	{
		if ($this->input->post(null, true)) {
			$this->load->helper('format');
			$this->load->library('datatables');
		
			$this->datatables
			->select('oh.*, oh.date_added as date_added, os.name as status, CONCAT_WS(" - ", a.name, ag.name) as updater', false)
			->join('order_status os', 'os.order_status_id = oh.order_status_id', 'left')
			->join('admin a', 'a.admin_id = oh.admin_id', 'left')
			->join('admin_group ag', 'ag.admin_group_id = a.admin_group_id', 'left')
			->where('(oh.store_order_id = '.$this->input->post('store_order_id').' AND oh.order_id = '.$this->input->post('order_id').')', null, false)
			->or_where('(oh.order_id = '.$this->input->post('order_id').' AND oh.store_order_id = 0)', null, false)
			->from('order_history oh')
			->edit_column('date_added', '$1', 'format_date(date_added, true)')
			->edit_column('total', '$1', 'format_money(total)');
			
			$this->output->set_output($this->datatables->generate('json'));
		} else {
			show_404();
		}
	}
	
	public function add_history()
	{
		$json = array();
		
		$keys = array(
			'order_id',
			'order_status_id',
			'store_order_id',
			'notify',
			'override',
			'comment'
		);

		foreach ($keys as $key) {
			if ($this->input->post($key)) {
				$$key = $this->input->post($key);
			} else {
				$$key = '';
			}
		}

		$this->load->model('order_model');
		$this->load->model('store_order_model');
		
		if ($this->order_model->get($order_id)) {
			$this->load->model('order_history_model');
			
			$order_history = array(
				'order_id' => (int)$order_id,
				'order_status_id' => (int)$order_status_id,
				'store_order_id' => (int)$store_order_id,
				'admin_id' => (int)$this->admin->admin_id(),
				'notify' => (int)$notify,
				'comment' => $comment
			);
			
			$this->store_order_model->update($store_order_id, array('order_status_id' => $order_status_id));
			$this->order_history_model->insert($order_history);
			
			if ($notify) {
				// send via email or SMS
			}
			
			$json['success'] = 'Status pesanan telah berhasil diperbarui';
		} else {
			$json['error'] = 'Pesanan tidak ditemukan';
		}

		$this->output->set_header('Content-Type: application/json');
		$this->output->set_output(json_encode($json));
	}
}