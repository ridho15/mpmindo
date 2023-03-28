<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reg_sale_offline extends User_Controller
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
		
		$this->load->model('reg_sale_offline_model');
		
		$this->load->helper('form');
		$this->load->library('form_validation');

		$this->load->model('article_model');
		$this->load->helper('url');
		$this->load->model('warehouse_model');
		$this->load->model('warranty_claim_model');

		$this->load->model('warranty_claim_history_model');

		$this->load->model('registration_product_model');
		$this->load->library('user_agent');
	}
	
	/**
	 * Index
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		// $data['registrations'] = $this->db
		// ->select('register_date, shop_name, start_warranty, os.product_code, os.product_name, notes, o.id, os.id as detail_id', false)
		// ->from('reg_sale_offline o')
		// ->join('reg_sale_offline_detail os', 'os.reg_sale_offline_id = o.id', 'left')
		// ->where('o.registrant_email', $this->user->email())
		// ->order_by('register_date desc, id desc')
		// ->get()
		// ->result_array();
			
		$data['registrations'] = $this->db
		->select('register_date, shop_name, start_warranty, product_code, product_name, notes, o.id, register_type', false)
		->from('registration_product o')
		->where('o.user_email', $this->user->email())
		->order_by('register_date desc, product_code asc')
		->get()
		->result_array();

		$this->load
		->breadcrumb('Akun', user_url())
		->breadcrumb('Registrasi Produk', user_url('reg_sale_offline'))
		->view('user/reg_sale_offline', $data);
	}

	/**
	 * Add
	 * 
	 * @access public
	 * @return void
	 */
	public function add()
	{
		$data['action'] = user_url('reg_sale_offline/validate');
		$data['name'] = $this->user->name();
		$data['email'] = $this->user->email();
		
		$product_names = $this->db
		->select('name', false)
		->from('product o')
		->order_by('name')
		->get()
		->result_array();

		$listname = "";
		foreach($product_names as $product_name) {
			$listname .= "<option value='".$product_name['name']."'>".$product_name['name']."</option>";
		}
		$data['listname'] = $listname;
		$data['mobile'] = $this->agent->is_mobile();
		
		$this->load
		->breadcrumb('Akun', user_url())
		->breadcrumb('Registrasi Produk', user_url('reg_sale_offline/add'))
		->view('user/reg_sale_offline_form', $data);
	}

	/**
	 * Validate
	 * 
	 * @access public
	 * @return void
	 */
	public function validate()
	{
		$this->load->model('order_model');

		check_ajax();
		
		$json = array();
		
		$this->form_validation
		->set_rules('shop_name', 'Nama Toko', 'trim|required')
		->set_error_delimiters('', '');
		
		if ($this->form_validation->run() == false) {
			foreach ($this->form_validation->get_errors() as $field => $error) {
				$json['errors'][$field] = $error;
			}
		} else {
			$post = $this->input->post(null, false);
			$post['start_warranty'] = date('Y-m-d');
			$post['register_date'] = date('Y-m-d H:i:s');

			if ($this->input->post('inventories')) {
				$inventories = $this->input->post('inventories');
			} else {
				$inventories = array();
			}
			
			if (count($inventories) < 1) {
				$json['error'] = 'Produk harus diisi';
			}

			foreach ($inventories as $inventory) {
				if($inventory['code'] == '' || $inventory['name'] == '') {
					$json['error'] = 'Kode dan Nama Produk harus diisi';
					break;
				}
				$counter = 0;
				foreach($inventories as $inventori) {
					if($inventory['code'] == $inventori['code']) $counter++;
				}
				if($counter > 1) {
					$json['error'] = 'Produk '.$inventory['code'].' diinput lebih dari satu kali';
					break;
				}
				if($this->reg_sale_offline_model->check_product_reg_sale_offline($inventory['code']) > 0) {
					$json['error'] = 'Produk '.$inventory['code'].' sudah pernah diregistrasi';
					break;
				}
				if($this->order_model->check_product_order_product_detail($inventory['code'], '', true) > 0) {
					$json['error'] = 'Produk '.$inventory['code'].' sudah diregistrasi oleh admin';
					break;
				}
				if($this->registration_product_model->check_product_registration_product($inventory['code']) > 0) {
					$json['warning'] = 'Produk '.$inventory['code'].' sudah pernah diregistrasikan';
					break;
				}
			}

			if (!$json) {
				$reg_id = $this->reg_sale_offline_model->create_reg_sale_offline($post);
				$arrayInventories = array();
				if($reg_id) {
					// insert detail
					$this->load->model('reg_sale_offline_detail_model');
					foreach ($inventories as $inventory) {
						$detail['reg_sale_offline_id'] = $reg_id;
						$detail['product_code'] = $inventory['code'];
						$detail['product_name'] = $inventory['name'];
						$detail_id = $this->reg_sale_offline_detail_model->insert($detail);
						$arrayInventories[] = $inventory['code'].', '.$inventory['name'];
						if($detail_id) {
							// insert to registration product
							$this->registration_product_model->create_registration_product_customer($post, $detail, $detail_id);
						}
					}

					// notification
					$this->load->model('notification_model');
					$this->notification_model->add_notification(array(
						'title' => 'Registrasi Produk Baru',
						'message' => 'Ada registrasi produk baru oleh customer: '.$post['registrant'].', tanggal registrasi: '.date('d-m-Y',strtotime($post['register_date'])).', nama toko: '.$post['shop_name'],
						'table' => 'reg_sale_offline',
						'table_id' => $reg_id,
						'date_added' => date('Y-m-d H:i:s')
					));
					
					//send email
					$data['logo'] = $this->image->resize($this->config->item('logo'), 120);
					$data['shop_name'] = html_entity_decode($post['shop_name'], ENT_QUOTES, 'UTF-8');
					$data['register_date'] = html_entity_decode($post['register_date'], ENT_QUOTES, 'UTF-8');
					$data['notes'] = html_entity_decode($post['notes'], ENT_QUOTES, 'UTF-8');
					$data['name'] = html_entity_decode($post['registrant'], ENT_QUOTES, 'UTF-8');
					$data['inventories'] = $arrayInventories;
					//to customer
					$this->load->library('email');
					$this->load->config('email');
					$this->email->from($this->config->item('sender'), $this->config->item('site_name'));
					$this->email->to($post['registrant_email']);
					$this->email->cc($this->config->item('sender'));
					$this->email->subject('Registrasi Produk Baru di MPM WAHL');
					$this->email->message($this->load->layout(false)->view('user/emails/registration_offline', $data, true));
					$this->email->send();
					//to admin & distributor
					$this->load->model('admin_model');
					$adminEmail = array();
					$distributorEmail = array();
					foreach ($this->admin_model->get_admins() as $admin) {
						$adminEmail[] = $admin['email'];
					}
					if(count($adminEmail) > 0) {
						$this->email->from($this->config->item('sender'), $this->config->item('site_name'));
						$this->email->to($adminEmail);
						$this->email->cc($this->config->item('sender'));
						$this->email->subject('Registrasi Produk Baru di MPM WAHL');
						$this->email->message($this->load->layout(false)->view('admin/emails/new_registration_offline', $data, true));
						$this->email->send();
					}

					$json['success'] = true;

					$this->session->set_flashdata('success', "Registrasi Berhasil");
				}
			}
		}
		
		$this->output->set_output(json_encode($json));
	}

	/**
	 * Photo
	 * 
	 * @access public
	 * @return void
	 */
	public function photo()
	{
		$reg_id = $this->input->get('reg_id');
		$json['error'] = "";
		if ($registrasi = $this->registration_product_model->get_registration_product($reg_id)) {
			$data['invoice_image'] = $registrasi['invoice_image'];
		} else {
			$json['error'] = 'Data tidak ditemukan';
		}
		
		if($json['error'] != '') {
			$this->output->set_output(json_encode($json));
		}
		else {
			$this->output->set_output(json_encode(array(
				'content' => $this->load->layout(null)->view('user/reg_sale_offline_photo', $data, true)
			)));
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
		$detail_id = $this->input->get('detail_id');
		$data['action'] = user_url('reg_sale_offline/validate_claim_warranty');
		$data['name'] = $this->user->name();
		
		if ($registrasi = $this->registration_product_model->get_registration_product($detail_id)) {
			if($registrasi['user_email'] != $this->user->email()) {
				show_404();
			}

			$data['sale_type'] = 'Offline';
			$data['sale_id'] = $detail_id;
			$data['product_code'] = $registrasi['product_code'];
			$data['product_name'] = $registrasi['product_name'];
			$data['reg_id'] = $detail_id;
			$data['mobile'] = $this->agent->is_mobile();
			
			foreach ($this->warehouse_model->get_all_warehouse() as $warehouse) {
				if($warehouse['warehouse_id'] != 2) {
					if(($this->admin->warehouse_id() != 0 && $this->admin->warehouse_id() == $warehouse['warehouse_id']) || $this->admin->warehouse_id() == 0) {
						$data['warehouse_froms'][$warehouse['warehouse_id']] = $warehouse['code'].' ('.$warehouse['name'].')';
					}
				}
			}

			$this->load
				->breadcrumb('Akun', user_url())
				->breadcrumb('Klaim Garansi', user_url('reg_sale_offline/claim_warranty?detail_id='.$detail_id))
				->view('user/reg_sale_offline_claim_warranty', $data);
		} else {
			redirect(user_url('reg_sale_offline'), 'refresh');
		}
	}

	/**
	 * List Warehouse
	 * 
	 * @access public
	 * @return void
	 */
	public function list_warehouse()
	{
		$data['warehouses'] = $this->warehouse_model->get_all_warehouse();
		
		$this->output->set_output(json_encode(array(
			'content' => $this->load->layout(null)->view('user/reg_sale_offline_list_warehouse', $data, true)
		)));
	}

	/**
	 * Validate
	 * 
	 * @access public
	 * @return void
	 */
	public function validate_claim_warranty()
	{
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

			//check start warranty
			$registrasi = $this->registration_product_model->get_registration_product($post['reg_id']);
			if(strtotime($registrasi['start_warranty']) > strtotime(date('Y-m-d'))) {
				$json['warning'] = 'Produk belum masuk masa garansi';
			}
			else {
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
	 * Upload
	 * 
	 * @access public
	 * @return void
	 */
	public function upload()
	{
		$json = array();
		
		$upload_path = DIR_IMAGE.'regofflines';
		
		if ( ! file_exists($upload_path)) {
			@mkdir($upload_path, 0777);
		}
		
		$this->load->library('upload', array(
			'upload_path' => $upload_path,
			'allowed_types' => 'gif|jpg|png|jpeg|heif|tiff',
			'max_size' => '5120',
			'overwrite' => false,
			'encrypt_name'=>true,
		));

		if ( ! $this->upload->do_upload()) {
			$json['error'] = $this->upload->display_errors('', '');
		} else {
			$this->load->library('image');
			
			$upload_data = $this->upload->data();
			$image = substr($upload_data['full_path'], strlen(FCPATH.DIR_IMAGE));

			// check EXIF and autorotate if needed
			//$this->load->library('image_autorotate', array('filepath' => $image));
			
			$thumb = $this->image->resize($image, 150, 150);
			
			$json['image'] = $image;
			$json['thumb'] = $thumb;
			
			$json['success'] = 'File is now uploaded!';
		}
		
		$this->output->set_output(json_encode($json));
	}
}