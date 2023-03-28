<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase extends User_Controller
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
		
		$this->load->helper('format');
		$this->load->model('order_model');
		$this->load->model('order_history_model');
		$this->load->library('form_validation');
	}
	
	/**
	 * Index
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		$data['orders'] = $this->db
		->select('o.order_id, o.order_id, o.total, os.name as status, o.date_added, o.invoice_no, o.order_status_id', false)
		->from('order o')
		->join('order_status os', 'os.order_status_id = o.order_status_id', 'left')
		->where('o.user_id', $this->user->user_id())
		->order_by('o.order_id','desc')
		->get()
		->result_array();
			
		$this->load
		->breadcrumb('Akun', user_url())
		->breadcrumb('Pembelian', user_url('purchase'))
		->view('user/purchase', $data);
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
		$this->load->library('currency');
		
		if ($order = $this->order_model->get_order($order_id, $this->user->user_id())) {
			$this->session->set_userdata('order_id', $order_id);
			if($order['user_id'] != $this->user->user_id()) {
				show_404();
			}

			foreach ($order as $key => $value) {
				$data[$key] = $value;
			}
			
			$data['date_added'] = date('d/m/Y H:i', strtotime($order['date_added']));
			$data['expiry_date'] = date('d/m/Y H:i', strtotime($order['date_added'])+86400);
			$data['products'] = array();
			
			$key = 1;
			
			foreach ($this->order_model->get_order_products($order['order_id']) as $product) {
				$product['price'] = $this->currency->format($product['price']);
				$product['total'] = $this->currency->format($product['total']);
				
				$data['products'][$key] = $product;
				
				$key++;
			}
			
			$data['totals'] = $this->order_model->get_order_totals($order['order_id']);
			
			$shipping_address = array(
				'name' => $data['user_name'],
				'address' => $data['user_address'],
				'telephone' => $data['user_telephone'],
				'postcode' => $data['user_postcode'],
				'subdistrict' => $data['user_subdistrict'],
				'city' => $data['user_city'],
				'province' => $data['user_province']
			);
			
			$data['shipping_address'] = format_address($shipping_address);
			$data['action'] = user_url('purchase/accept');
			$data['waybill'] = $this->order_model->get_waybill($order_id);
			$data['histories'] = $this->order_model->get_histories($order_id);
			$data['order_complete_id'] = $this->config->item('order_complete_status_id');
			$data['flag_order_status_id'] = (int)$this->config->item('order_status_id');

			$data['articles'] = $this->db
				->select('oa.product_code, name, oa.id', false)
				->from('order_product_detail oa')
				->join('product p', 'oa.product_id = p.product_id', 'left')
				->where('oa.order_id', $order_id)
				->order_by('product_code')
				->get()
				->result_array();

			$data['ratings'] = $this->db
				->select('oa.*, name', false)
				->from('order_product_rating oa')
				->join('product p', 'oa.product_id = p.product_id', 'left')
				->where('oa.order_id', $order_id)
				->order_by('name')
				->get()
				->result_array();

			// gunakan Midtrans SNAP
			$this->load->config('midtrans');
			
			$data['snap_action'] = site_url('payment/confirm');
			$data['snap_client_key'] = $this->config->item('client_key');
			$data['snap_js'] = (bool)$this->config->item('is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js';
			//$this->session->set_userdata('order_id', $order_id);
			$payment_code = explode('.', $data['payment_code']);
			$data['payment_code'] = $payment_code[0];
			
			$this->load
			->title('Detil Pesanan #'.$order['invoice_no'])
			->breadcrumb('Akun', user_url())
			->breadcrumb('Pembelian', user_url('purchase'))
			->breadcrumb('Detil Pesanan', user_url('purchase/detail/'.$order['order_id']))
			->view('user/purchase_detail', $data);
		} else {
			show_404();
		}
	}
	
	/**
	 * Accept
	 * 
	 * @access public
	 * @return void
	 */
	public function accept()
	{
		$order_history = array(
			'order_status_id' => (int)$this->config->item('order_complete_status_id'),
			'order_id' => (int)$this->input->post('order_id'),
			'notify' => 1,
			'comment' => 'Barang telah diterima oleh pembeli'
		);
		
		$this->order_history_model->insert($order_history);
			
		$this->order_model->update((int)$this->input->post('order_id'), array(
			'order_status_id' => (int)$this->config->item('order_complete_status_id')
		));
			
		$json['success'] = true;
		
		$this->output->set_output(json_encode($json));
	}
	
	/**
	 * Print or export docs to pdf with spesific type
	 * 
	 * @access public
	 * @return void
	 */
	public function eprint()
	{
		$order_id = $this->input->get('order_id');
		$type = $this->input->get('type');
		
		if ( ! in_array($type, array('invoice_total', 'invoice_tax', 'invoice_no_tax'))) {
			show_404();
		}
		
		$this->load->library('image');
		$this->load->library('pdf');
		$this->load->helper('format');
		
		switch ($type) {
			case 'invoice_total':
				if ($order = $this->order_model->get_order_combined($order_id)) {
					$order['logo'] = str_replace(base_url(), FCPATH, $this->image->resize($this->config->item('logo'), 180));
					$this->pdf->pdf_create($this->load->layout(false)->view('invoices/order_invoice_no_tax_pdf', $order, true), $order['invoice_no'].'-total', true);
				} else {
					show_404();
				}
			break;
			
			case 'invoice_tax':
				if ($order = $this->order_model->get_order_tax($order_id)) {
					$order['logo'] = str_replace(base_url(), FCPATH, $this->image->resize($this->config->item('logo'), 180));
					$this->pdf->pdf_create($this->load->layout(false)->view('invoices/order_invoice_tax_pdf', $order, true), $order['invoice_no'].'-pajak', true);
				} else {
					show_404();
				}
			break;
			
			case 'invoice_no_tax':
				if ($order = $this->order_model->get_order_no_tax($order_id)) {
					$order['logo'] = str_replace(base_url(), FCPATH, $this->image->resize($this->config->item('logo'), 180));
					$this->pdf->pdf_create($this->load->layout(false)->view('invoices/order_invoice_no_tax_pdf', $order, true), $order['invoice_no'].'-non-pajak', true);
				} else {
					show_404();
				}
			break;
			
			default:
				show_404();
			break;
		}
	}

	/**
	 * Claim Warranty
	 * 
	 * @access public
	 * @return void
	 */
	public function claim_warranty()
	{
		$this->load->model('article_model');
		$this->load->model('order_model');
		$this->load->model('warehouse_model');
		$this->load->model('registration_product_model');
		
		$order_product_id = $this->input->get('order_p_id');
		$data['action'] = user_url('purchase/validate_claim_warranty');
		$data['name'] = $this->user->name();
		
		if ($order_product = $this->registration_product_model->get_registration_product_online($order_product_id)) {
			$data['sale_type'] = 'Online';
			$data['sale_id'] = $order_product['id'];
			$data['order'] = $order_product;

			foreach ($this->warehouse_model->get_all_warehouse() as $warehouse) {
				if($warehouse['warehouse_id'] != 2) {
					$data['warehouse_froms'][$warehouse['warehouse_id']] = $warehouse['code'].' ('.$warehouse['name'].')';
				}
			}

			$this->load
				->breadcrumb('Akun', user_url())
				->breadcrumb('Klaim Garansi', user_url('purchase/claim_warranty?order_p_id='.$order_product_id))
				->view('user/purchase_claim_warranty', $data);
		} else {
			redirect(user_url('purchase'), 'refresh');
		}
	}

	/**
	 * Validate
	 * 
	 * @access public
	 * @return void
	 */
	public function validate_claim_warranty()
	{
		$this->load->model('warranty_claim_model');

		$this->load->model('warranty_claim_history_model');
		
		check_ajax();
		
		$json = array();
		
		$this->form_validation
		->set_rules('sale_type', 'Tipe Penjualan', 'trim|required')
		->set_rules('sale_id', 'ID Penjualan', 'trim|required')
		->set_rules('reason', 'Alasan Klaim', 'trim|required')
		->set_rules('claim_description', 'Deskripsi Kerusakan', 'trim|required')
		->set_rules('warehouse_destination', 'Kirim ke Gudang / Distributor', 'trim|required')
		// ->set_rules('invoice_image', 'Bukti Kerusakan', 'trim|required')
		->set_error_delimiters('', '');
		
		if ($this->form_validation->run() == false) {
			foreach ($this->form_validation->get_errors() as $field => $error) {
				$json['errors'][$field] = $error;
			}
		} else {
			$post = $this->input->post(null, false);

			$post['claim_by'] = $this->user->name();
			$post['claim_date'] = date('Y-m-d H:i:s');
			$post['status'] = 'Klaim Baru';
			if(isset($post['invoice_image'])) $post['evidence_photo'] = $post['invoice_image'];
			else $post['evidence_photo'] = '';
			$post['user_email'] = $this->user->email();
			$post['user_phone'] = $this->user->telephone();
			$post['user_address'] = $this->user->address();
			$post['create_source'] = 'user';
			$post['user_id'] = $this->user->user_id();

			//check product exist on warranty or not
			$countArticleActiveWarranty = $this->warranty_claim_model->get_count_article_active_warranty($post['product_code']);
			if($countArticleActiveWarranty == 0) {
				
				$claim_id = $this->warranty_claim_model->create_warranty_claim($post);
				if($claim_id) {
					//insert history
					$history['warranty_claim_id'] = $claim_id;
					$history['process_date'] = $post['claim_date'];
					$history['process_by'] = $post['claim_by'];
					$history['status'] = $post['status'];
					$history['user_name'] = $post['claim_by'];
					$history['user_phone'] = $post['user_phone'];
					$history['user_address'] = $post['user_address'];
					$history_id = $this->warranty_claim_history_model->create_warranty_claim_history($history);

					// notification
					$this->load->model('notification_model');
					$this->notification_model->add_notification(array(
						'title' => 'Klaim Garansi Baru',
						'message' => 'Ada klaim garansi baru oleh customer: '.$post['claim_by'].', kode produk: '.$post['product_code'].', nama produk: '.$post['product_name'].', alasan: '.$post['reason'],
						'table' => 'warranty_claim',
						'table_id' => $claim_id,
						'date_added' => date('Y-m-d H:i:s')
					));
					$this->notification_model->add_notification_distributor(array(
						'title' => 'Klaim Garansi Baru',
						'message' => 'Ada klaim garansi baru oleh customer: '.$post['claim_by'].', kode produk: '.$post['product_code'].', nama produk: '.$post['product_name'].', alasan: '.$post['reason'],
						'table' => 'warranty_claim',
						'table_id' => $claim_id,
						'date_added' => date('Y-m-d H:i:s')
					),$post['warehouse_destination']);
					
					//send email
					$data['logo'] = $this->image->resize($this->config->item('logo'), 120);
					$data['claim_date'] = html_entity_decode(date('d-m-Y',strtotime($post['claim_date'])), ENT_QUOTES, 'UTF-8');
					$data['product_code'] = html_entity_decode($post['product_code'], ENT_QUOTES, 'UTF-8');
					$data['product_name'] = html_entity_decode($post['product_name'], ENT_QUOTES, 'UTF-8');
					$data['name'] = html_entity_decode($post['claim_by'], ENT_QUOTES, 'UTF-8');
					$data['reason'] = html_entity_decode($post['reason'], ENT_QUOTES, 'UTF-8');
					$data['claim_by'] = html_entity_decode($post['claim_by'], ENT_QUOTES, 'UTF-8');
					$data['user_email'] = html_entity_decode($post['user_email'], ENT_QUOTES, 'UTF-8');
					$data['user_phone'] = html_entity_decode($post['user_phone'], ENT_QUOTES, 'UTF-8');

					$this->load->library('email');
					$this->load->config('email');
					$this->email->from($this->config->item('sender'), $this->config->item('site_name'));
					$this->email->to($post['user_email']);
					$this->email->cc($this->config->item('sender'));
					$this->email->subject('Klaim Garansi Baru di MPM WAHL');
					$this->email->message($this->load->layout(false)->view('user/emails/warranty_claim', $data, true));
					$this->email->send();

					$this->load->model('admin_model');
					$adminEmail = array();
					$distributorEmail = array();
					foreach ($this->admin_model->get_admins() as $admin) {
						$adminEmail[] = $admin['email'];
					}
					foreach ($this->admin_model->get_distributors($post['warehouse_destination']) as $admin) {
						$distributorEmail[] = $admin['email'];
					}
					if(count($adminEmail) > 0) {
						$this->email->from($this->config->item('sender'), $this->config->item('site_name'));
						$this->email->to($adminEmail);
						$this->email->cc($this->config->item('sender'));
						$this->email->subject('Klaim Garansi Baru di MPM WAHL');
						$this->email->message($this->load->layout(false)->view('admin/emails/new_claim', $data, true));
						$this->email->send();
					}
					if(count($distributorEmail) > 0) {
						$this->email->from($this->config->item('sender'), $this->config->item('site_name'));
						$this->email->to($distributorEmail);
						$this->email->cc($this->config->item('sender'));
						$this->email->subject('Klaim Garansi Baru di MPM WAHL');
						$this->email->message($this->load->layout(false)->view('admin/emails/new_claim', $data, true));
						$this->email->send();
					}
					$json['success'] = true;
					$json['message'] = 'Klaim garansi berhasil dibuat';
					$this->session->set_flashdata('success', 'Klaim garansi berhasil dibuat');
				}
				else $json['error'] = 'Klaim gagal dibuat';
			}
			else {
				$json['error'] = 'Produk masih dalam proses garansi';
			}
		}
		
		$this->output->set_output(json_encode($json));
	}

	/**
	 * Rating
	 * 
	 * @access public
	 * @return void
	 */
	public function rating()
	{
		$this->load->model('order_model');

		$order_product_id = $this->input->get('order_p_id');
		$data['action'] = user_url('purchase/validate_rating');
		$data['name'] = $this->user->name();
		
		if ($order_product = $this->order_model->get_order_products_rating_by_id($order_product_id)) {
			
			$data['order'] = $order_product;

			$this->load
				->breadcrumb('Akun', user_url())
				->breadcrumb('Rating', user_url('purchase/rating?order_p_id='.$order_product_id))
				->view('user/purchase_rating', $data);
		} else {
			redirect(user_url('purchase'), 'refresh');
		}
	}

	/**
	 * Validate
	 * 
	 * @access public
	 * @return void
	 */
	public function validate_rating()
	{
		$this->load->model('order_model');
		
		check_ajax();
		
		$json = array();
		
		$this->form_validation
		->set_rules('id', 'ID Rating', 'trim|required')
		->set_rules('rating', 'Rating', 'trim|required')
		->set_error_delimiters('', '');
		
		if ($this->form_validation->run() == false) {
			foreach ($this->form_validation->get_errors() as $field => $error) {
				$json['errors'][$field] = $error;
			}
		} else {
			$post = $this->input->post(null, false);

			if(isset($post['invoice_image'])) $invoiceimage = $post['invoice_image'];
			else $invoiceimage = '';
			
			$rating = $this->order_model->get_order_products_rating_by_id($post['id']);
			if ($rating) {
				$this->order_model->update_rating($post['id'], $post['rating'], $post['notes'], $invoiceimage);
				$json['success'] = true;
			}
			else {
				$json['error'] = 'Rating tidak ditemukan';
			}
		}
		
		$this->output->set_output(json_encode($json));
	}
}