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
		$response = file_get_contents('php://input');
		$response = json_decode($response);

		$order = $this->order_model->get_order_by_payment_plan($response->data->id);
		if($order){
			$metode_pembayaran = $response->data->channel_code;
			if($metode_pembayaran == "ID_AKULAKU") $metode_pembayaran = "Akulaku";
			elseif($metode_pembayaran == "ID_KREDIVO") $metode_pembayaran = "Kredivo";
			elseif($metode_pembayaran == "ID_UANGME") $metode_pembayaran = "Uangme";
			elseif($metode_pembayaran == "ID_INDODANA") $metode_pembayaran = "Indodana";

			if($response->data->status == "SUCCEEDED"){
				$this->order_model->confirm(
					$order['order_id'],
					$this->config->item('order_paid_status_id'), 
					'Pembayaran Order nomor #' .$order['order_id'] . 'dengan metode pembayaran ' .$metode_pembayaran. ': BERHASIL', 
					true
				);
			}elseif($response->data->status == "FAILED"){
				$this->order_model->confirm(
					$order->id, 
					11, 
					'Pembayaran Order nomor #' .$order['order_id'] . 'dengan metode pembayaran ' .$metode_pembayaran. ': GAGAL', 
					true
				);
			}
			return json_encode(["status" => 200]);
		}else{
			return json_encode(["status" => 404]);
		}

	}
}