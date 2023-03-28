<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Xendit\Xendit;

class Payment extends MY_Controller
{
	/**
	 * Order prefix
	 * 
	 * @var string
	 * @access private
	 */
	private $_order_id_prefix = 'ORDER-';
	
	/**
	 * Constructor
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('shopping_cart');
		$this->load->config('midtrans');
		\Midtrans\Config::$serverKey = $this->config->item('server_key');
		\Midtrans\Config::$isProduction = (bool)$this->config->item('is_production');
		\Midtrans\Config::$isSanitized = (bool)$this->config->item('is_sanitized');
		\Midtrans\Config::$is3ds = (bool)$this->config->item('is_3ds');		
	}

	
	
	/**
	 * Token
	 * 
	 * @access public
	 * @return void
	 */
	public function xendit()
	{
		$this->load->model('order_model');
		$this->load->library('total');

		if ($order = $this->order_model->get_order($this->session->userdata('order_id'))) {
			$order_products = $this->order_model->get_order_products($order['order_id']);
			$order_totals = $this->order_model->get_order_totals($order['order_id']);
			$item_details = array();

			$code_chanel = explode('.', $order['payment_code']);
			$code_chanel = $code_chanel[1];
			$plan = $this->session->userdata('plan_paylater');

			$parameter_plan = [
				"plan_id" => $plan[$code_chanel],
				"reference_id" => "mpmindo" . time(),
				"checkout_method" => "ONE_TIME_PAYMENT",
				"success_redirect_url" => base_url('checkout/success'),
				"failure_redirect_url" => base_url('payment/failure')
			
			];
			$response = \Xendit\PayLater::createPayLaterCharge($parameter_plan);
			
			$this->order_model->update_plan_id($this->session->userdata('order_id'), $response['id']);
			if( isset($response['actions']['desktop_web_checkout_url']) ) echo $response['actions']['desktop_web_checkout_url'];
			elseif( isset($response['actions']['mobile_web_checkout_url']) ) echo $response['actions']['mobile_web_checkout_url'];
			elseif( isset($response['actions']['mobile_deeplink_checkout_url']) ) echo $response['actions']['mobile_deeplink_checkout_url'];
		}
	}
	
	/**
	 * Token
	 * 
	 * @access public
	 * @return void
	 */
	public function token()
	{
		$this->load->model('order_model');
		
		if ($order = $this->order_model->get_order($this->session->userdata('order_id'))) {
			$order_products = $this->order_model->get_order_products($order['order_id']);
			$order_totals = $this->order_model->get_order_totals($order['order_id']);
			$item_details = array();
			
			foreach ($order_products as $order_product) {
				$item_details[] = array(
					'id' => (int)$order_product['product_id'],
					'price' => round((float)$order_product['price'], 0),
					'quantity' => (int)$order_product['quantity'],
					'name' => $order_product['name'],
				);
			}
			
			foreach ($order_totals as $order_total) {
				if ($order_total['code'] != 'total' && $order_total['code'] != 'subtotal') {
					$item_details[] = array(
						'id' => $order_total['code'],
						'price' => round((float)$order_total['value'], 0),
						'quantity' => 1,
						'name' => $order_total['title'],
					);
				} elseif ($order_total['code'] == 'total') {
					$transaction_details = array(
						'order_id' => $this->_order_id_prefix.$order['order_id'],
						'gross_amount' => round((float)$order_total['value'], 0),
					);
				}
			}
			
			$name = explode(' ', $order['user_name']);
			
			$address = array(
				'first_name' => isset($name[0]) ? $name[0] : '',
				'last_name' => isset($name[1]) ? $name[1] : '',
				'address' => $order['user_address'],
				'city' => $order['user_city'],
				'postal_code' => $order['user_postcode'],
				'phone' => $order['user_telephone'],
				'country_code' => 'IDN'
			);
				
			$customer_details = array(
				'first_name' => isset($name[0]) ? $name[0] : '',
				'last_name' => isset($name[1]) ? $name[1] : '',
				'email' => $order['user_email'],
				'phone' => $order['user_telephone'],
				'billing_address' => $address,
				'shipping_address' => $address
			);
			
			$transaction = array(
				'transaction_details' => $transaction_details,
				'customer_details' => $customer_details,
				'item_details' => $item_details
			);
			
			$payment_code = explode('.', $order['payment_code']);
			
			if (isset($payment_code[1])) {
				$transaction['enabled_payments'] = array($payment_code[1]);
			}
			
			$snapToken = \Midtrans\Snap::getSnapToken($transaction);
			$this->output->set_output($snapToken);
		}
	}
	
	public function confirm()
	{
		if ($this->input->post('result_data')) {
			$response = json_decode($this->input->post('result_data'));
		} elseif ($this->input->post('response')) {
			$response = json_decode($this->input->post('response'));
		} else {
			$response = false;
		}
		
		if ($response) {
			$order_id = str_replace($this->_order_id_prefix, '', $response->order_id);
			$data = array();
			$this->load->model('order_model');
			
			$transaction_status = $response->transaction_status;
			$payment_type = $response->payment_type;
			$channel = array('bank_transfer', 'echannel', 'cstore', 'xl_tunai');
			
			if ($transaction_status == 'capture' || $transaction_status == 'settlement') {
				$this->order_model->confirm(
					$order_id, 
					$this->config->item('order_paid_status_id'), 
					'Status pembayaran untuk order #'.$order_id.' : LUNAS', 
					true
				);
				redirect('checkout/success');
			} elseif ($transaction_status == 'deny') {
				$this->failure();
			} elseif ($transaction_status == 'pending' && in_array($payment_type, $channel)) {
				switch ($payment_type) {
					case 'bank_transfer':
						if ($transaction_status == 'settlement') {
							$this->order_model->confirm(
								$order_id, 
								$this->config->item('order_paid_status_id'), 
								'Status pembayaran untuk order #'.$order_id.' : LUNAS', 
								true
							);
							redirect('checkout/success');
						} else {
						
							if (isset($response->va_numbers[0]->bank)) {
								$data['data']= array(
									'payment_type' => $payment_type,
									'payment_method' => 'BCA Virtual Account',
									'instruction' => $response->pdf_url,
									'payment_code' => $response->bca_va_number,
								);
							} else{
								$data['data']= array(
									'payment_type' => $payment_type,
									'payment_method' => 'Permata Virtual Account',
									'instruction' => $response->pdf_url,
									'payment_code' => $response->permata_va_number,
								);
							}
							
							$this->order_model->confirm(
								$order_id, 
								$this->config->item('order_status_id'), 
								'Status pembayaran untuk order #'.$order_id.' : PENDING', 
								true
							);
						}
					break;
					
					case 'echannel':
						if ($transaction_status == 'settlement') {
							$this->order_model->confirm(
								$order_id, 
								$this->config->item('order_paid_status_id'), 
								'Status pembayaran untuk order #'.$order_id.' : LUNAS', 
								true
							);
							redirect('checkout/success');
						} else {

							$data['data']= array(
								'payment_type' => $payment_type,
								'payment_method' => 'Mandiri Bill Payment',
								'instruction'=> $response->pdf_url,
								'company_code' => $response->biller_code,
								'payment_code' => $response->bill_key,
							);
							
							$this->order_model->confirm(
								$order_id, 
								$this->config->item('order_status_id'), 
								'Status pembayaran untuk order #'.$order_id.' : PENDING', 
								true
							);
						}
					break;
					
					case 'cstore':
						if ($transaction_status == 'settlement') {
							$this->order_model->confirm(
								$order_id, 
								$this->config->item('order_paid_status_id'), 
								'Status pembayaran untuk order #'.$order_id.' : LUNAS', 
								true
							);
							redirect('checkout/success');
						} else {

							$data['data']= array(
								'payment_type' => $payment_type,
								'payment_method' => 'Indomaret',
								'instruction'=> $response->pdf_url,
								'payment_code' => $response->payment_code,
								'expire' => $response->indomaret_expire_time
							);
							
							$this->order_model->confirm(
								$order_id, 
								$this->config->item('order_status_id'), 
								'Status pembayaran untuk order #'.$order_id.' : PENDING', 
								true
							);
						}
					break;
					
					case 'xl_tunai':
						if ($transaction_status == 'settlement') {
							$this->order_model->confirm(
								$order_id, 
								$this->config->item('order_paid_status_id'), 
								'Status pembayaran untuk order #'.$order_id.' : LUNAS', 
								true
							);
							redirect('checkout/success');
						} else {
							$xl_tunai_instruction  = '<p><strong>Cara pembayaran XL Tunai</strong></p>';
							$xl_tunai_instruction .= '<p>Berikut ini adalah tahapan cara pembayaran melalui XL Tunai:</p>';
							$xl_tunai_instruction .= '<ol>';
							$xl_tunai_instruction .= '<li>Tekan panggilan *123*120#</li>';
							$xl_tunai_instruction .= '<li>Balas dengan memilih nomor \'4\' untuk \'Belanja Online\'</li>';
							$xl_tunai_instruction .= '<li>Input XL Tunai Merchant Code</li>';
							$xl_tunai_instruction .= '<li>Input XL Tunai Order ID</li>';
							$xl_tunai_instruction .= '<li>Input XL Tunai PIN</li>';
							$xl_tunai_instruction .= '<li>Anda akan mendapatkan konfirmai SMS dari XL</li>';
							$xl_tunai_instruction .= '</ol>';
						
							$data['data']= array(
								'payment_type' => $payment_type,
								'payment_method' => 'XL Tunai',
								'instruction'=> $xl_tunai_instruction,
								'xl_tunai_order_id' => $response->xl_tunai_order_id,
								'merchant_code' => $response->xl_tunai_merchant_id,
								'expire' => $response->xl_expiration
							);
							
							$this->order_model->confirm(
								$order_id, 
								$this->config->item('order_status_id'), 
								'Status pembayaran untuk order #'.$order_id.' : PENDING', 
								true
							);
						}
					break;
				}
				
				$this->shopping_cart->clear();
				
				$this->session->unset_userdata('shipping_method');
				$this->session->unset_userdata('shipping_methods');
				$this->session->unset_userdata('payment_method');
				$this->session->unset_userdata('payment_methods');
				$this->session->unset_userdata('comments');
				$this->session->unset_userdata('order_id');
				$this->session->unset_userdata('coupon');
				$this->session->unset_userdata('totals');
				
				$this->load->title('Menunggu Pembayaran');
				$this->load->layout('default')->view('payment/midtrans_instruction', $data);
			}
		} else {
			show_404();
		}
	}
	
	public function notification()
	{
		$this->load->model('order_model');
		$this->load->model('order_history_model');

		$notif = new \Midtrans\Notification();
		
		if ($notif) {
			$transaction = $notif->transaction_status;
			$type = $notif->payment_type;
			$order_id = str_replace($this->_order_id_prefix, '', $notif->order_id);
			$fraud = $notif->fraud_status;

			if ($transaction == 'capture') {
				if ($type == 'credit_card') {
					if ($fraud == 'challenge') {
						$order_status_id = $this->config->item('order_status_id');
						$comment = 'Status pembayaran untuk order #'.$order_id.' : CHALLENGED BY FDS';
					} elseif ($fraud == 'accept') {
						$order_status_id = $this->config->item('order_paid_status_id');
						$comment = 'Status pembayaran untuk order #'.$order_id.' : LUNAS';
					}
				}
			} else if ($transaction == 'settlement') {
				$orderdata = $this->order_model->get_order($order_id);
				if($orderdata['order_status_id'] == $this->config->item('order_status_id')) {
					$order_status_id = $this->config->item('order_paid_status_id');
				}
				else {
					$order_status_id = $orderdata['order_status_id'];
				}
				$comment = 'Status pembayaran untuk order #'.$order_id.' : SETTLEMENT';
			} else if ($transaction == 'pending') {
				$orderdata = $this->order_model->get_order($order_id);
				if($orderdata['order_status_id'] == $this->config->item('order_status_id')) {
					$order_status_id = $this->config->item('order_status_id');
				}
				else {
					$order_status_id = false;
				}
				$comment = 'Status pembayaran untuk order #'.$order_id.' : PENDING';
			} else if ($transaction == 'deny') {
				$order_status_id = $this->config->item('order_cancel_status_id');
				$comment = 'Status pembayaran untuk order #'.$order_id.' : DITOLAK';
			} else if ($transaction == 'expire') {
				$order_status_id = $this->config->item('order_cancel_status_id');
				$comment = 'Status pembayaran untuk order #'.$order_id.' : KADALUARSA';
			} else {
				$order_status_id = false;
			}
			
			if ($order_status_id) {

				$this->order_model->update($order_id, array(
					'order_status_id' => (int)$order_status_id
				));
			
				$this->order_history_model->insert(array(
					'order_id' => $order_id,
					'order_status_id' => (int)$order_status_id,
					'notify' => true,
					'comment' => $comment
				));
			}
			
			$this->output->set_output('Notification received');
		} else {
			$this->output->set_output('Not found result data!');
		}
	}
	
	public function failure()
	{
		$this->load->view('payment/failure');
	}
}