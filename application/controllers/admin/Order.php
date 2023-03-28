<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends Admin_Controller
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
		
		$this->load->library('currency');
		$this->load->library('form_validation');
		
		$this->load->helper('form');
		$this->load->helper('language');
		
		$this->load->model('order_model');
		$this->load->model('address_model');
		$this->load->model('registration_product_model');
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
			$this->load->helper('format');
			$this->load->library('datatables');
		
			$this->datatables
			->select('o.order_id, o.invoice_no, o.date_added, o.user_name, o.total, os.name as status, o.order_status_id', false)
			->join('order_status os', 'os.order_status_id = o.order_status_id', 'left')
			->from('order o')
			->edit_column('date_added', '$1 WIB', 'format_date(date_added, true)')
			->edit_column('total', '$1', 'format_money(total)');
			
			$this->output->set_output($this->datatables->generate('json'));
		} else {
			if ( ! $this->admin->has_permission()) {
				show_401($this->uri->uri_string());
			}
			
			$data['success'] = $this->session->userdata('success');
			$data['error'] = $this->session->userdata('error');
			
			$this->load
			->title('Pesanan')
			->view('admin/order', $data);
		}
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
		
		$order_id = $this->input->post('order_id');
		
		if ( ! $this->admin->has_permission()) {
			$json['error'] = lang('admin_error_delete');
		}
		
		if (empty($json['error'])) {
			$this->order_model->delete($order_id);
			$json['success'] = 'Berhasil menghapus pesanan!';
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	/**
	 * Detail
	 * 
	 * @access public
	 * @param int $order_id
	 * @return void
	 */
	public function detail($order_id = null)
	{
		$this->load->helper('format');
		
		if ($order = $this->order_model->get_order($order_id)) {
			foreach ($order as $key => $value) {
				$data[$key] = $value;
			}
			
			$this->load->model('order_status_model');
			$order_status = $this->order_status_model->get($data['order_status_id']);
			
			$data['order_statuses'] = $this->order_status_model->get_all();
			$data['total'] = $this->currency->format($data['total']);
			$data['status']	= $order_status ? $order_status['name'] : '';
			$data['date_added'] = date('d/m/Y H:i', strtotime($order['date_added']));
			$data['expiry_date'] = date('d/m/Y H:i', strtotime($order['date_added'])+86400);
			$data['products'] = array();
			$data['order_complete_id'] = $this->config->item('order_complete_status_id');
			$data['order_deliver_id'] = $this->config->item('order_delivered_status_id');
			$data['order_status_id_default'] = $this->config->item('order_status_id');
			$data['order_status_id_current'] = $order['order_status_id'];
			$key = 1;
			
			foreach ($this->order_model->get_order_products($order_id) as $product) {
				$product['price'] = format_money($product['price']);
				$product['total'] = format_money($product['total']);
				
				$data['products'][$key] = $product;
				
				$key++;
			}
			
			$data['totals'] = $this->order_model->get_order_totals($order_id);
			
			if ($this->input->get('ref')) {
				$data['back'] = admin_url($this->input->get('ref'));
			} else {
				$data['back'] = admin_url('sale/shipping');
			}
			
			$address = array(
				'name'		=> $data['user_name'],
				'address'	=> $data['user_address'],
				'telephone'	=> $data['user_telephone'],
				'postcode'	=> $data['user_postcode'],
				'subdistrict' => $data['user_subdistrict'],
				'city'		=> $data['user_city'],
				'province'	=> $data['user_province']
			);
			
			$data['shipping_address'] = format_address($address);
			
			$data['action'] = admin_url('order/add_history');
			$data['product_action'] = admin_url('order/update_product');
			
			$order_history = $this->db
			->select('o.shipping_code, oh.waybill')
			->from('order_history oh')
			->join('order o', 'o.order_id = oh.order_id', 'left')
			->where('oh.order_id', $order_id)
			->where('oh.waybill !=', '')
			->order_by('oh.order_history_id', 'desc')
			->get()
			->row_array();
			
			if ($order_history) {
				$data['waybill'] = $order_history['waybill'];
			} else {
				$data['waybill'] = false;
			}
			
			$data['order_product_details'] = $this->order_model->get_order_products_detail($order_id);

			// notification
			$this->load->model('notification_model');
			$this->notification_model->read_notification($this->admin->admin_id(), 'order', $order_id);
			
			$this->load
			->title('Detil Order #'.$order_id)
			->view('admin/order_detail', $data);
		} else {
			show_404();
		}
	}
	
	/**
	 * Fetch order hitory
	 * 
	 * @access public
	 * @return json
	 */
	public function history()
	{
		if ($this->input->post(null, true)) {
			$this->load->helper('format');
			$this->load->library('datatables');
		
			$this->datatables
			->select('oh.*, oh.date_added as date_added, os.name as status, (CASE WHEN a.name IS NOT NULL THEN CONCAT_WS(" - ", a.name, ag.name) ELSE "System" END) as updater', false)
			->join('order_status os', 'os.order_status_id = oh.order_status_id', 'left')
			->join('admin a', 'a.admin_id = oh.admin_id', 'left')
			->join('admin_group ag', 'ag.admin_group_id = a.admin_group_id', 'left')
			->where('oh.order_id', $this->input->post('order_id'))
			->from('order_history oh')
			->edit_column('date_added', '$1', 'format_date(date_added, true)')
			->edit_column('total', '$1', 'format_money(total)');
			
			$this->output->set_output($this->datatables->generate('json'));
		} else {
			show_404();
		}
	}
	
	/**
	 * Add history
	 * 
	 * @access public
	 * @return void
	 */
	public function add_history()
	{
		$json = array();
		
		$keys = array(
			'order_id',
			'order_status_id',
			'notify',
			'override',
			'comment',
			'waybill'
		);

		foreach ($keys as $key) {
			if ($this->input->post($key)) {
				$$key = $this->input->post($key);
			} else {
				$$key = '';
			}
		}
		
		// check product if status is dikirim
		if ($order_status_id == (int)$this->config->item('order_delivered_status_id') || $order_status_id == (int)$this->config->item('order_complete_status_id')) {
			$order_product_details = $this->order_model->get_order_products_detail($order_id);
			$counter = 0;
			foreach($order_product_details as $detail) {
				if($detail['product_code'] == '' || $detail['product_code'] == null) {
					$json['error'] = 'Daftar Produk Untuk Pesanan Harus Diisi Sebelum Dikirim';
					break;
				}
				if($detail['product_code'] == 'W') $counter++;
			}
			if($counter == count($order_product_details)) {
				$json['error'] = 'Daftar Produk Untuk Pesanan Harus Diisi Sebelum Dikirim';
			}
		}

		if(!$json) {
			// check product if status is selesai
			if ($order_status_id == (int)$this->config->item('order_complete_status_id')) {
				//insert registration product
				$order_data = $this->order_model->get_order($order_id);
				if($order_data["user_id"] != 0) {
					$order_product_details = $this->order_model->get_order_products_detail($order_id);
					foreach($order_product_details as $detail) {
						// insert to registration product
						$this->registration_product_model->create_registration_product_order($order_data, $detail);
					}
				}
			}
		}
		
		if(!$json) {
			$this->load->model('order_history_model');
			
			$order_history = array(
				'order_id' => (int)$order_id,
				'order_status_id' => (int)$order_status_id,
				'admin_id' => (int)$this->admin->admin_id(),
				'notify' => (int)$notify,
				'comment' => $comment,
				'waybill' => $waybill
			);
				
			$this->order_model->update($order_id, array('order_status_id' => $order_status_id));
			$this->order_history_model->insert($order_history);
			
			// update if status is confirmed
			$this->load->model('article_model');
			$this->load->model('order_article_model');
	
			// update if status is dikirim
			if ($order_status_id == (int)$this->config->item('order_delivered_status_id')) {
				$this->order_model->update_stock($order_id);
			}
	
			if ($data = $this->order_model->get_order($order_id)) {
				if ($notify) {
					$data['products'] = array();
				
					foreach ($this->order_model->get_order_products($order_id) as $order_product) {
						$data['products'][] = array(
							'name' => $order_product['name'],
							'quantity' => $order_product['quantity'],
							'price' => $this->currency->format($order_product['price']),
							'total' => $this->currency->format($order_product['total'])
						);
					}
				
					$data['logo'] = $this->image->resize($this->config->item('logo'), 200);
					$data['order_id'] = $order_id;
					$data['date_added'] = date('d/m/Y', strtotime($data['date_added']));
					$data['comment'] = nl2br($comment);
					
					$this->load->helper('format');
					
					$address['name'] = $data['user_name'];
					$address['address'] = $data['user_address'];
					$address['telephone'] = $data['user_telephone'];
					$address['postcode'] = $data['user_postcode'];
					$address['subdistrict'] = $data['user_subdistrict'];
					$address['city'] = $data['user_city'];
					$address['province'] = $data['user_province'];
					
					$data['address'] = format_address($address);
					$data['totals'] = $this->order_model->get_order_totals($order_id);
					
					if ($waybill) {
						$data['waybill'] = $waybill;
					} else {
						$order_history = $this->db
						->select('o.shipping_code, oh.waybill')
						->from('order_history oh')
						->join('order o', 'o.order_id = oh.order_id', 'left')
						->where('oh.order_id', $order_id)
						->where('oh.waybill !=', '')
						->order_by('oh.order_history_id', 'desc')
						->get()
						->row_array();
						
						if ($order_history) {
							$data['waybill'] = $order_history['waybill'];
						} else {
							$data['waybill'] = false;
						}
					}
					
					$data['tracking'] = site_url('tracking/check/'.$data['invoice_no']);
					
					$this->load->library('email');
					$this->load->config('email');
					
					$this->email->from($this->config->item('sender'), $this->config->item('site_name'));
					$this->email->to($data['user_email']);
					$this->email->cc($this->config->item('email'));
					$this->email->subject('Update Status Pesanan');
					$this->email->message($this->load->layout(false)->view('emails/order_status', $data, true));
					$this->email->send();
				}
				
				$json['success'] = 'Status pesanan telah berhasil diperbarui';
				$json['newstatus'] = $order_status_id;
				$json['completestatus'] = $this->config->item('order_complete_status_id');
				
				$this->session->set_flashdata('success', 'Status pesanan telah berhasil diperbarui');
			} else {
				$json['error'] = 'Pesanan tidak ditemukan';
			}
		}

		$this->output->set_header('Content-Type: application/json');
		$this->output->set_output(json_encode($json));
	}
	
	/**
	 * Print or export docs to pdf with spesific type
	 * 
	 * @access public
	 * @param string $type Invoice, delivery note
	 * @param int $order_id
	 * @return void
	 */
	public function eprint($type = false, $order_id = null)
	{
		$stream = true;
		
		if ( ! in_array($type, array('invoice', 'delivery'))) {
			show_404();
		}
		
		$this->load->helper('format');
		$this->load->library('currency');
		$this->load->library('pdf');
		
		if ($order = $this->order_model->get_order($order_id)) {
			foreach ($order as $key => $value) {
				$data[$key] = $value;
			}
			
			$data['date_added'] = date('d/m/Y', strtotime($order['date_added']));
			$data['products'] = array();
			
			$key = 1;
			
			foreach ($this->order_model->get_order_products($order_id) as $product) {
				$product['price'] = format_money($product['price']);
				$product['total'] = format_money($product['total']);
				
				$data['products'][$key] = $product;
				
				$key++;
			}
			
			$data['totals'] = $this->order_model->get_order_totals($order_id);
			
			$shipping_address = array(
				'name' => $data['user_name'],
				'address' => $data['user_address'],
				'telephone'	=> $data['user_telephone'],
				'postcode' => $data['user_postcode'],
				'subdistrict' => $data['user_subdistrict'],
				'city' => $data['user_city'],
				'province' => $data['user_province']
			);
			
			$data['shipping_address'] = format_address($shipping_address);
		} else {
			show_404();
		}
		
		$this->load->library('image');
		
		$data['logo'] = str_replace(base_url(), FCPATH, $this->image->resize($this->config->item('logo'), 120));
		
		switch ($type) {
			case 'invoice':
				$filename = APPPATH.'cache/docs/invoice-'.$order_id.'.pdf';
				if ( ! $stream) {
					$this->pdf->pdf_create($this->load->layout(false)->view('admin/order_invoice_pdf', $data, true), $filename, false);
					return file_exists($filename) ? $filename : false;
				} else {
					$this->pdf->pdf_create($this->load->layout(false)->view('admin/order_invoice_pdf', $data, true), 'invoice-'.$order_id, true);
				}
			break;
			
			case 'delivery':
				$filename = APPPATH.'cache/docs/delivery-'.$order_id.'.pdf';
				if ( ! $stream) {
					$this->pdf->pdf_create($this->load->layout(false)->view('admin/order_delivery_pdf', $data, true), $filename, false);
					return file_exists($filename) ? $filename : false;
				} else {
					$this->pdf->pdf_create($this->load->layout(false)->view('admin/order_delivery_pdf', $data, true), 'delivery-'.$order_id, true);
				}
			break;
			
			default:
				show_404();
			break;
		}
	}

	/**
	 * Fetch order article
	 * 
	 * @access public
	 * @return json
	 */
	public function article()
	{
		if ($this->input->post(null, true)) {
			$this->load->helper('format');
			$this->load->library('datatables');
		
			$this->datatables
			->select('oa.*, p.name', false)
			->join('product p', 'p.product_id = oa.product_id', 'left')
			->where('oa.order_id', $this->input->post('order_id'))
			->from('order_product_detail oa')
			->order_by('oa.id');
			
			$this->output->set_output($this->datatables->generate('json'));
		} else {
			show_404();
		}
	}

	/**
	 * Update product
	 * 
	 * @access public
	 * @return json
	 */
	public function update_product() 
	{
		$this->load->model('reg_sale_offline_model');

		$json = array();
		
		$order_id = $this->input->post('order_id');

		if ($this->input->post('product_code')) {
			$inventories = $this->input->post('product_code');
		} else {
			$inventories = array();
		}
		
		if (count($inventories) < 1) {
			$json['warning'] = 'Kode Produk harus diisi';
		}

		foreach ($inventories as $inventory) {
			if($inventory == '') {
				$json['warning'] = 'Kode Produk harus diisi';
				break;
			}
			$counter = 0;
			foreach($inventories as $inventori) {
				if($inventory == $inventori) $counter++;
			}
			if($counter > 1) {
				$json['warning'] = 'Produk '.$inventory.' diinput lebih dari satu kali';
				break;
			}
			if($this->reg_sale_offline_model->check_product_reg_sale_offline($inventory) > 0) {
				$json['warning'] = 'Produk '.$inventory.' sudah pernah diregistrasi';
				break;
			}
			if($this->order_model->check_product_order_product_detail($inventory, $order_id, false) > 0) {
				$json['warning'] = 'Produk '.$inventory.' sudah pernah dipakai di pesanan';
				break;
			}
			if($this->registration_product_model->check_product_registration_product($inventory) > 0) {
				$json['warning'] = 'Produk '.$inventory.' sudah pernah diregistrasikan';
				break;
			}
		}

		if (!$json) {
			$counter = 0;

			$product_details = $this->db
					->select('oa.*', false)
					->where('oa.order_id', $order_id)
					->from('order_product_detail oa')
					->order_by('oa.id')
					->get()
					->result_array();

			foreach($product_details as $detail) {
				$counter2 = 0;
				$productcode = '';
				foreach ($inventories as $inventory) {
					if($counter == $counter2) $productcode = $inventory;
					$counter2++;
				}

				$this->order_model->update_order_product_detail($detail['id'], $productcode);
				$counter++;
			}
			
			$json['success'] = 'Update Produk berhasil';
		}

		$this->output->set_output(json_encode($json));
	}
} 