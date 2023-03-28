<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Warranty_claim extends Admin_Controller
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

		$this->load->model('warranty_claim_model');

		$this->lang->load('warranty_claim');

		$this->load->model('article_model');

		$this->load->model('warehouse_model');

		$this->load->model('warranty_claim_history_model');

		$this->load->model('registration_product_model');

		$this->load->model('registration_product_history_model');

		$this->load->model('reg_sale_offline_model');

		$this->load->model('order_model');
	}
	
	/**
	 * Index
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		$claim_id = $this->input->get('claim_id');
		if($claim_id) {
			// notification
			$this->load->model('notification_model');
			$this->notification_model->read_notification($this->admin->admin_id(), 'warranty_claim', $claim_id);
		}

		if ($this->input->post(null, true)) {
			$this->load->library('datatables');
			
			$this->datatables
			->select('status, claim_date, reason, product_code, product_name, claim_by, it.id as claim_id, concat(gs.code," - ",gs.name) as warehouse, it.user_id as user_id')
			->join('warehouse gs', 'gs.warehouse_id = it.warehouse_destination', 'left')
			->from('warranty_claim it')
			->order_by('it.id desc');

			if ($this->input->post('status')) {
				$this->datatables->where('status', $this->input->post('status'));
			}
			if($this->admin->warehouse_id() != 0) {
				$this->datatables->where('it.warehouse_destination', $this->admin->warehouse_id());
			}

			$this->datatables
			->edit_column('claim_date', '$1', 'format_date(claim_date)');

			$this->output->set_output($this->datatables->generate('json'));
		} else {
			// if ( ! $this->admin->has_permission()) {
			// 	show_401($this->uri->uri_string());
			// }
			
			$this->load
			->title(lang('heading_title'))
			->view('admin/warranty_claim');
		}
	}

	/**
	 * Information
	 * 
	 * @access public
	 * @return void
	 */
	public function information()
	{
		$claim_id = $this->input->get('claim_id');

		$json['error'] = '';
		
		if ($claim = $this->warranty_claim_model->get_warranty_claim($claim_id)) {
			$data['claim'] = $claim;
			$data['action']	= admin_url('warranty_claim/information');
			$warehouse = $this->warehouse_model->get_warehouse($claim['warehouse_destination']);
			if($warehouse) {
				$data['warehouse'] = $warehouse['code']." - ".$warehouse['name'];
			}
			else $data['warehouse'] = '';
			$data['histories'] = $this->warranty_claim_history_model->get_warranty_claim_history($claim_id);
		} else {
			$json['error'] = 'Data tidak ditemukan';
		}
		
		if($json['error'] != '') {
			$this->output->set_output(json_encode($json));
		}
		else {
			$this->output->set_output(json_encode(array(
				'content' => $this->load->layout(null)->view('admin/warranty_claim_info', $data, true)
			)));
		}
	}

	/**
	 * Change status
	 * 
	 * @access private
	 * @return void
	 */
	public function change_status()
	{
		$claim_id = $this->input->get('claim_id');

		$json = array();

		$json['error'] = '';
		
		$data['action']	= admin_url('warranty_claim/validate_change_status');
		
		$data['heading_title']	= 'Ubah Status Klaim Garansi';

		if ($claim = $this->warranty_claim_model->get_warranty_claim($claim_id)) {
			$data['claim'] = $claim;

		} else {
			$json['error'] = 'Data tidak ditemukan';
		}
		
		if($json['error'] != '') {
			$this->output->set_output(json_encode($json));
		}
		else {
			$this->output->set_output(json_encode(array(
				'content' => $this->load->layout(null)->view('admin/warranty_claim_status', $data, true, true)
			)));
		}
	}

	/**
	 * Validate change status form
	 * 
	 * @access public
	 * @return json
	 */
	public function validate_change_status()
	{
		$json = array();
		
		$this->form_validation
		->set_rules('status', 'Status', 'trim|required')
		->set_rules('id', 'Klaim', 'trim|required')
		->set_error_delimiters('', '');
		
		if ($this->form_validation->run() == false) {
			foreach ($this->form_validation->get_errors() as $field => $error) {
				$json['error'][$field] = $error;
			}
		} else {
			$post = $this->input->post(null, false);
			$updated_by = $this->admin->name();
			$updated_date = date('Y-m-d H:i:s');

			//check if status = 'Ditolak', reason required
			$json['warning'] = '';
			if($post['status'] == 'Klaim Ditolak' || $post['status'] == 'Klaim Tidak Disetujui Dan Barang Dikembalikan Ke Customer' || $post['status'] == 'Klaim Dikenakan Biaya Dan Menunggu Pembayaran Dari Customer') {
				if($post['response_note'] == '') $json['warning'] = 'Respon Klaim harus diisi';
			}

			//check if status = 'Klaim Disetujui Dan Sedang Diproses', check new product code if filled
			if($post['old_status'] == 'Klaim Disetujui Dan Sedang Diproses') {
				if(isset($post['new_product_code'])) {
					if($post['new_product_code'] != 'W' && $post['new_product_code'] != '') {
						//check new code
						if($this->reg_sale_offline_model->check_product_reg_sale_offline($post['new_product_code']) > 0) {
							$json['warning'] = 'Produk '.$post['new_product_code'].' sudah pernah diregistrasi';
						}
						if($this->order_model->check_product_order_product_detail($post['new_product_code'], '', true) > 0) {
							$json['warning'] = 'Produk '.$post['new_product_code'].' sudah pernah dipakai di pesanan';
						}
						if($this->registration_product_model->check_product_registration_product($post['new_product_code']) > 0) {
							$json['warning'] = 'Produk '.$post['new_product_code'].' sudah pernah diregistrasikan';
						}
					}
				}
			}

			if($json['warning'] == '') {
				if ($claim = $this->warranty_claim_model->get_warranty_claim($post['id'])) {
					if(isset($post['invoice_image'])) $invoiceimage = $post['invoice_image'];
					else $invoiceimage = '';
					$this->warranty_claim_model->update_status($post['id'], $post['status'], $updated_date, $updated_by, $post['response_note'], $invoiceimage);

					if($post['old_status'] == 'Klaim Disetujui Dan Sedang Diproses') {
						if(isset($post['new_product_code'])) {
							if($post['new_product_code'] != 'W' && $post['new_product_code'] != '') {
								//update warranty
								$this->warranty_claim_model->update_new_product_code($post['id'], $post['new_product_code']);
								//add registration product history
								$registrasi = $this->registration_product_model->get_registration_product_by_product_code($post['product_code']);
								$regis_history['registration_id'] = $registrasi['id'];
								$regis_history['history_date'] = date('Y-m-d H:i:s');
								$regis_history['type'] = 'Warranty';
								$regis_history['old_product_code'] = $post['product_code'];
								$regis_history['new_product_code'] = $post['new_product_code'];
								$this->registration_product_history_model->create_registration_product_history($regis_history);
								//update registration product
								$this->registration_product_model->update_product_code($registrasi['id'], $post['new_product_code'], date('Y-m-d H:i:s'), $this->admin->name());
							}
						}
					}

					//insert history
					$history['warranty_claim_id'] = $post['id'];
					$history['process_date'] = $updated_date;
					$history['process_by'] = $updated_by;
					$history['status'] = $post['status'];
					$history['response_note'] = $post['response_note'];
					$history['response_photo'] = $invoiceimage;
					
					$history_id = $this->warranty_claim_history_model->create_warranty_claim_history($history);
	
					// notification
					$this->load->model('notification_model');
					$this->notification_model->add_notification(array(
						'title' => 'Perubahan Status Klaim Garansi Produk '.$post['product_code'],
						'message' => 'Ada perubahan status klaim garansi oleh user: '.$history['process_by'].', kode serial produk: '.$post['product_code'].', nama produk: '.$post['product_name'].', status dari "'.$claim['status'].'" menjadi "'.$post['status'].'"',
						'table' => 'warranty_claim',
						'table_id' => $post['id'],
						'date_added' => date('Y-m-d H:i:s')
					));
					$this->notification_model->add_notification_distributor(array(
						'title' => 'Perubahan Status Klaim Garansi Produk '.$post['product_code'],
						'message' => 'Ada perubahan status klaim garansi oleh user: '.$history['process_by'].', kode serial produk: '.$post['product_code'].', nama produk: '.$post['product_name'].', status dari "'.$claim['status'].'" menjadi "'.$post['status'].'"',
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
					$data['newstatus'] = html_entity_decode($post['status'], ENT_QUOTES, 'UTF-8');

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
					
					$json['success'] = lang('success_updated');
		
				} else {
					$json['warning'] = 'Data tidak ditemukan';
				}
			}
		}
		
		$this->output->set_output(json_encode($json));
	}

	/**
	 * View Address
	 * 
	 * @access public
	 * @return void
	 */
	public function view_address()
	{
		$claim_id = $this->input->get('claim_id');

		$json['error'] = '';
		
		if ($claim = $this->warranty_claim_model->get_warranty_claim($claim_id)) {
			$data['claim'] = $claim;
			$this->load->model('address_model');
			$data['addresses'] = $this->address_model->get_addresses($claim['user_id']);
		} else {
			$json['error'] = 'Data tidak ditemukan';
		}
		
		if($json['error'] != '') {
			$this->output->set_output(json_encode($json));
		}
		else {
			$this->output->set_output(json_encode(array(
				'content' => $this->load->layout(null)->view('admin/warranty_claim_view_address', $data, true)
			)));
		}
	}
}