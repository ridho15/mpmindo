<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends Admin_Controller
{
	private $emails;
	
	/**
	 * Constructor
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('user_model');
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->model('address_model');
		$this->load->model('location_model');
		$this->load->library('user_agent');
	}
	
	public function index()
	{
		if ($this->input->post(null, true)) {
			$this->load->library('datatables');
			$this->load->helper('format');
		
			$this->datatables
			->select('u.user_id, u.name, u.email, u.telephone, u.active, u.date_added, u.job, u.job_status, u.address_id, u.dob,
			(select concat(address,", ",subdistrict,", ",city,", ",province,", ",postcode) from address b where b.address_id = u.address_id) as address', false)
			->from('user u');

			if ($this->input->post('job_status')) {
				$this->datatables->where('u.job_status', $this->input->post('job_status'));
			}
			if ($this->input->post('birthday')) {
				if($this->input->post('birthday') == 1) {
					$this->datatables->where('month(u.dob)', date('m'));
				}
				else if($this->input->post('birthday') == 2) {
					$this->datatables->where('month(u.dob)', date('m', strtotime("+1 month", strtotime(date('Y-m-01')))));
				}
			}
			if ($this->input->post('active')) {
				if($this->input->post('active') == 1) {
					$this->datatables->where('u.active', 1);
				}
				else if($this->input->post('active') == 2) {
					$this->datatables->where('u.active', 0);
				}
			}
			if ($this->input->post('province')) {
				$this->datatables->like('(select concat(address,", ",subdistrict,", ",city,", ",province,", ",postcode) from address b where b.address_id = u.address_id)', $this->input->post('province'));
			}

			$this->datatables
			->edit_column('date_added', '$1', 'format_date(date_added)')
			->edit_column('dob', '$1', 'format_date(dob)');
			
			$this->output->set_output($this->datatables->generate('json'));
		} else {
			if ( ! $this->admin->has_permission()) {
				show_401($this->uri->uri_string());
			}

			$data['provinces'] = $this->location_model->get_provinces();
			
			$this->load->title('Pelanggan')->view('admin/user', $data);	
		}
	}
	
	public function transaction($user_id = null)
	{	
		if ($this->input->post(null, true)) {
			$this->load->library('datatables');
			$this->load->helper('format');
		
			$this->datatables
			->select('user_transaction_id, approved, @uti := user_transaction_id, description, date_added, (CASE WHEN amount > 0 THEN amount ELSE NULL END) AS debit, (CASE WHEN amount < 0 THEN amount * (-1) ELSE NULL END) AS credit, (SELECT SUM(amount) FROM user_transaction ut WHERE ut.user_transaction_id < @uti AND ut.user_id = '.(int)$this->input->post('user_id').') + amount as balance', false)
			->from('user_transaction')
			->where('user_id', $this->input->post('user_id'))
			->edit_column('date_added', '$1', 'format_date(date_added)')
			->edit_column('debit', '$1', 'format_money(debit)')
			->edit_column('credit', '$1', 'format_money(credit)')
			->edit_column('balance', '$1', 'format_money(balance)');
			
			$this->output->set_output($this->datatables->generate('json'));
		} else {
			if ( ! $this->admin->has_permission()) {
				show_401($this->uri->uri_string());
			}
			
			if ($user = $this->user_model->get($user_id)) {
				$data['name'] = $user['name'];
			} else {
				$data['name'] = '';
			}
			
			$data['user_id'] = $user_id;
			
			$this->load->title('Daftar Transaksi')->view('admin/user_transaction', $data);	
		}
	}
	
	public function info($user_id)
	{
		if ($user = $this->user_model->get($user_id)) {
			foreach ($user as $key => $value) {
				$data[$key] = $value;
			}
			
			if ($user['type'] == 'exhibitor') {
				$data['heading_title'] = 'Exhibitor Detail';
			} else {
				$data['heading_title'] = 'Visitor Detail';
			}
			
			$this->load->view('admin/user_info', $data);	
		} else {
			show_404();
		}
	}
	
	public function create()
	{
		if ( ! $this->admin->has_permission()) {
			show_401($this->uri->uri_string());
		}
		
		$this->load->title('Tambah Pelanggan');
		
		$this->form();
	}
	
	public function edit($user_id = null)
	{
		if ( ! $this->admin->has_permission()) {
			show_401($this->uri->uri_string());
		}
		
		$this->load->title('Edit Pelanggan');
		
		$this->form($user_id);
	}
	
	public function form($user_id = null)
	{
		$this->load->library('image');
		$this->load->model('location_model');
		$this->load->model('bank_model');
		
		if ($user_id) {
			foreach ($this->user_model->get($user_id) as $key => $value) {
				$data[$key] = $value;
			}
		} else {
			foreach ($this->user_model->list_fields() as $field) {
				$data[$field] = '';
			}
		}
		
		if ( ! empty($data['image'])) {
			$data['image'] = $this->image->resize($data['image'], 100, 100);
		} else {
			$data['image'] = $this->image->resize('no_image.jpg', 100, 100);
		}
		
		$data['action'] = admin_url('users/validate');
		$data['banks'] = $this->bank_model->get_all();
		$data['months'] = array(
			'01' => 'Januari',
			'02' => 'Februari',
			'03' => 'Maret',
			'04' => 'April',
			'05' => 'Mei',
			'06' => 'Juni',
			'07' => 'Juli',
			'08' => 'Agustus',
			'09' => 'September',
			'10' => 'Oktober',
			'11' => 'November',
			'12' => 'Desember',
		);
		$data['provinces'] = $this->location_model->get_provinces();
		$data['mobile'] = $this->agent->is_mobile();
		
		$this->load->view('admin/user_form', $data);
	}
	
	public function validate()
	{
		check_ajax();
		
		$json = array();
		
		$user_id = $this->input->post('user_id');

		if ($this->input->post('password') !== '' || $user_id == '') {
			$this->form_validation
			->set_rules('password', 'Password', 'trim|required|min_length[6]')
			->set_rules('confirm', 'Konfirmasi Password', 'trim|required|matches[password]');
		}
		
		if($user_id) {
			$this->form_validation
				->set_rules('name', 'Nama', 'trim|required')
				->set_rules('telephone', 'No. Telephone / Handphone', 'trim|required|callback__check_telephone')
				->set_rules('email', 'Email', 'trim|required|valid_email|callback__check_email')
				->set_rules('job', 'Tempat Kerja', 'trim|required')
				->set_rules('job_status', 'Status Kerja', 'trim|required')
				->set_error_delimiters('', '');
		}
		else {
			$this->form_validation
				->set_rules('name', 'Nama', 'trim|required')
				->set_rules('telephone', 'No. Telephone / Handphone', 'trim|required|callback__check_telephone')
				->set_rules('email', 'Email', 'trim|required|valid_email|callback__check_email')
				->set_rules('job', 'Tempat Kerja', 'trim|required')
				->set_rules('job_status', 'Status Kerja', 'trim|required')
				->set_rules('address_name', 'Nama Alamat', 'trim|required')
				->set_rules('address_address', 'Alamat', 'trim|required')
				->set_rules('address_postcode', 'Kode Pos', 'trim|required')
				->set_rules('address_province_id', 'Provinsi', 'trim|required')
				->set_rules('address_city_id', 'Kota / Kabupaten', 'trim|required')
				->set_rules('address_subdistrict_id', 'Kecamatan', 'trim|required')
				->set_error_delimiters('', '');
		}
		
		if ($this->form_validation->run() == false) {
			if ($this->input->post('password') !== '') {
				if (form_error('password') !== '') {
					$json['errors']['password'] = form_error('password');
				}
				
				if (form_error('confirm') !== '') {
					$json['errors']['confirm'] = form_error('confirm');
				}
			}
			
			foreach ($this->form_validation->get_errors() as $field => $error) {
				$json['errors'][$field] = $error;
			}
		} else {
			$data = $this->input->post(null, true);
			$data['active'] = (bool)$this->input->post('active');
			$data['dob'] = $this->input->post('dob_year').'-'.$this->input->post('dob_month').'-'.$this->input->post('dob_date');

			if ($user_id) {
				$this->user_model->update_user($user_id, $data);
				$success = 'Data pelanggan berhasil diperbarui';
			} else {
				$user_id = $this->user_model->create_user($data);
				if($user_id) {
					$address['user_id'] = $user_id;
					$address['name'] = $this->input->post('address_name');
					$address['address'] = $this->input->post('address_address');
					$address['postcode'] = $this->input->post('address_postcode');
					$address['province_id'] = $this->input->post('address_province_id');
					$address['city_id'] = $this->input->post('address_city_id');
					$address['subdistrict_id'] = $this->input->post('address_subdistrict_id');
					$province = $this->location_model->get_province($this->input->post('address_province_id'));
					$city = $this->location_model->get_city($this->input->post('address_city_id'));
					$subdistrict = $this->location_model->get_subdistrict($this->input->post('address_subdistrict_id'));
					$address['province'] = $province['name'];
					$address['city'] = $city['name'];
					$address['subdistrict'] = $subdistrict['name'];

					$address_id = $this->address_model->insert($address);
					if($address_id) {
						$addressdefault['address_id'] = $address_id;
						$this->user_model->update_user($user_id, $addressdefault);
					}
				}
				$success = 'Berhasil menambahkan pelanggan baru';
			}
			
			$json['success'] = $success;
			$this->session->set_flashdata('success', $json['success']);
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	public function upload()
	{
		$json = array();
		
		$upload_path = DIR_IMAGE.'user';
		
		if ( ! file_exists($upload_path)) {
			@mkdir($upload_path, 0777);
		}
		
		$this->load->library('upload', array(
			'upload_path'	=> $upload_path,
			'allowed_types' => 'gif|jpg|png|jpeg|heif|tiff',
			'max_size' => '5120',
			'encrypt_name'  => true
		));

		if ( ! $this->upload->do_upload()) {
			$json['error'] = $this->upload->display_errors('', '');
		} else {
			$this->load->library('image');
			
			$upload_data = $this->upload->data();
			$image = substr($upload_data['full_path'], strlen(FCPATH.DIR_IMAGE));
			
			// check EXIF and autorotate if needed
			//$this->load->library('image_autorotate', array('filepath' => $image));
			
			$thumb = $this->image->resize($image, 128, 128);
			$this->user_model->update($this->input->get('user_id'), array('image' => $image));
			
			$json['image'] = $thumb;
			$json['success'] = 'File berhasil diupload!';
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	public function _check_email($email)
	{
		if ($this->user_model->check_email($email, $this->input->post('user_id'))) {
			$this->form_validation->set_message('_check_email', 'Email ini tidak tersedia!');
			return false;
		}
		
		return true;
	}
	
	public function _check_telephone($telephone)
	{
		if ($this->user_model->check_telephone($telephone, $this->input->post('user_id'))) {
			$this->form_validation->set_message('_check_telephone', 'No. telepon ini sudah ada yang menggunakan!');
			return false;
		}
		
		return true;
	}
	
	public function delete()
	{
		check_ajax();
		
		$json = array();
		
		$user_id = $this->input->post('user_id');
		
		if ( ! $this->admin->has_permission()) {
			$json['error'] = lang('admin_error_delete');
		}
		
		if (empty($json['error'])) {
			$this->user_model->delete($user_id);
			//delete address
			if (is_array($user_id)) {
				$this->address_model->delete_address_user_array($user_id);
			} else {
				$this->address_model->delete_address_user($user_id);
			}
			
			$json['success'] = 'Berhasil: Pelanggan telah berhasil dihapus!';
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	public function autocomplete()
	{
		$json = array();
		
		if ($this->input->get('name')) {	
			$params = array(
				'name' => $this->input->get('name'),
				'type' => $this->input->get('type'),
				'start' => 0,
				'limit' => 20
			);
			
			foreach ($this->user_model->get_users_autocomplete($params) as $user) {
				$json[$user['user_id']] = array(
					'user_id' => (int)$user['user_id'], 
					'name' => $user['user_id'].' - '.strip_tags(html_entity_decode($user['name'], ENT_QUOTES, 'UTF-8')),
					'type' => strip_tags(html_entity_decode(ucwords($user['type']), ENT_QUOTES, 'UTF-8')),
					'email' => $user['email'],
					'telephone' => $user['telephone']
				);
			}
		}
		
		$sort_order = array();
	
		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}
		
		array_multisort($sort_order, SORT_ASC, $json);
	
		$this->output->set_output(json_encode($json));
	}
	
	public function get_addresses()
	{
		check_ajax();
		
		if ($this->input->post()) {
			$this->load->library('datatables');
		
			$this->datatables
			->select("a.*, l3.name as province, CONCAT_WS(' ', l2.type, l2.name) as city, l.name as subdistrict, (CASE WHEN u.address_id = a.address_id THEN 1 ELSE 0 END) as is_default", false)
			->from('address a')
			->join('location l', 'l.subdistrict_id = a.subdistrict_id', 'left')
			->join('location l2', 'l2.city_id = a.city_id and l2.subdistrict_id = 0', 'left')
			->join('location l3', 'l3.province_id = a.province_id and l3.city_id = 0', 'left')
			->join('user u', 'u.user_id = a.user_id', 'left')
			->where('a.user_id', $this->input->post('user_id'));
			
			$this->output->set_output($this->datatables->generate('json'));
		} else {
			$json = array();
		
			$this->load->model('address_model');
			
			if ($addresses = $this->address_model->get_addresses($this->input->get('user_id'))) {
				foreach ($addresses as $address) {
					$json[] = $address;	
				}
			}
			
			$this->output->set_output(json_encode($json));
		}
	}
	
	public function export()
	{
		if ( ! $this->admin->has_permission('index')) {
			show_401($this->uri->uri_string());
		}
		
		$this->load->helper('format');
		
		$data['reports'] = array();
		
		$results = $this->db
		->select('u.user_id, u.name, u.email, u.telephone, u.active, u.date_added, (SELECT SUM(amount) FROM user_transaction ut WHERE ut.user_id = u.user_id) AS balance')
		->from('user u')
		->get()
		->result_array();
		
		$total = 0;
		
		foreach ($results as $result) {
			$result['date_added'] = format_date($result['date_added']);
			$result['balance'] = format_money($result['balance']);
			$result['status'] = $result['active'] ? 'Aktif' : 'Non Aktif';
			$data['reports'][] = $result;
			$total++;
		}
		
		$data['total'] = (int)$total;
		$format = $this->input->get('format');
		
		switch ($format)
		{
			case 'pdf':
				$this->load->library('pdf');
				$file_name = 'user-report-'.date('dmYHis', time());
				$filename = APPPATH.'cache/docs/'.$file_name.'.pdf';
				$this->pdf->pdf_create($this->load->layout(false)->view('admin/user_export', $data, true), $file_name, true);
			break;
			
			case 'pdf_download':
				$this->load->library('pdf');
				$file_name = 'user-report-'.date('dmYHis', time());
				$filename = APPPATH.'cache/docs/'.$file_name.'.pdf';
				$this->pdf->pdf_create($this->load->layout(false)->view('admin/user_export', $data, true), $filename, false);
					
				if (file_exists($filename))
				{
					$this->load->helper('download');
					
					$content = file_get_contents($filename);
					force_download($file_name.'.pdf', $content);
				}
			break;
			
			case 'excel_download':
				$this->load->library('PHPExcel');
				
				require_once(APPPATH.'libraries/PHPExcel.php');
				require_once(APPPATH.'libraries/PHPExcel/IOFactory.php');
				
				$objPHPExcel = new PHPExcel();
				$objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
				$objPHPExcel->setActiveSheetIndex(0);
				
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'Data Pelanggan');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 2, 'Total:');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 2, $data['total']);
				
				$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
				
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 4, 'ID');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 4, 'Name Lengkap');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 4, 'No. Handphone');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 4, 'Email');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 4, 'Tanggal Daftar');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 4, 'Saldo');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 4, 'Status');
				
				$objPHPExcel->getActiveSheet()->getStyle('A4:G4')->getFont()->setBold(true);
				
				$row = 5;
				
				foreach($data['reports'] as $report) {
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $report['user_id']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $report['name']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $report['telephone']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $report['email']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, format_date($report['date_added']));
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $report['balance']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $report['status']);
					
					$row++;
				}
 
				$objPHPExcel->setActiveSheetIndex(0);
				
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
				
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="user-report-'.date('dmYHis').'.xls"');
				header('Cache-Control: max-age=0');
	 
				$objWriter->save('php://output');
			break;
			
			default:
				$this->load->layout(false)->view('admin/user_export', $data);
			break;
		}
	}

	/**
	 * Print
	 *
	 * @access public
	 * @return void
	 */
	public function excel()
	{
		if ($this->input->post(null, true)) {

			$this->load->helper('format');

			$job_status = $this->input->post('job_status');
			$province = $this->input->post('province');
			$birthday = $this->input->post('birthday');
			$active = $this->input->post('active');
			
			$this->db
			->select('u.user_id, u.name, u.email, u.telephone, u.active, u.date_added, u.job, u.job_status, u.address_id, u.dob,
			(select concat(address,", ",subdistrict,", ",city,", ",province,", ",postcode) from address b where b.address_id = u.address_id) as address', false)
			->from('user u');

			if ($this->input->post('job_status')) {
				$this->db->where('u.job_status', $this->input->post('job_status'));
			}
			if ($this->input->post('birthday')) {
				if($this->input->post('birthday') == 1) {
					$this->db->where('month(u.dob)', date('m'));
				}
				else if($this->input->post('birthday') == 2) {
					$this->db->where('month(u.dob)', date('m', strtotime("+1 month", strtotime(date('Y-m-01')))));
				}
			}
			if ($this->input->post('active')) {
				if($this->input->post('active') == 1) {
					$this->db->where('u.active', 1);
				}
				else if($this->input->post('active') == 2) {
					$this->db->where('u.active', 0);
				}
			}
			if ($this->input->post('province')) {
				$this->db->like('(select concat(address,", ",subdistrict,", ",city,", ",province,", ",postcode) from address b where b.address_id = u.address_id)', $this->input->post('province'));
			}

			$users = $this->db->order_by('u.name')->get()->result_array();

			$this->load->library('PHPExcel');

			require_once(APPPATH.'libraries/PHPExcel.php');
			require_once(APPPATH.'libraries/PHPExcel/IOFactory.php');

			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
			$objPHPExcel->setActiveSheetIndex(0);

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'NAMA');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'EMAIL');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'NO. TELEPON / HANDPHONE');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'TANGGAL DAFTAR');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'TEMPAT KERJA');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, 'STATUS KERJA');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, 'ALAMAT');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, 'ULANG TAHUN');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, 1, 'AKTIF');

			$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);
			
			$row = 2;
			
			foreach($users as $user) {

				$date_added = date('d-m-Y', strtotime($user['date_added']));
				$dob = date('d-m-Y', strtotime($user['dob']));
				if($user['active'] == '1') $status = 'Aktif'; else $status = 'Tidak Aktif';
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $user['name']);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $user['email']);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $user['telephone']);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $date_added);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $user['job']);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $user['job_status']);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $user['address']);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $dob);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $status);
				
				$row++;
			}
			
			$filename = 'daftar-pelanggan-'.date('dmY-Hi').'.xlsx';
			
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