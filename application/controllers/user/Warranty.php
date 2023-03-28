<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Warranty extends User_Controller
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
		
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->load->model('article_model');
		$this->load->helper('url');
		$this->load->model('warehouse_model');
		$this->load->model('warranty_claim_model');

		$this->load->model('warranty_claim_history_model');
	}
	
	/**
	 * Index
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		$data['warranties'] = $this->db
		->select('status, claim_date, reason, product_code, product_name, claim_by, it.id as claim_id, concat(gs.code," - ",gs.name) as warehouse, gs.warehouse_id')
		->from('warranty_claim it')
		->join('warehouse gs', 'gs.warehouse_id = it.warehouse_destination', 'left')
		->where('it.user_email', $this->user->email())
		->order_by('claim_date','desc')
		->get()
		->result_array();
			
		$this->load
		->breadcrumb('Akun', user_url())
		->breadcrumb('Klaim Garansi', user_url('warranty'))
		->view('user/warranty', $data);
	}

	/**
	 * Detail
	 * 
	 * @access public
	 * @return void
	 */
	public function detail()
	{
		$claim_id = $this->input->get('claim_id');

		if ($claim = $this->warranty_claim_model->get_warranty_claim($claim_id)) {
			if($claim['user_email'] != $this->user->email()) {
				show_404();
			}

			$data['claim'] = $claim;
			$data['action']	= user_url('warranty/warranty_detail');
			$warehouse = $this->warehouse_model->get_warehouse($claim['warehouse_destination']);
			if($warehouse) {
				$data['warehouse'] = $warehouse['code']." - ".$warehouse['name'];
			}
			else $data['warehouse'] = '';
			$data['histories'] = $this->warranty_claim_history_model->get_warranty_claim_history($claim_id);
			
			$this->load
				->breadcrumb('Akun', user_url())
				->breadcrumb('Klaim Garansi', user_url('warranty'))
				->view('user/warranty_detail', $data);
		} else {
			redirect(user_url('warranty'), 'refresh');
		}
	}

	/**
	 * Warehouse
	 * 
	 * @access public
	 * @return void
	 */
	public function warehouse()
	{
		$warehouse_id = $this->input->get('warehouse_id');
		$json['error'] = "";
		if ($warehouse = $this->warehouse_model->get_warehouse($warehouse_id)) {
			$data['warehouse'] = $warehouse;
		} else {
			$json['error'] = 'Data tidak ditemukan';
		}
		
		if($json['error'] != '') {
			$this->output->set_output(json_encode($json));
		}
		else {
			$this->output->set_output(json_encode(array(
				'content' => $this->load->layout(null)->view('user/warranty_warehouse', $data, true)
			)));
		}
	}

	/**
	 * Finish Claim
	 * 
	 * @access public
	 * @return void
	 */
	public function finish_claim()
	{
		$claim_id = $this->input->get('claim_id');
		$json['error'] = '';
		$json['success'] = '';
		$data = array();

		if ($claim = $this->warranty_claim_model->get_warranty_claim($claim_id)) {
			$updated_by = $this->user->name();
			$updated_date = date('Y-m-d H:i:s');

			$this->warranty_claim_model->update_status_by_customer($claim_id, 'Selesai', $updated_date, $updated_by);
			//insert history
			$history['warranty_claim_id'] = $claim_id;
			$history['process_date'] = $updated_date;
			$history['process_by'] = $updated_by;
			$history['status'] = 'Selesai';
			$history['response_note'] = '';
			$history['response_photo'] = '';
			$history['user_name'] = $this->user->name();
			$history['user_phone'] = $this->user->telephone();
			$history['user_address'] = $this->user->address();
			$history_id = $this->warranty_claim_history_model->create_warranty_claim_history($history);

			// notification
			$this->load->model('notification_model');
			$this->notification_model->add_notification(array(
				'title' => 'Klaim Garansi Selesai',
				'message' => 'Ada klaim garansi selesai oleh customer: '.$updated_by.', kode produk: '.$claim['product_code'].', nama produk: '.$claim['product_name'],
				'table' => 'warranty_claim',
				'table_id' => $claim_id,
				'date_added' => date('Y-m-d H:i:s')
			));
			$this->notification_model->add_notification_distributor(array(
				'title' => 'Klaim Garansi Selesai',
				'message' => 'Ada klaim garansi selesai oleh customer: '.$updated_by.', kode produk: '.$claim['product_code'].', nama produk: '.$claim['product_name'],
				'table' => 'warranty_claim',
				'table_id' => $claim_id,
				'date_added' => date('Y-m-d H:i:s')
			),$claim['warehouse_destination']);
			
			//send email
			$data['logo'] = $this->image->resize($this->config->item('logo'), 120);
			$data['product_code'] = html_entity_decode($claim['product_code'], ENT_QUOTES, 'UTF-8');
			$data['product_name'] = html_entity_decode($claim['product_name'], ENT_QUOTES, 'UTF-8');
			$data['name'] = html_entity_decode($updated_by, ENT_QUOTES, 'UTF-8');

			$this->load->library('email');
			$this->load->config('email');
			$this->email->from($this->config->item('sender'), $this->config->item('site_name'));
			$this->email->to($claim['user_email']);
			$this->email->cc($this->config->item('sender'));
			$this->email->subject('Klaim Garansi Selesai di MPM WAHL');
			$this->email->message($this->load->layout(false)->view('user/emails/warranty_claim_finish', $data, true));
			$this->email->send();

			$this->load->model('admin_model');
			$adminEmail = array();
			$distributorEmail = array();
			foreach ($this->admin_model->get_admins() as $admin) {
				$adminEmail[] = $admin['email'];
			}
			foreach ($this->admin_model->get_distributors($claim['warehouse_destination']) as $admin) {
				$distributorEmail[] = $admin['email'];
			}
			if(count($adminEmail) > 0) {
				$this->email->from($this->config->item('sender'), $this->config->item('site_name'));
				$this->email->to($adminEmail);
				$this->email->cc($this->config->item('sender'));
				$this->email->subject('Klaim Garansi Selesai di MPM WAHL');
				$this->email->message($this->load->layout(false)->view('admin/emails/claim_finish', $data, true));
				$this->email->send();
			}
			if(count($distributorEmail) > 0) {
				$this->email->from($this->config->item('sender'), $this->config->item('site_name'));
				$this->email->to($distributorEmail);
				$this->email->cc($this->config->item('sender'));
				$this->email->subject('Klaim Garansi Selesai di MPM WAHL');
				$this->email->message($this->load->layout(false)->view('admin/emails/claim_finish', $data, true));
				$this->email->send();
			}
			$json['success'] = 'Proses Berhasil';
			$this->session->set_flashdata('success', "Klaim Telah Selesai");

		} else {
			$json['error'] = 'Data Tidak Ditemukan';
		}

		$this->output->set_output(json_encode($json));
	}

	/**
	 * Add
	 * 
	 * @access public
	 * @return void
	 */
	public function add()
	{
		$data = array();

		$data['products'] = $this->db
			->select('product_code, product_name, id', false)
			->from('registration_product')
			->where('user_email', $this->user->email())
			->order_by('product_code')
			->get()
			->result_array();

		$this->load
		->breadcrumb('Akun', user_url())
		->breadcrumb('Klaim Garansi', user_url('warranty/add'))
		->view('user/warranty_add', $data);
	}

	/**
	 * Add Detail
	 * 
	 * @access public
	 * @return void
	 */
	public function add_detail()
	{
		$this->load->model('order_model');
		$this->load->model('warehouse_model');
		$this->load->model('registration_product_model');

		$id = $this->input->get('id');
		$data['action'] = user_url('warranty/validate_add_detail');
		$data['name'] = $this->user->name();

		if ($registrasi = $this->registration_product_model->get_registration_product($id)) {
			if($registrasi['user_email'] != $this->user->email()) {
				show_404();
			}
			
			if($registrasi['register_type'] == 'reg_by_online') {
				$data['sale_type'] = 'Online';
				$data['sale_id'] = $registrasi['id'];
			}
			else {
				$data['sale_type'] = 'Offline';
				$data['sale_id'] = $registrasi['id'];
			}
			$data['product_code'] = $registrasi['product_code'];
			$data['product_name'] = $registrasi['product_name'];
			$data['header_id'] = $registrasi['id'];
			foreach ($this->warehouse_model->get_all_warehouse() as $warehouse) {
				if($warehouse['warehouse_id'] != 2) {
					$data['warehouse_froms'][$warehouse['warehouse_id']] = $warehouse['code'].' ('.$warehouse['name'].')';
				}
			}
		} else {
			redirect(user_url('warranty'), 'refresh');
		}

		$this->load
		->breadcrumb('Akun', user_url())
		->breadcrumb('Klaim Garansi', user_url('warranty'))
		->view('user/warranty_add_detail', $data);
	}

	/**
	 * Validate
	 * 
	 * @access public
	 * @return void
	 */
	public function validate_add_detail()
	{
		$this->load->model('registration_product_model');
		
		check_ajax();
		
		$json = array();
		
		$this->form_validation
		->set_rules('sale_type', 'Tipe Penjualan', 'trim|required')
		->set_rules('sale_id', 'ID Penjualan', 'trim|required')
		->set_rules('product_code', 'Kode Produk', 'trim|required')
		->set_rules('product_name', 'Nama Produk', 'trim|required')
		->set_rules('reason', 'Alasan Klaim', 'trim|required')
		->set_rules('claim_description', 'Deskripsi Kerusakan', 'trim|required')
		->set_rules('warehouse_destination', 'Kirim ke Gudang / Distributor', 'trim|required')
		->set_error_delimiters('', '');
		
		if ($this->form_validation->run() == false) {
			foreach ($this->form_validation->get_errors() as $field => $error) {
				$json['errors'][$field] = $error;
			}
		} else {
			$post = $this->input->post(null, false);

			$registrasi = $this->registration_product_model->get_registration_product($post['header_id']);
			if(strtotime($registrasi['start_warranty']) > strtotime(date('Y-m-d'))) {
				$json['warning'] = 'Produk belum masuk masa garansi';
			}

			if(!$json) {
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
		}
		
		$this->output->set_output(json_encode($json));
	}

	/**
	 * Transfer bank
	 * 
	 * @access public
	 * @return void
	 */
	public function transfer_bank()
	{
		$claim_id = $this->input->get('claim_id');
		$json['error'] = "";
		if ($claim = $this->warranty_claim_model->get_warranty_claim($claim_id)) {
			$data['claim'] = $claim;
			$data['transfer_bank'] = $this->config->item('warranty_transfer');
		} else {
			$json['error'] = 'Data tidak ditemukan';
		}
		
		if($json['error'] != '') {
			$this->output->set_output(json_encode($json));
		}
		else {
			$this->output->set_output(json_encode(array(
				'content' => $this->load->layout(null)->view('user/warranty_transfer_bank', $data, true)
			)));
		}
	}

	/**
	 * Stop Claim
	 * 
	 * @access public
	 * @return void
	 */
	public function stop_claim()
	{
		$claim_id = $this->input->get('claim_id');
		$json['error'] = '';
		$json['success'] = '';
		$data = array();

		if ($claim = $this->warranty_claim_model->get_warranty_claim($claim_id)) {
			$updated_by = $this->user->name();
			$updated_date = date('Y-m-d H:i:s');

			$this->warranty_claim_model->update_status_by_customer($claim_id, 'Klaim Tidak Dilanjutkan Customer Dan Barang Dikembalikan Ke Customer', $updated_date, $updated_by);
			//insert history
			$history['warranty_claim_id'] = $claim_id;
			$history['process_date'] = $updated_date;
			$history['process_by'] = $updated_by;
			$history['status'] = 'Klaim Tidak Dilanjutkan Customer Dan Barang Dikembalikan Ke Customer';
			$history['response_note'] = '';
			$history['response_photo'] = '';
			$history['user_name'] = $this->user->name();
			$history['user_phone'] = $this->user->telephone();
			$history['user_address'] = $this->user->address();
			$history_id = $this->warranty_claim_history_model->create_warranty_claim_history($history);

			// notification
			$this->load->model('notification_model');
			$this->notification_model->add_notification(array(
				'title' => 'Klaim Garansi Tidak Dilanjutkan Customer',
				'message' => 'Ada klaim garansi tidak dilanjutkan oleh customer: '.$updated_by.', kode produk: '.$claim['product_code'].', nama produk: '.$claim['product_name'],
				'table' => 'warranty_claim',
				'table_id' => $claim_id,
				'date_added' => date('Y-m-d H:i:s')
			));
			$this->notification_model->add_notification_distributor(array(
				'title' => 'Klaim Garansi Tidak Dilanjutkan Customer',
				'message' => 'Ada klaim garansi tidak dilanjutkan oleh customer: '.$updated_by.', kode produk: '.$claim['product_code'].', nama produk: '.$claim['product_name'],
				'table' => 'warranty_claim',
				'table_id' => $claim_id,
				'date_added' => date('Y-m-d H:i:s')
			),$claim['warehouse_destination']);
			
			//send email
			$data['logo'] = $this->image->resize($this->config->item('logo'), 120);
			$data['product_code'] = html_entity_decode($claim['product_code'], ENT_QUOTES, 'UTF-8');
			$data['product_name'] = html_entity_decode($claim['product_name'], ENT_QUOTES, 'UTF-8');
			$data['name'] = html_entity_decode($updated_by, ENT_QUOTES, 'UTF-8');

			$this->load->library('email');
			$this->load->config('email');
			$this->email->from($this->config->item('sender'), $this->config->item('site_name'));
			$this->email->to($claim['user_email']);
			$this->email->cc($this->config->item('sender'));
			$this->email->subject('Klaim Garansi Tidak Dilanjutkan di MPM WAHL');
			$this->email->message($this->load->layout(false)->view('user/emails/warranty_claim_stop_customer', $data, true));
			$this->email->send();

			$this->load->model('admin_model');
			$adminEmail = array();
			$distributorEmail = array();
			foreach ($this->admin_model->get_admins() as $admin) {
				$adminEmail[] = $admin['email'];
			}
			foreach ($this->admin_model->get_distributors($claim['warehouse_destination']) as $admin) {
				$distributorEmail[] = $admin['email'];
			}
			if(count($adminEmail) > 0) {
				$this->email->from($this->config->item('sender'), $this->config->item('site_name'));
				$this->email->to($adminEmail);
				$this->email->cc($this->config->item('sender'));
				$this->email->subject('Klaim Garansi Tidak Dilanjutkan di MPM WAHL');
				$this->email->message($this->load->layout(false)->view('admin/emails/claim_stop_customer', $data, true));
				$this->email->send();
			}
			if(count($distributorEmail) > 0) {
				$this->email->from($this->config->item('sender'), $this->config->item('site_name'));
				$this->email->to($distributorEmail);
				$this->email->cc($this->config->item('sender'));
				$this->email->subject('Klaim Garansi Tidak Dilanjutkan di MPM WAHL');
				$this->email->message($this->load->layout(false)->view('admin/emails/claim_stop_customer', $data, true));
				$this->email->send();
			}
			$json['success'] = 'Proses Berhasil';
			$this->session->set_flashdata('success', "Klaim Tidak Dilanjutkan");

		} else {
			$json['error'] = 'Data Tidak Ditemukan';
		}

		$this->output->set_output(json_encode($json));
	}

	/**
	 * Transfer done
	 * 
	 * @access public
	 * @return void
	 */
	public function transfer_done()
	{
		$claim_id = $this->input->get('claim_id');

		if ($claim = $this->warranty_claim_model->get_warranty_claim($claim_id)) {
			$data['claim'] = $claim;
			$data['action']	= user_url('warranty/validate_transfer_done');
			
			$this->load
				->breadcrumb('Akun', user_url())
				->breadcrumb('Klaim Garansi', user_url('warranty'))
				->view('user/warranty_transfer_done', $data);
		} else {
			redirect(user_url('warranty'), 'refresh');
		}
	}

	/**
	 * Validate
	 * 
	 * @access public
	 * @return void
	 */
	public function validate_transfer_done()
	{
		check_ajax();
		
		$json = array();
		
		$this->form_validation
		->set_rules('id', 'ID', 'trim|required')
		->set_rules('invoice_image', 'Bukti Transfer', 'trim|required')
		->set_error_delimiters('', '');
		
		if ($this->form_validation->run() == false) {
			foreach ($this->form_validation->get_errors() as $field => $error) {
				$json['errors'][$field] = $error;
			}
		} else {
			$post = $this->input->post(null, false);
			$updated_by = $this->user->name();
			$updated_date = date('Y-m-d H:i:s');

			if ($claim = $this->warranty_claim_model->get_warranty_claim($post['id'])) {
				if(isset($post['invoice_image'])) $invoiceimage = $post['invoice_image'];
				else $invoiceimage = '';
				$this->warranty_claim_model->update_claim_transfer_done($post['id'], $invoiceimage, 'Customer Telah Melakukan Pembayaran Dan Menunggu Konfirmasi', $updated_date, $updated_by);

				//insert history
				$history['warranty_claim_id'] = $post['id'];
				$history['process_date'] = $updated_date;
				$history['process_by'] = $updated_by;
				$history['status'] = 'Customer Telah Melakukan Pembayaran Dan Menunggu Konfirmasi';
				$history['response_note'] = '';
				$history['response_photo'] = '';
				
				$history_id = $this->warranty_claim_history_model->create_warranty_claim_history($history);

				// notification
				$this->load->model('notification_model');
				$this->notification_model->add_notification(array(
					'title' => 'Perubahan Status Klaim Garansi Produk '.$post['product_code'],
					'message' => 'Ada perubahan status klaim garansi oleh user: '.$history['process_by'].', kode serial produk: '.$post['product_code'].', nama produk: '.$post['product_name'].', status dari "'.$claim['status'].'" menjadi "Customer Telah Melakukan Pembayaran Dan Menunggu Konfirmasi"',
					'table' => 'warranty_claim',
					'table_id' => $post['id'],
					'date_added' => date('Y-m-d H:i:s')
				));
				$this->notification_model->add_notification_distributor(array(
					'title' => 'Perubahan Status Klaim Garansi Produk '.$post['product_code'],
					'message' => 'Ada perubahan status klaim garansi oleh user: '.$history['process_by'].', kode serial produk: '.$post['product_code'].', nama produk: '.$post['product_name'].', status dari "'.$claim['status'].'" menjadi "Customer Telah Melakukan Pembayaran Dan Menunggu Konfirmasi"',
					'table' => 'warranty_claim',
					'table_id' => $post['id'],
					'date_added' => date('Y-m-d H:i:s')
				), $claim['warehouse_destination']);

				//send email
				$data['logo'] = $this->image->resize($this->config->item('logo'), 120);
				$data['name'] = html_entity_decode($claim['claim_by'], ENT_QUOTES, 'UTF-8');
				$data['product_code'] = html_entity_decode($post['product_code'], ENT_QUOTES, 'UTF-8');
				$data['product_name'] = html_entity_decode($post['product_name'], ENT_QUOTES, 'UTF-8');
				$data['status'] = html_entity_decode($claim['status'], ENT_QUOTES, 'UTF-8');
				$data['newstatus'] = html_entity_decode('Customer Telah Melakukan Pembayaran Dan Menunggu Konfirmasi', ENT_QUOTES, 'UTF-8');

				$this->load->library('email');
				$this->load->config('email');

				if($claim['create_source'] != 'admin') {
					//to customer
					$this->email->from($this->config->item('sender'), $this->config->item('site_name'));
					$this->email->to($claim['user_email']);
					$this->email->cc($this->config->item('sender'));
					$this->email->subject('Perubahan Status Klaim Garansi, Produk: '.html_entity_decode($post['product_code'].' - '.$post['product_name'].' di MPM WAHL', ENT_QUOTES, 'UTF-8'));
					$this->email->message($this->load->layout(false)->view('user/emails/warranty_claim_status', $data, true));
					$this->email->send();
				}
				
				//to admin/distributor
				$this->load->model('admin_model');
				$adminEmail = array();
				$distributorEmail = array();
				foreach ($this->admin_model->get_admins() as $admin) {
					$adminEmail[] = $admin['email'];
				}
				foreach ($this->admin_model->get_distributors($claim['warehouse_destination']) as $admin) {
					$distributorEmail[] = $admin['email'];
				}
				if(count($adminEmail) > 0) {
					$this->email->from($this->config->item('sender'), $this->config->item('site_name'));
					$this->email->to($adminEmail);
					$this->email->cc($this->config->item('sender'));
					$this->email->subject('Perubahan Status Klaim Garansi, Produk: '.html_entity_decode($post['product_code'].' - '.$post['product_name'].' di MPM WAHL', ENT_QUOTES, 'UTF-8'));
					$this->email->message($this->load->layout(false)->view('admin/emails/claim_status', $data, true));
					$this->email->send();
				}
				if(count($distributorEmail) > 0) {
					$this->email->from($this->config->item('sender'), $this->config->item('site_name'));
					$this->email->to($distributorEmail);
					$this->email->cc($this->config->item('sender'));
					$this->email->subject('Perubahan Status Klaim Garansi, Produk: '.html_entity_decode($post['product_code'].' - '.$post['product_name'].' di MPM WAHL', ENT_QUOTES, 'UTF-8'));
					$this->email->message($this->load->layout(false)->view('admin/emails/claim_status', $data, true));
					$this->email->send();
				}
				
				$json['success'] = true;
				$json['message'] = 'Klaim garansi berhasil diperbarui';
				$this->session->set_flashdata('success', 'Klaim garansi berhasil diperbarui');
			}
			else {
				$json['error'] = 'Klaim tidak ditemukan';
			}
		}
		
		$this->output->set_output(json_encode($json));
	}
}