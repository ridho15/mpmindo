<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reg_sale_offline extends Admin_Controller
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
		
		$this->load->model('reg_sale_offline_model');

		$this->load->model('article_model');

		$this->load->helper('format');
		
		$this->lang->load('reg_sale_offline');
		
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
		$reg_id = $this->input->get('reg_id');
		if($reg_id) {
			// notification
			$this->load->model('notification_model');
			$this->notification_model->read_notification($this->admin->admin_id(), 'reg_sale_offline', $reg_id);
		}

		if ($this->input->post(null, true)) {
			$this->load->library('datatables');
			
			$period = (int)$this->config->item('warranty_period');

			$this->datatables
			->select('it.id as reg_id, register_date, user_name, start_warranty, shop_name, notes, product_code, product_name, start_warranty as datewarranty, register_type')
			->from('registration_product it')
			->order_by('register_date desc, product_code')
			->edit_column('register_date', '$1', 'format_date(register_date)')
			->edit_column('start_warranty', '$1', 'format_date(start_warranty)');

			if ($this->input->post('reg_type')) {
				$this->datatables->where('register_type', $this->input->post('reg_type'));
			}
			if ($this->input->post('warranty_period')) {
				if($this->input->post('warranty_period') == 1) {
					$this->datatables->where('(start_warranty + INTERVAL '.$period.' MONTH) >= DATE(NOW())');
				}
				else if($this->input->post('warranty_period') == 2) {
					$this->datatables->where('(start_warranty + INTERVAL '.$period.' MONTH) < DATE(NOW())');
				}
			}

			// $this->datatables
			// ->select('it.id as reg_id, register_date, registrant, start_warranty, shop_name, notes, product_code, product_name, ib.id as detail_id, start_warranty as datewarranty')
			// ->from('reg_sale_offline it')
			// ->join('reg_sale_offline_detail ib', 'ib.reg_sale_offline_id = it.id', 'left')
			// ->order_by('it.id desc')
			// ->edit_column('register_date', '$1', 'format_date(register_date)')
			// ->edit_column('start_warranty', '$1', 'format_date(start_warranty)');

			if($this->admin->warehouse_id() != 0) {
				$this->datatables->where('it.warehouse_id', $this->admin->warehouse_id());
			}

			$this->output->set_output($this->datatables->generate('json'));
		} else {
			// if ( ! $this->admin->has_permission()) {
			// 	show_401($this->uri->uri_string());
			// }
			
			$data['warranty_period'] = $this->config->item('warranty_period');

			$this->load
			->title(lang('heading_title'))
			->view('admin/reg_sale_offline', $data);
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
	 * Form
	 * 
	 * @access private
	 * @return void
	 */
	private function form()
	{
		$json = array();
		
		$data['action']	= admin_url('reg_sale_offline/validate');
		
		$data['heading_title']	= 'Tambah Registrasi Produk';

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
		
		$this->output->set_output(json_encode(array(
			'content' => $this->load->layout(null)->view('admin/reg_sale_offline_form', $data, true, true)
		)));
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

	/**
	 * Validate form
	 * 
	 * @access public
	 * @return json
	 */
	public function validate()
	{
		$this->load->model('order_model');

		$json = array();
		
		$product_id = $this->input->post('product_id');
		
		$this->form_validation
		->set_rules('shop_name', 'Nama Toko', 'trim|required')
		->set_error_delimiters('', '');
		
		if ($this->form_validation->run() == false) {
			foreach ($this->form_validation->get_errors() as $field => $error) {
				$json['error'][$field] = $error;
			}
		} else {
			if ($this->input->post('inventories')) {
				$inventories = $this->input->post('inventories');
			} else {
				$inventories = array();
			}
			
			if (count($inventories) < 1) {
				$json['warning'] = lang('error_item_required');
			}

			foreach ($inventories as $inventory) {
				if($inventory['code'] == '' || $inventory['name'] == '') {
					$json['warning'] = lang('error_item_required');
					break;
				}
				$counter = 0;
				foreach($inventories as $inventori) {
					if($inventory['code'] == $inventori['code']) $counter++;
				}
				if($counter > 1) {
					$json['warning'] = 'Produk '.$inventory['code'].' diinput lebih dari satu kali';
					break;
				}
				if($this->reg_sale_offline_model->check_product_reg_sale_offline($inventory['code']) > 0) {
					$json['warning'] = 'Produk '.$inventory['code'].' sudah pernah diregistrasi';
					break;
				}
				if($this->order_model->check_product_order_product_detail($inventory['code'], '', true) > 0) {
					$json['warning'] = 'Produk '.$inventory['code'].' sudah pernah dipakai di pesanan';
					break;
				}
				if($this->registration_product_model->check_product_registration_product($inventory['code']) > 0) {
					$json['warning'] = 'Produk '.$inventory['code'].' sudah pernah diregistrasikan';
					break;
				}
			}

			if (!$json) {
				$post = $this->input->post(null, false);
				$post['registrant'] = $this->admin->name();
				$post['start_warranty'] = date('Y-m-d');
				$post['register_date'] = date('Y-m-d H:i:s');
				$post['registrant_email'] = $this->admin->email();
				$post['warehouse_id'] = $this->admin->warehouse_id();
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
							$this->registration_product_model->create_registration_product_staff($post, $detail, $detail_id);
						}
					}

					// notification
					$this->load->model('notification_model');
					$this->notification_model->add_notification(array(
						'title' => 'Registrasi Produk Baru',
						'message' => 'Ada registrasi produk baru oleh '.$post['registrant'].', tanggal registrasi: '.date('d-m-Y',strtotime($post['register_date'])).', nama toko: '.$post['shop_name'],
						'table' => 'reg_sale_offline',
						'table_id' => $reg_id,
						'date_added' => date('Y-m-d H:i:s')
					));
					$this->notification_model->add_notification_distributor(array(
						'title' => 'Registrasi Produk Baru',
						'message' => 'Ada registrasi produk baru oleh '.$post['registrant'].', tanggal registrasi: '.date('d-m-Y',strtotime($post['register_date'])).', nama toko: '.$post['shop_name'],
						'table' => 'reg_sale_offline',
						'table_id' => $reg_id,
						'date_added' => date('Y-m-d H:i:s')
					), $post['warehouse_id']);

					//send email
					$data['logo'] = $this->image->resize($this->config->item('logo'), 120);
					$data['shop_name'] = html_entity_decode($post['shop_name'], ENT_QUOTES, 'UTF-8');
					$data['register_date'] = html_entity_decode($post['register_date'], ENT_QUOTES, 'UTF-8');
					$data['notes'] = html_entity_decode($post['notes'], ENT_QUOTES, 'UTF-8');
					$data['name'] = html_entity_decode($post['registrant'], ENT_QUOTES, 'UTF-8');
					$data['inventories'] = $arrayInventories;

					$this->load->library('email');
					$this->load->config('email');

					$this->load->model('admin_model');
					$adminEmail = array();
					$distributorEmail = array();
					foreach ($this->admin_model->get_admins() as $admin) {
						$adminEmail[] = $admin['email'];
					}
					foreach ($this->admin_model->get_distributors($post['warehouse_id']) as $admin) {
						$distributorEmail[] = $admin['email'];
					}
					if(count($adminEmail) > 0) {
						$this->email->from($this->config->item('sender'), $this->config->item('site_name'));
						$this->email->to($adminEmail);
						$this->email->cc($this->config->item('sender'));
						$this->email->subject('Registrasi Produk Baru di MPM WAHL');
						$this->email->message($this->load->layout(false)->view('admin/emails/new_registration_offline', $data, true));
						$this->email->send();
					}
					if(count($distributorEmail) > 0) {
						$this->email->from($this->config->item('sender'), $this->config->item('site_name'));
						$this->email->to($distributorEmail);
						$this->email->cc($this->config->item('sender'));
						$this->email->subject('Registrasi Produk Baru di MPM WAHL');
						$this->email->message($this->load->layout(false)->view('admin/emails/new_registration_offline', $data, true));
						$this->email->send();
					}
				}
				$json['success'] = lang('success_created');
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

		$json['error'] = '';
		
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
				'content' => $this->load->layout(null)->view('admin/reg_sale_offline_photo', $data, true)
			)));
		}
	}

	/**
	 * Photo
	 * 
	 * @access public
	 * @return void
	 */
	public function edit_warrranty()
	{
		$reg_id = $this->input->get('reg_id');

		$json['error'] = '';
		
		if ($registrasi = $this->registration_product_model->get_registration_product($reg_id)) {
			$data['start_warranty'] = $registrasi['start_warranty'];
		} else {
			$json['error'] = 'Data tidak ditemukan';
		}
		$data['action']	= admin_url('reg_sale_offline/validate_edit_warranty');
		
		$data['heading_title']	= 'Edit Mulai Garansi';
		$data['reg_id'] = $reg_id;

		if($json['error'] != '') {
			$this->output->set_output(json_encode($json));
		}
		else {
			$this->output->set_output(json_encode(array(
				'content' => $this->load->layout(null)->view('admin/reg_sale_offline_edit_warranty', $data, true)
			)));
		}
	}

	/**
	 * Validate edit warranty form
	 * 
	 * @access public
	 * @return json
	 */
	public function validate_edit_warranty()
	{
		$json = array();
		
		$reg_id = $this->input->post('reg_id');
		
		$this->form_validation
		->set_rules('start_warranty', 'Mulai Garansi', 'trim|required')
		->set_rules('reg_id', 'Kode Produk', 'trim|required')
		->set_error_delimiters('', '');
		
		if ($this->form_validation->run() == false) {
			foreach ($this->form_validation->get_errors() as $field => $error) {
				$json['error'][$field] = $error;
			}
		} else {
			$post = $this->input->post(null, false);
			$updated_by = $this->admin->name();
			$start_warranty = date('Y-m-d',strtotime($post['start_warranty']));
			$updated_date = date('Y-m-d H:i:s');

			$this->registration_product_model->update_start_warranty($reg_id, $start_warranty, $updated_date, $updated_by);
			$json['success'] = lang('success_updated');
		}
		
		$this->output->set_output(json_encode($json));
	}

	/**
	 * Start Warranty
	 * 
	 * @access private
	 * @return void
	 */
	public function start_warranty()
	{
		$reg_id = $this->input->get('reg_id');

		$json = array();

		$json['error'] = '';
		
		$data['action']	= admin_url('reg_sale_offline/validate_claim_warranty');
		
		$data['heading_title']	= 'Klaim Garansi';
		$data['mobile'] = $this->agent->is_mobile();
		
		if ($registrasi = $this->registration_product_model->get_registration_product($reg_id)) {
			$data['sale_type'] = 'Offline';
			$data['sale_id'] = $reg_id;
			$data['product_code'] = $registrasi['product_code'];
			$data['product_name'] = $registrasi['product_name'];
			$data['reg_id'] = $reg_id;

			foreach ($this->warehouse_model->get_all_warehouse() as $warehouse) {
				if($warehouse['warehouse_id'] != 2) {
					if(($this->admin->warehouse_id() != 0 && $this->admin->warehouse_id() == $warehouse['warehouse_id']) || $this->admin->warehouse_id() == 0) {
						$data['warehouse_froms'][$warehouse['warehouse_id']] = $warehouse['code'].' ('.$warehouse['name'].')';
					}
				}
			}

		} else {
			$json['error'] = 'Data tidak ditemukan';
		}
		
		if($json['error'] != '') {
			$this->output->set_output(json_encode($json));
		}
		else {
			$this->output->set_output(json_encode(array(
				'content' => $this->load->layout(null)->view('admin/reg_sale_offline_start_warranty', $data, true, true)
			)));
		}
	}

	/**
	 * Upload
	 * 
	 * @access public
	 * @return void
	 */
	public function upload_warranty()
	{
		$json = array();
		
		$upload_path = DIR_IMAGE.'warranties';
		
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

	/**
	 * Validate claim warranty form
	 * 
	 * @access public
	 * @return json
	 */
	public function validate_claim_warranty()
	{
		$json = array();
		
		$this->form_validation
		->set_rules('sale_type', 'Tipe Penjualan', 'trim|required')
		->set_rules('sale_id', 'ID Penjualan', 'trim|required')
		->set_rules('reason', 'Alasan Klaim', 'trim|required')
		->set_rules('claim_description', 'Deskripsi Kerusakan', 'trim|required')
		->set_rules('warehouse_destination', 'Kirim ke Gudang / Distributor', 'trim|required')
		->set_rules('reg_id', 'ID Registrasi', 'trim|required')
		->set_error_delimiters('', '');
		
		if ($this->form_validation->run() == false) {
			foreach ($this->form_validation->get_errors() as $field => $error) {
				$json['error'][$field] = $error;
			}
		} else {
			$post = $this->input->post(null, false);

			//check start warranty
			$registrasi = $this->registration_product_model->get_registration_product($post['reg_id']);
			
			if(strtotime($registrasi['start_warranty']) > strtotime(date('Y-m-d'))) {
				$json['warning'] = 'Produk belum masuk masa garansi';
			}
			else {
				$post['claim_by'] = $this->admin->name();
				$post['claim_date'] = date('Y-m-d H:i:s');
				$post['status'] = 'Klaim Baru';
				if(isset($post['invoice_image'])) $post['evidence_photo'] = $post['invoice_image'];
				else $post['evidence_photo'] = '';
				$post['user_email'] = $this->admin->email();
				$post['create_source'] = 'admin';
	
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
						$history_id = $this->warranty_claim_history_model->create_warranty_claim_history($history);
	
						// notification
						$this->load->model('notification_model');
						$this->notification_model->add_notification(array(
							'title' => 'Klaim Garansi Baru',
							'message' => 'Ada klaim garansi baru oleh user: '.$post['claim_by'].', kode serial produk: '.$post['product_code'].', nama produk: '.$post['product_name'].', alasan: '.$post['reason'],
							'table' => 'warranty_claim',
							'table_id' => $claim_id,
							'date_added' => date('Y-m-d H:i:s')
						));
						$this->notification_model->add_notification_distributor(array(
							'title' => 'Klaim Garansi Baru',
							'message' => 'Ada klaim garansi baru oleh user: '.$post['claim_by'].', kode serial produk: '.$post['product_code'].', nama produk: '.$post['product_name'].', alasan: '.$post['reason'],
							'table' => 'warranty_claim',
							'table_id' => $claim_id,
							'date_added' => date('Y-m-d H:i:s')
						), $post['warehouse_destination']);

						//send email
						$data['logo'] = $this->image->resize($this->config->item('logo'), 120);
						$data['claim_date'] = html_entity_decode(date('d-m-Y',strtotime($post['claim_date'])), ENT_QUOTES, 'UTF-8');
						$data['product_code'] = html_entity_decode($post['product_code'], ENT_QUOTES, 'UTF-8');
						$data['product_name'] = html_entity_decode($post['product_name'], ENT_QUOTES, 'UTF-8');
						$data['name'] = html_entity_decode($post['claim_by'], ENT_QUOTES, 'UTF-8');
						$data['reason'] = html_entity_decode($post['reason'], ENT_QUOTES, 'UTF-8');
						$data['claim_by'] = html_entity_decode($post['claim_by'], ENT_QUOTES, 'UTF-8');
						$data['user_email'] = html_entity_decode($post['user_email'], ENT_QUOTES, 'UTF-8');
						$data['user_phone'] = '';

						$this->load->library('email');
						$this->load->config('email');
						
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
							$this->email->subject('Klaim Garansi Baru Produk '.html_entity_decode($data['product_code'].' - '.$data['product_name'].' di MPM WAHL', ENT_QUOTES, 'UTF-8'));
							$this->email->message($this->load->layout(false)->view('admin/emails/new_claim', $data, true));
							$this->email->send();
						}
						if(count($distributorEmail) > 0) {
							$this->email->from($this->config->item('sender'), $this->config->item('site_name'));
							$this->email->to($distributorEmail);
							$this->email->cc($this->config->item('sender'));
							$this->email->subject('Klaim Garansi Baru Produk '.html_entity_decode($data['product_code'].' - '.$data['product_name'].' di MPM WAHL', ENT_QUOTES, 'UTF-8'));
							$this->email->message($this->load->layout(false)->view('admin/emails/new_claim', $data, true));
							$this->email->send();
						}
						
						$json['success'] = 'Klaim garansi berhasil dibuat';
					}
					else $json['warning'] = 'Klaim gagal dibuat';
				}
				else {
					$json['warning'] = 'Produk masih dalam proses garansi';
				}
			}
		}
		
		$this->output->set_output(json_encode($json));
	}

	/**
	 * Excel
	 *
	 * @access public
	 * @return void
	 */
	public function excel()
	{
		if ($this->input->post(null, true)) {

			$this->load->helper('format');

			$reg_type = $this->input->post('reg_type');
			$warranty_period = $this->input->post('warranty_period');
			$period = (int)$this->config->item('warranty_period');

			$this->db
			->select('it.id as reg_id, register_date, user_name, start_warranty, shop_name, notes, product_code, product_name, start_warranty as datewarranty, register_type')
			->from('registration_product it');
			
			if ($this->input->post('reg_type')) {
				$this->db->where('register_type', $this->input->post('reg_type'));
			}
			if ($this->input->post('warranty_period')) {
				if($this->input->post('warranty_period') == 1) {
					$this->db->where('(start_warranty + INTERVAL '.$period.' MONTH) >= DATE(NOW())');
				}
				else if($this->input->post('warranty_period') == 2) {
					$this->db->where('(start_warranty + INTERVAL '.$period.' MONTH) < DATE(NOW())');
				}
			}

			if($this->admin->warehouse_id() != 0) {
				$this->db->where('it.warehouse_id', $this->admin->warehouse_id());
			}

			$products = $this->db->order_by('register_date desc, product_code')->get()->result_array();

			$this->load->library('PHPExcel');

			require_once(APPPATH.'libraries/PHPExcel.php');
			require_once(APPPATH.'libraries/PHPExcel/IOFactory.php');

			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
			$objPHPExcel->setActiveSheetIndex(0);

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'TANGGAL REGISTRASI');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'KODE SERIAL PRODUK');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'NAMA PRODUK');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'TIPE REGISTRASI');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'NAMA TOKO');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, 'PENDAFTAR');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, 'MULAI GARANSI');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, 'AKHIR GARANSI');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, 'KETERANGAN');

			$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
			
			$row = 2;
			
			foreach($products as $product) {
				$register_type = "";
				if($product['register_type'] == 'reg_by_online') $register_type = 'Registrasi dari belanja online';
				else if($product['register_type'] == 'reg_by_staff') $register_type = 'Registrasi oleh staff';
				else if($product['register_type'] == 'reg_by_customer') $register_type = 'Registrasi oleh customer';

				$end_warranty = "";
				$end_warranty = date('d/m/Y', strtotime($product['start_warranty'].' +'.$period.' months'));

				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, date('d/m/Y',strtotime($product['register_date'])));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $product['product_code']);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $product['product_name']);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $register_type);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $product['shop_name']);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $product['user_name']);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, date('d/m/Y',strtotime($product['start_warranty'])));
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $end_warranty);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $product['notes']);
				
				$row++;
			}
			
			$filename = 'daftar-produk-'.date('dmY-Hi').'.xlsx';
			
			$objPHPExcel->setActiveSheetIndex(0);

			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$filename.'"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

			$objWriter->save('php://output');
		} else {
			show_404();
		}
	}
}