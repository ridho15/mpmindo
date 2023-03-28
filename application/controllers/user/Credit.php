<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Credit extends User_Controller
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
	}
	
	public function index()
	{
		$code = $this->input->post('code');
		
		if ($this->input->post(null, true)) {
			$this->load->library('datatables');
			$this->load->helper('format');
		
			$this->datatables
			->select('user_transaction_id, approved, @uti := user_transaction_id, description, date_added, (CASE WHEN amount > 0 THEN amount ELSE NULL END) AS debit, (CASE WHEN amount < 0 THEN amount * (-1) ELSE NULL END) AS credit, (SELECT SUM(amount) FROM user_transaction ut WHERE ut.user_transaction_id < @uti AND ut.user_id = '.(int)$this->user->user_id().') + amount as balance', false)
			->from('user_transaction')
			->where('user_id', $this->user->user_id());
			
			if ($code) {
				$this->datatables->where('code', $code);
			}
			
			$this->datatables->edit_column('date_added', '$1', 'format_date(date_added)')
			->edit_column('debit', '$1', 'format_money(debit)')
			->edit_column('credit', '$1', 'format_money(credit)')
			->edit_column('balance', '$1', 'format_money(balance)');
			
			$this->output->set_output($this->datatables->generate('json'));
		} else {
			$this->load->library('currency');
			$this->load->helper('form');
			$data['balance'] = $this->currency->format($this->user->get_balance());
			$data['code'] = $code;
			$data['success'] = $this->session->flashdata('success');
			$this->load->title('DompetKu')->view('user/credit', $data);	
		}
	}
	
	/**
	 * Request SMS code for payout request
	 * 
	 * @access public
	 * @return void
	 */
	public function request_code()
	{
		check_ajax();
		
		$json = array();
		
		$this->load->helper('string');
		$this->load->library('sms');
		$this->lang->load('user_sms');
		
		$code = random_string('numeric', 6);
		$date = time() + 3600;
		$message = sprintf(lang('sms_payout_confirm'), $code, date('d/m/Y H:i', $date));
		
		$user = $this->user_model->get($this->user->user_id());
		
		if ($user && $this->sms->send($user['telephone'], $message)) {
			$this->load->model('verification_model');
						
			$verification = array(
				'user_id' => $this->user->user_id(),
				'date_expired' => date('Y-m-d H:i:s', $date),
				'telephone' => $user['telephone'],
				'code' => $code,
				'type' => 'payout',
			);
						
			$this->verification_model->insert($verification);
			$this->user_model->update($this->user->user_id(), array('telephone' => $user['telephone']));
						
			$json['success'] = 'Kode keamanan telah dikirim melalui SMS ke nomor <b>'.$user['telephone'].'</b>';
		} else {
			$json['error'] = 'System error, please contact our administrator!';
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	public function payout_validate()
	{
		$json = array();
		
		$amount = $this->input->post('amount');
		
		if ((float)$amount > $this->user->get_balance()) {
			$json['error'] = 'Saldo anda tidak cukup!';
		}
		
		$this->load->model('verification_model');
		
		$param = array(
			'code' => $this->input->post('code'), 
			'type' => 'payout', 
			'user_id' => $this->user->user_id()
		);
		
		if (!$json) {
			if ($verification = $this->verification_model->get_by($param)) {
				if (strtotime($verification['date_expired']) > strtotime(date('Y-d-m H:i:s', time()))) {
					$this->verification_model->delete_by(array('user_id' => $this->user->user_id(), 'type' => 'payout'));
					$this->load->model('user_transaction_model');
					$this->load->model('bank_model');
					$this->load->library('currency');
					
					$transaction = array(
						'user_id' => $this->user->user_id(),
						'description' => 'Penarikan dana',
						'amount' => -$amount,
						'code' => 'payout',
						'date_added' => date('Y-m-d H:i:s', time()),
						'approved' => 1
					);
					
					$transaction_id = $this->user_transaction_model->insert($transaction);
					
					if ($bank = $this->bank_model->get_by('code', $this->input->post('bank_code'))) {
						if ($bank['charge'] > 0) {
							$transaction = array(
								'user_id' => $this->user->user_id(),
								'description' => 'Biaya transfer bank transaksi #'.$transaction_id,
								'amount' => -$bank['charge'],
								'code' => 'charge',
								'date_added' => date('Y-m-d H:i:s', time()),
								'approved' => 1
							);
							
							$this->user_transaction_model->insert($transaction);
						}
					}
					
					$json['success'] = 'Permintaan penarikan dana telah berhasil. Mohon menunggu proses pencairan maksimal dalam 3x24 jam kerja.';
					$this->session->set_flashdata('success', $json['success']);
				} else {
					$json['error'] = 'Kode verifikasi sudah kadaluarsa!';
				}
			} else {
				$json['error'] = 'Kode verifikasi tidak valid!';
			}
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	public function payout_request()
	{
		$data['action'] = user_url('credit/payout_validate');
		
		$this->load->helper('form');
		
		if ($user = $this->user_model->get($this->user->user_id())) {
			if ($user['bank_code'] && $user['bank_account_name'] && $user['bank_account_number']) {
				$data['error'] = false;
			} else {
				$data['error'] = 'Anda belum memiliki data rekening bank. Silakan tambahkan di <a href="'.site_url('user/profile/edit').'">sini</a>';
			}
			
			$this->load->model('bank_model');
			
			$bank = $this->bank_model->get_by('code', $user['bank_code']);
			
			if ($bank) {
				$this->load->library('currency');
				
				$data['bank_code'] = $bank['code'];
				$data['bank_name'] = $bank['name'];
				$data['bank_account_name'] = $user['bank_account_name'];
				$data['bank_account_number'] = $user['bank_account_number'];
				$data['bank_charge'] = $bank['charge'] > 0 ? $this->currency->format($bank['charge']) : false;
			} else {
				$data['bank_code'] = '';
				$data['bank_name'] = '';
				$data['bank_account_name'] = '';
				$data['bank_account_number'] = '';
				$data['bank_charge'] = false;
			}
			
			return $this->load->layout(false)->view('user/credit_payout_form', $data);
		}
	}
	
	public function topup()
	{
		if ($this->input->post()) {
			$json = array();
			if ($this->input->post('amount') < 10000) {
				$json['error'] = 'Minimal top up adalah Rp 10.000!';
			}
			
			if (strlen($this->input->post('payment_method')) == 0) {
				$json['error'] = 'Silakan pilih metode pembayaran!';
			}
			
			if ( ! $json) {
				$this->load->model('user_transaction_model');
				$this->load->model('payment_model');
				
				if ($payment = $this->payment_model->get_by('code', $this->input->post('payment_method'))) {
					$payment_method = $payment['name'];
				} else {
					$payment_method = '';
				}
				
				$transaction = array(
					'user_id' => (int)$this->user->user_id(),
					'description' => 'Topup saldo',
					'payment_code' => $this->input->post('payment_method'),
					'payment_method' => $payment_method,
					'amount' => (float)$this->input->post('amount'),
					'code' => 'topup',
					'date_added' => date('Y-m-d H:i:s', time())
				);
				
				if ($this->user_transaction_model->insert($transaction)) {
					$json['success']  = '<b>Topup menunggu pembayaran Anda!</b><br>';
					$json['success'] .= $this->config->item('instruction', $this->input->post('payment_method'));
					
					$this->session->set_flashdata('success', $json['success']);
				}
			}
			
			$this->output->set_output(json_encode($json));
		} else {
			$this->load->helper('form');
			$this->load->helper('string');
			
			$data['unique'] = random_string('numeric', 3);
			$data['action'] = user_url('credit/topup');
			
			$method_data = array();
					
			$this->load->model('payment_model');
	
			$results = $this->payment_model->get_all_by(array('active' => 1));
			
			$data['payment_methods'] = array();
			
			foreach ($results as $result) {
				if ($this->config->item('active', $result['code']) && (strpos($result['code'], 'transfer') != false)) {
					$model = strtolower($result['code']).'_model';
					$this->load->model($model);
					
					$method = $this->{$model}->get_method(array(), 0);
					
					if ($method) {
						$data['payment_methods'][$result['code']] = $method;
					}
				}
			}
	
			$sort_order = array(); 
	
			foreach ($method_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}
	
			array_multisort($sort_order, SORT_ASC, $method_data);
			
			return $this->load->layout(false)->view('user/credit_topup_form', $data);
		}
	}
}