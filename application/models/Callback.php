<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Callback extends MY_Controller
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
		$this->load->model('order_history_model');
	}
	
	/**
	 * Index
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		$data = '{
			"event": "paylater.payment",
			"business_id": "5f27a14a9bf05c73dd040bc8",
			"created": "2020-11-11T16:28:52Z",
			"data": {
				"id": "plc_7eb15231-a2e1-4652-b667-0481f6d74f71",
				"customer_id": "49d056bd-21e5-4997-85f2-2127544c2196",
				"plan_id": "plp_3d88d952-9505-4ed7-84d3-e8639e99e9c4",
				"business_id": "5f27a14a9bf05c73dd040bc8",
				"reference_id": "order_id_123",
				"checkout_method": "ONE_TIME_PAYMENT",
				"channel_code": "PH_BILLEASE",
				"currency": "PHP",
				"amount": 1234.56,
				"refunded_amount": null,
				"order_items": [{
					"type": "PRODUCT",
					"reference_id": "SKU_123-456-789",
					"name": "Dyson Vacuum",
					"net_unit_amount": 1234.56,
					"quantity": 1,
					"url": "https://www.zngmyhome.com/dyson_vacuum",
					"category": "Electronics",
					"subcategory": "Appliances",
					"description": "A very powerful vacuum",
					"metadata": null
				}],
				"success_redirect_url": "https://merchant.com/order/confirm",
				"failure_redirect_url": "https://merchant.com/order/fail",
				"status": "SUCCEEDED",
				"created": "2020-11-11T16:23:52Z",
				"updated": "2020-11-11T16:23:52Z",
				"actions": {
					"desktop_web_checkout_url": "https://webcheckout.this/",
					"mobile_web_checkout_url": "https://webcheckout.this/",
					"mobile_deeplink_checkout_url": "app://deeplinkcheckout.this/"
				},
				"callback_url": "https://webhook.me/gethooked",
				"payment_method_id": null,
				"voided_at": null,
				"refunds": null,
				"metadata": null
			}
		}';
		$data = json_decode( $data );
		$order = $this->order_model->get_order_by_payment_plan($data->id);
		if($order){
			$metode_pembayaran = $data->channel_code;
			if($metode_pembayaran == "ID_AKULAKU") $metode_pembayaran = "Akulaku";
			elseif($metode_pembayaran == "ID_KREDIVO") $metode_pembayaran = "Kredivo";
			elseif($metode_pembayaran == "ID_UANGME") $metode_pembayaran = "Uangme";
			elseif($metode_pembayaran == "ID_INDODANA") $metode_pembayaran = "Indodana";

			if(json_decode( $data )->data->status == "SUCCEEDED"){
				$this->order_model->confirm(
					$order->id, 
					$this->config->item('order_paid_status_id'), 
					'Pembayaran Order nomor #' .$order['order_id'] . 'dengan metode pembayaran ' .$metode_pembayaran. ': BERHASIL', 
					true
				);
			}elseif(json_decode( $data )->data->status == "FAILED"){
				$this->order_model->confirm(
					$order->id, 
					11, 
					'Pembayaran Order nomor #' .$order['order_id'] . 'dengan metode pembayaran ' .$metode_pembayaran. ': GAGAL', 
					true
				);
			}
			return 200;
		}else{
			return 404;
		}

	}
}