<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_confirmation extends MY_Controller
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
		$this->load->model('payment_confirmation_model');
		$this->load->library('form_validation');
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
		$this->load->helper('form');
		
		$redirect = $this->input->get('redirect') ? $this->input->get('redirect') : site_url();
		$order = false;
		$query = '';
		
		if ($this->input->get('order_id')) {
			$this->load->model('order_model');
			
			$order = $this->order_model->get_order($this->input->get('order_id'));
			
			if ( ! $order) {
				show_404();
			}
			
			$query = '?order_id='.$this->input->get('order_id').'&redirect='.$redirect;
		} else {
			$query = '';
		}
		
		$data['action'] = site_url('payment_confirmation/validate'.$query);
		$data['success'] = $this->session->flashdata('success');
		
		if ($order) {
			$this->load->library('shopping_cart');
			
			$this->shopping_cart->clear();
			
			$this->session->unset_userdata('shipping_method');
			$this->session->unset_userdata('shipping_methods');
			$this->session->unset_userdata('payment_method');
			$this->session->unset_userdata('payment_methods');
			$this->session->unset_userdata('comment');
			$this->session->unset_userdata('order_id');
			$this->session->unset_userdata('coupon');
			$this->session->unset_userdata('totals');
			$this->session->unset_userdata('guest');
			
			$data['order_id'] = $order['order_id'];
			$data['invoice_no'] = $order['invoice_no'];
			$data['amount'] = $order['total'];
			$data['telephone'] = $order['user_telephone'];
			$data['email'] = $order['user_email'];
			$data['from_name'] = $order['user_name'];
		} else {
			$data['order_id'] = '';
			$data['invoice_no'] = '';
			$data['amount'] = '';
			$data['from_name'] = '';
			$data['telephone'] = '';
			$data['email'] = '';
		}
		
		$this->load->model('bank_account_model');
		$data['bank_accounts'] = $this->bank_account_model->get_all_by(['active' => 1]);
		$data['mobile'] = $this->agent->is_mobile();

		$this->load
		->title('Konfirmasi Pembayaran')
		->breadcrumb('Konfirmasi Pembayaran', site_url('payment_confirmation'))
		->view('payment_confirmation', $data);
	}
	
	/**
	 * Validate
	 * 
	 * @access public
	 * @return void
	 */
	public function validate()
	{
		$json = array();
		
		$this->form_validation
		->set_rules('invoice_no', 'No. Invoice', 'trim|required|callback__check_invoice_no')
		->set_rules('to_bank', 'Bank Tujuan', 'trim|required')
		->set_rules('email', 'Email', 'trim|required|valid_email')
		->set_rules('telephone', 'No. Telepon', 'trim|required|numeric|min_length[3]|max_length[12]')
		//->set_rules('from_name', 'Nama Pemilik Rekening', 'trim|required|min_length[3]')
		//->set_rules('from_bank', 'Nama Pemilik Rekening', 'trim|required|min_length[3]')
		//->set_rules('from_number', 'Nama Pemilik Rekening', 'trim|required|min_length[3]')
		->set_rules('amount', 'Nilai Transfer', 'trim|numeric|callback__check_amount')
		->set_error_delimiters('', '');
		
		if ($this->form_validation->run() == false) {
			foreach ($this->form_validation->get_errors() as $field => $error) {
				$json['error'][$field] = $error;
			}
		} else {
			$post = $this->input->post(null, true);
			$post['date_transfered'] = date('Y-m-d', strtotime($post['date_transfered']));
			$post['date_added'] = date('Y-m-d H:i:s', time());
			
			$this->load->model('order_model');
			
			$order = $this->order_model->get_by(['invoice_no' => $post['invoice_no']]);
			
			if ($order) {
				$post['order_id'] = $order['order_id'];
				
				$this->payment_confirmation_model->insert($post);
			
				$this->load->library('email');
				$this->load->helper('format');
				
				$data['logo'] = $this->image->resize($this->config->item('logo'), 120);
				$data['name'] = html_entity_decode($post['from_name'], ENT_QUOTES, 'UTF-8');
				$data['invoice_no'] = html_entity_decode($this->input->post('invoice_no'), ENT_QUOTES, 'UTF-8');
				$data['amount'] = format_money($post['amount']);
				$data['from_bank'] = html_entity_decode($this->input->post('from_bank'), ENT_QUOTES, 'UTF-8');
				$data['from_name'] = html_entity_decode($post['from_name'], ENT_QUOTES, 'UTF-8');
				$data['from_number'] = html_entity_decode($post['from_number'], ENT_QUOTES, 'UTF-8');
				$data['to_bank'] = strtoupper($post['to_bank']);
				$data['date_transfered'] = date('d/m/Y', strtotime($post['date_transfered']));
				$data['email'] = $this->input->post('email');
				$data['telephone'] = html_entity_decode($post['telephone'], ENT_QUOTES, 'UTF-8');
				$data['note'] = html_entity_decode($post['note'], ENT_QUOTES, 'UTF-8');
				
				$this->email->initialize();
				$this->email->from('halo@mpmindo.id', $this->config->item('site_name'));
				$this->email->to($data['email']);
				$this->email->cc($this->config->item('email'));
				$this->email->subject('Konfirmasi Pembayaran : #'.html_entity_decode($data['invoice_no'], ENT_QUOTES, 'UTF-8'));
				$this->email->message($this->load->layout(false)->view('emails/payment_confirmation', $data, true));
				
				if ($this->email->send()) {
					$this->load->model('order_history_model');
					
					$this->order_history_model->insert([
						'order_id' => (int)$order['order_id'],
						'updated_by' => 'Customer ('.$data['name'].')',
						'notify' => 1,
						'comment' => 'Konfirmasi Pembayaran'
					]);
					
					$this->session->set_flashdata('action_success', [
						'title' => 'Konfirmasi Pembayaran',
						'content' => 'No. pesanan anda : <b>'.$data['invoice_no'].'</b>.<br><br>Mohon menunggu proses validasi maksimal 6 jam. Kami akan segera memproses pesanan anda jika pembayaran telah berhasil kami validasi.<br>Kami juga telah mengirimkan bukti konfirmasi ini ke alamat email Anda.',
						'redirect' => $this->input->get('redirect') ? $this->input->get('redirect') : site_url()
					]);
					
					$json['redirect'] = site_url('page/success');
				} else {
					exit($this->email->print_debugger());
				}
			}
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	/**
	 * Check invoice
	 * 
	 * @access public
	 * @param string $invoice_no
	 * @return bool
	 */
	public function _check_invoice_no($invoice_no)
	{	
		$check = $this->order_model->get_by(['invoice_no' => $invoice_no]);
		
		if ($check) {
			return true;
		} else {
			$this->form_validation->set_message('_check_invoice_no', 'Kode pembelian tidak valid!');
			return false;
		}
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
		
		$upload_path = DIR_IMAGE.'misc';
		
		if ( ! file_exists($upload_path)) {
			@mkdir($upload_path, 0777);
		}
		
		$this->load->library('upload', array(
			'upload_path' => $upload_path,
			'allowed_types' => 'gif|jpg|png|jpeg|heif|tiff',
			'max_size' => '5120',
			'overwrite' => true,
			'remove_spaces' => true,
			'encrypt_name' => true
		));

		if ( ! $this->upload->do_upload()) {
			$json['error'] = $this->upload->display_errors('', '');
		} else {
			$upload_data = $this->upload->data();
			
			$json['file'] = substr($upload_data['full_path'], strlen(FCPATH.DIR_IMAGE));
			$json['success'] = 'File uploaded!';
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	/**
	 * Check amount
	 * 
	 * @access public
	 * @param float $amount
	 * @return bool
	 */
	public function _check_amount($amount)
	{	
		$check = $this->order_model->get_by([
			'invoice_no' => $this->input->post('invoice_no'),
			'total' => (float)$amount
		]);
		
		if ($check) {
			return true;
		} else {
			$this->form_validation->set_message('_check_amount', 'Nilai pembayaran tidak cocok!');
			return false;
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
		if ($pc = $this->payment_confirmation_model->get($this->input->get('payment_confirmation_id'))) {
			$data['pc'] = $pc;
		} else {
			$data = false;
		}
		
		$this->load->helper('format');
		
		$this->output->set_output(json_encode(array(
			'content' => $this->load->layout(false)->view('payment_confirmation_detail', $data, true)
		)));
	}
}