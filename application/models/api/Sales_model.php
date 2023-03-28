<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'models/api/Base_model.php';

class Sales_model extends Base_model
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
		$this->load->model('api/places_model', 'api_places_model');
		$this->load->model('api/stocks_model', 'api_stocks_model');
		$this->load->model('api/inventories_model', 'api_inventories_model');
		$this->load->model('api/notifications_model', 'api_notifications_model');
		$this->load->model('api/products_model', 'api_products_model');
	}
	
	/**
	 * Create sales on inventory system
	 * 
	 * @access public
	 * @param int $order_id
	 * @return void
	 */
	public function create_sales($order_id)
	{
		// $order_tax = $this->get_order_tax($order_id);
		// $order_no_tax = $this->get_order_no_tax($order_id);
		
		// if ($order_tax) {
		// 	$total_sales = 0;
		// 	$sales_details = array();
		// 	$sales_inventories = array();
		// 	$sales_costs = array();
			
		// 	if ($order_tax['comment']) {
		// 		$description = 'E-Commerce['.$order_tax['invoice_no'].']'.'/'.$order_tax['comment'];
		// 	} else {
		// 		$description = 'E-Commerce['.$order_tax['invoice_no'].']';
		// 	}
			
		// 	if ($order_tax['tax_id']) {
		// 		$description .= '/NPWP: '.$order_tax['tax_id'];
		// 		$description .= '/Nama WP: '.$order_tax['tax_name'];
		// 		$description .= '/Alamat WP: '.$order_tax['tax_address'];
		// 	} else {
		// 		$description .= '/NPWP:-';
		// 	}
			
		// 	$reference_no = $this->get_reference_no();
			
		// 	$sales_data = array(
		// 		'store_id' => (int)$this->config->item('store_id'),
		// 		'reference_no' => $reference_no,
		// 		'sale_date' => date('Y-m-d', strtotime($order_tax['date_added'])),
		// 		'customer_id' => 1,
		// 		'delivery_date' => date('Y-m-d'),
		// 		'sale_status' => 'Baru',
		// 		'created_id' => $this->admin->admin_id(),
		// 		'created_by' => $this->admin->username(),
		// 		'created_date' => date('Y-m-d H:i:s'),
		// 		'deliver_status' => 'Belum Dikirim',
		// 		'receive_status' => 'Belum Diterima',
		// 		'description' => $description,
		// 		'sales_tax_type' => 2,
		// 		'sales_tax_value' => 10,
		// 		'total_sales' => $order_tax['total_sales'],
		// 		'payment_status' => 'Belum Dibayar',
		// 		'customer_invname' => $order_tax['user_name'],
		// 		'customer_invaddress' => $order_tax['user_address'].', '.$order_tax['user_subdistrict'].', '.$order_tax['user_city'].' - '.$order_tax['user_province'],
		// 		'customer_invphone' => $order_tax['user_telephone'],
		// 		'discount_value' => 0,
		// 		'order_id' => $order_tax['order_id']
		// 	);
			
		// 	foreach ($order_tax['products'] as $product) {
		// 		$iproduct = $this->idb
		// 		->select('limit_price, cost, big_capacity')
		// 		->where('id', $product['inventory_product_id'])
		// 		->get('products')
		// 		->row_array();
				
		// 		if ($iproduct) {
		// 			$sales_details[] = array(
		// 				'product_id' => (int)$product['inventory_product_id'],
		// 				'qty' => (int)$product['quantity'],
		// 				'price' => (float)$product['price'],
		// 				'sell_price' => (float)$iproduct['limit_price'],
		// 				'tax_type' => $product['tax_type'],
		// 				'tax_value' => (float)$product['tax_value'],
		// 				'category_percent' => 0,
		// 				'store_percent'=> 0,
		// 				'qty_deliver' => 0,
		// 				'qty_retur' => 0,
		// 				'limit_price' => (float)$iproduct['limit_price'],
		// 				'cost' => (float)$iproduct['cost']
		// 			);
					
		// 			$inventory = $this->api_inventories_model->get_inventory_product((int)$product['inventory_product_id'], 'store', (int)$this->config->item('store_id'));
					
		// 			if ((int)$inventory['balance'] >= (int)$product['quantity']) {
		// 				// stock store mencukupi, semua ambil dari sini
		// 				$sales_inventories[] = array(
		// 					'product_id' => (int)$product['inventory_product_id'],
		// 					'qty' => (int)$product['quantity'],
		// 					'location_type' => 'store',
		// 					'location_id' => (int)$this->config->item('store_id'),
		// 					'is_store_requested' => 0,
		// 					'delivery_type' => 3,
		// 					'deliver_status' => 'Belum Dikirim',
		// 					'receive_status' => 'Belum Diterima',
		// 					'big_capacity_product' => (int)$iproduct['big_capacity'],
		// 					'product_status' => 'ready',
		// 					'qty_deliver' => 0,
		// 					'qty_receive' => 0,
		// 					'qty_customer' => 0,
		// 					'customer_status' => 'Barang Belum Diberikan'
		// 				);
						
		// 				$this->api_inventories_model->update_qty_booking_sale((int)$product['inventory_product_id'], (int)$product['quantity'], 'store', (int)$this->config->item('store_id'), 'add');
		// 			} else {
		// 				// jika stok tidak mencukupi, coba cari di tempat lain
		// 				$quantity = (int)$product['quantity'];
		// 				$inventories = $this->api_inventories_model->get_inventory_product((int)$product['inventory_product_id']);
						
		// 				foreach ($inventories as $inventory) {
		// 					if ($quantity > 0 && $inventory['balance'] <= $quantity) {
		// 						$sales_inventories[] = array(
		// 							'product_id' => (int)$product['inventory_product_id'],
		// 							'qty' => (int)$inventory['balance'],
		// 							'location_type' => $inventory['place_type'],
		// 							'location_id' => (int)$inventory['place_id'],
		// 							'is_store_requested' => 0,
		// 							'delivery_type' => 3,
		// 							'deliver_status' => 'Belum Dikirim',
		// 							'receive_status' => 'Belum Diterima',
		// 							'big_capacity_product' => (int)$iproduct['big_capacity'],
		// 							'product_status' => 'ready',
		// 							'qty_deliver' => 0,
		// 							'qty_receive' => 0,
		// 							'qty_customer' => 0,
		// 							'customer_status' => 'Barang Belum Diberikan'
		// 						);
								
		// 						$this->api_inventories_model->update_qty_booking_sale((int)$product['inventory_product_id'], (int)$inventory['balance'], $inventory['place_type'], (int)$inventory['place_id'], 'add');
								
		// 						$quantity -= $inventory['balance'];
		// 					}
							
		// 					if ($quantity > 0 && $inventory['balance'] > $quantity) {
		// 						$sales_inventories[] = array(
		// 							'product_id' => (int)$product['inventory_product_id'],
		// 							'qty' => (int)$quantity,
		// 							'location_type' => $inventory['place_type'],
		// 							'location_id' => (int)$inventory['place_id'],
		// 							'is_store_requested' => 0,
		// 							'delivery_type' => 3,
		// 							'deliver_status' => 'Belum Dikirim',
		// 							'receive_status' => 'Belum Diterima',
		// 							'big_capacity_product' => (int)$iproduct['big_capacity'],
		// 							'product_status' => 'ready',
		// 							'qty_deliver' => 0,
		// 							'qty_receive' => 0,
		// 							'qty_customer' => 0,
		// 							'customer_status' => 'Barang Belum Diberikan'
		// 						);
								
		// 						$this->api_inventories_model->update_qty_booking_sale((int)$product['inventory_product_id'], (int)$quantity, $inventory['place_type'], (int)$inventory['place_id'], 'add');
								
		// 						$quantity -= $quantity;
		// 					}
		// 				}
						
		// 				if ($quantity) {
		// 					// booking untuk gudang ini jika ada sisa yang belum terpenuhi
		// 					$sales_inventories[] = array(
		// 						'product_id' => (int)$product['inventory_product_id'],
		// 						'qty' => (int)$quantity,
		// 						'location_type' => 'store',
		// 						'location_id' => (int)$this->config->item('store_id'),
		// 						'is_store_requested' => 0,
		// 						'delivery_type' => 3,
		// 						'deliver_status' => 'Belum Dikirim',
		// 						'receive_status' => 'Belum Diterima',
		// 						'big_capacity_product' => (int)$iproduct['big_capacity'],
		// 						'product_status' => 'booking',
		// 						'qty_deliver' => 0,
		// 						'qty_receive' => 0,
		// 						'qty_customer' => 0,
		// 						'customer_status' => 'Barang Belum Diberikan'
		// 					);
							
		// 					$this->api_inventories_model->update_qty_booking_sale((int)$product['inventory_product_id'], (int)$quantity, 'store', (int)$this->config->item('store_id'), 'add');
		// 				}
		// 			}
		// 		}
		// 	}
			
		// 	if ( ! $order_no_tax) {
		// 		foreach ($order_tax['totals'] as $total) {
		// 			$sales_costs[] = array(
		// 				'cost_name' => $total['title'],
		// 				'cost_value' => $total['value'],
		// 				'include_invoice' => 1,
		// 				'cost_tax_invoice' => 'Non Pajak'
		// 			);
		// 		}
		// 	}
			
		// 	$sales_id = $this->add_sales($sales_data, $sales_details, $sales_inventories, $sales_costs);
			
		// 	foreach ($sales_inventories as $sales_inventory) {
		// 		$notification = array(
		// 			'notif_type' => 'INFO',
		// 			'notif_group' => 'Penjualan',
		// 			'refer_id' => $sales_id,
		// 			'notif_title' => 'Penjualan Baru '.$reference_no.' di DT ECommerce',
		// 			'description' => 'Ada Penjualan Baru '.$reference_no.' di DT ECommerce pada tanggal '.date('Y-m-d').', customer: '.$order_tax['user_name'].', tanggal pengiriman: '.date('Y-m-d').', sales: '.$this->admin->username(),
		// 			'show_date' => date('Y-m-d'),
		// 			'read_status' => 'Belum Dibaca',
		// 			'created_id' => $this->admin->admin_id(),
		// 			'created_by' => $this->admin->username(),
		// 			'created_date' => date('Y-m-d H:i:s'),
		// 			'place' => $sales_inventory['location_type'],
		// 			'place_id' => $sales_inventory['location_id']
		// 		);
				
		// 		$this->api_notifications_model->add_notification($notification);
				
		// 		$notification = array(
		// 			'notif_type' => 'INFO',
		// 			'notif_group' => 'Pengiriman Barang Penjualan',
		// 			'refer_id' => $sales_id,
		// 			'notif_title' => 'Permintaan Keluar Barang Untuk Penjualan '.$reference_no.' dari '.$this->api_places_model->get_place_name($sales_inventory['location_type'], $sales_inventory['location_id']).' ke '.$this->api_places_model->get_place_name('store', (int)$this->config->item('store_id')).' (DT ECommerce)',
		// 			'description' => 'Ada Permintaan Keluar Barang untuk Penjualan '.$reference_no.' dari '.$this->api_places_model->get_place_name($sales_inventory['location_type'], $sales_inventory['location_id']).' ke '.$this->api_places_model->get_place_name('store', (int)$this->config->item('store_id')).' (DT ECommerce) pada tanggal '.date('d-m-Y').', Detail barang: '.$this->api_products_model->get_iproduct_name($sales_inventory['product_id']).', jumlah: '.number_format($sales_inventory['qty']),
		// 			'show_date' => date('Y-m-d'),
		// 			'read_status' => 'Belum Dibaca',
		// 			'created_id' => $this->admin->admin_id(),
		// 			'created_by' => $this->admin->username(),
		// 			'created_date' => date('Y-m-d H:i:s'),
		// 			'place' => $sales_inventory['location_type'],
		// 			'place_id' => $sales_inventory['location_id']
		// 		);
				
		// 		$this->api_notifications_model->add_notification($notification);
		// 	}
		// }
		
		// if ($order_no_tax) {
		// 	$total_sales = 0;
		// 	$sales_details = array();
		// 	$sales_inventories = array();
		// 	$sales_costs = array();
			
		// 	if ($order_no_tax['comment']) {
		// 		$description = 'E-Commerce['.$order_no_tax['invoice_no'].'] '.$order_no_tax['comment'];
		// 	} else {
		// 		$description = 'E-Commerce['.$order_no_tax['invoice_no'].']';
		// 	}
			
		// 	$reference_no = $this->get_reference_no();
			
		// 	$sales_data = array(
		// 		'store_id' => (int)$this->config->item('store_id'),
		// 		'reference_no' => $reference_no,
		// 		'sale_date' => date('Y-m-d', strtotime($order_no_tax['date_added'])),
		// 		'customer_id' => 1,
		// 		'delivery_date' => date('Y-m-d'),
		// 		'sale_status' => 'Baru',
		// 		'created_id' => $this->admin->admin_id(),
		// 		'created_by' => $this->admin->username(),
		// 		'created_date' => date('Y-m-d H:i:s'),
		// 		'deliver_status' => 'Belum Dikirim',
		// 		'receive_status' => 'Belum Diterima',
		// 		'description' => $description,
		// 		'sales_tax_type' => 0,
		// 		'sales_tax_value' => 0,
		// 		'total_sales' => $order_no_tax['total_sales'],
		// 		'payment_status' => 'Belum Dibayar',
		// 		'customer_invname' => $order_no_tax['user_name'],
		// 		'customer_invaddress' => $order_no_tax['user_address'].', '.$order_no_tax['user_subdistrict'].', '.$order_no_tax['user_city'].' - '.$order_no_tax['user_province'],
		// 		'customer_invphone' => $order_no_tax['user_telephone'],
		// 		'discount_value' => 0,
		// 		'order_id' => $order_no_tax['order_id']
		// 	);
			
		// 	foreach ($order_no_tax['products'] as $product) {
		// 		$iproduct = $this->idb
		// 		->select('limit_price, cost, big_capacity')
		// 		->where('id', $product['inventory_product_id'])
		// 		->get('products')
		// 		->row_array();
				
		// 		if ($iproduct) {
		// 			$sales_details[] = array(
		// 				'product_id' => (int)$product['inventory_product_id'],
		// 				'qty' => (int)$product['quantity'],
		// 				'price' => (float)$product['price'],
		// 				'sell_price' => (float)$iproduct['limit_price'],
		// 				'tax_type' => $product['tax_type'],
		// 				'tax_value' => (float)$product['tax_value'],
		// 				'category_percent' => 0,
		// 				'store_percent'=> 0,
		// 				'qty_deliver' => 0,
		// 				'qty_retur' => 0,
		// 				'limit_price' => (float)$iproduct['limit_price'],
		// 				'cost' => (float)$iproduct['cost']
		// 			);
					
		// 			$inventory = $this->api_inventories_model->get_inventory_product((int)$product['inventory_product_id'], 'store', (int)$this->config->item('store_id'));
					
		// 			if ((int)$inventory['balance'] >= (int)$product['quantity']) {
		// 				// stock store mencukupi, semua ambil dari sini
		// 				$sales_inventories[] = array(
		// 					'product_id' => (int)$product['inventory_product_id'],
		// 					'qty' => (int)$product['quantity'],
		// 					'location_type' => 'store',
		// 					'location_id' => (int)$this->config->item('store_id'),
		// 					'is_store_requested' => 0,
		// 					'delivery_type' => 3,
		// 					'deliver_status' => 'Belum Dikirim',
		// 					'receive_status' => 'Belum Diterima',
		// 					'big_capacity_product' => (int)$iproduct['big_capacity'],
		// 					'product_status' => 'ready',
		// 					'qty_deliver' => 0,
		// 					'qty_receive' => 0,
		// 					'qty_customer' => 0,
		// 					'customer_status' => 'Barang Belum Diberikan'
		// 				);
						
		// 				$this->api_inventories_model->update_qty_booking_sale((int)$product['inventory_product_id'], (int)$product['quantity'], 'store', (int)$this->config->item('store_id'), 'add');
		// 			} else {
		// 				// jika stok tidak mencukupi, coba cari di tempat lain
		// 				$quantity = (int)$product['quantity'];
		// 				$inventories = $this->api_inventories_model->get_inventory_product((int)$product['inventory_product_id']);
						
		// 				foreach ($inventories as $inventory) {
		// 					if ($quantity > 0 && $inventory['balance'] <= $quantity) {
		// 						$sales_inventories[] = array(
		// 							'product_id' => (int)$product['inventory_product_id'],
		// 							'qty' => (int)$inventory['balance'],
		// 							'location_type' => $inventory['place_type'],
		// 							'location_id' => (int)$inventory['place_id'],
		// 							'is_store_requested' => 0,
		// 							'delivery_type' => 3,
		// 							'deliver_status' => 'Belum Dikirim',
		// 							'receive_status' => 'Belum Diterima',
		// 							'big_capacity_product' => (int)$iproduct['big_capacity'],
		// 							'product_status' => 'ready',
		// 							'qty_deliver' => 0,
		// 							'qty_receive' => 0,
		// 							'qty_customer' => 0,
		// 							'customer_status' => 'Barang Belum Diberikan'
		// 						);
								
		// 						$this->api_inventories_model->update_qty_booking_sale((int)$product['inventory_product_id'], (int)$inventory['balance'], $inventory['place_type'], (int)$inventory['place_id'], 'add');
								
		// 						$quantity -= $inventory['balance'];
		// 					}
							
		// 					if ($quantity > 0 && $inventory['balance'] > $quantity) {
		// 						$sales_inventories[] = array(
		// 							'product_id' => (int)$product['inventory_product_id'],
		// 							'qty' => (int)$quantity,
		// 							'location_type' => $inventory['place_type'],
		// 							'location_id' => (int)$inventory['place_id'],
		// 							'is_store_requested' => 0,
		// 							'delivery_type' => 3,
		// 							'deliver_status' => 'Belum Dikirim',
		// 							'receive_status' => 'Belum Diterima',
		// 							'big_capacity_product' => (int)$iproduct['big_capacity'],
		// 							'product_status' => 'ready',
		// 							'qty_deliver' => 0,
		// 							'qty_receive' => 0,
		// 							'qty_customer' => 0,
		// 							'customer_status' => 'Barang Belum Diberikan'
		// 						);
								
		// 						$this->api_inventories_model->update_qty_booking_sale((int)$product['inventory_product_id'], (int)$quantity, $inventory['place_type'], (int)$inventory['place_id'], 'add');
								
		// 						$quantity -= $quantity;
		// 					}
		// 				}
						
		// 				if ($quantity) {
		// 					// booking untuk gudang ini jika ada sisa yang belum terpenuhi
		// 					$sales_inventories[] = array(
		// 						'product_id' => (int)$product['inventory_product_id'],
		// 						'qty' => (int)$quantity,
		// 						'location_type' => 'store',
		// 						'location_id' => (int)$this->config->item('store_id'),
		// 						'is_store_requested' => 0,
		// 						'delivery_type' => 3,
		// 						'deliver_status' => 'Belum Dikirim',
		// 						'receive_status' => 'Belum Diterima',
		// 						'big_capacity_product' => (int)$iproduct['big_capacity'],
		// 						'product_status' => 'booking',
		// 						'qty_deliver' => 0,
		// 						'qty_receive' => 0,
		// 						'qty_customer' => 0,
		// 						'customer_status' => 'Barang Belum Diberikan'
		// 					);
							
		// 					$this->api_inventories_model->update_qty_booking_sale((int)$product['inventory_product_id'], (int)$quantity, 'store', (int)$this->config->item('store_id'), 'add');
		// 				}
		// 			}
		// 		}
		// 	}
			
		// 	foreach ($order_no_tax['totals'] as $total) {
		// 		$sales_costs[] = array(
		// 			'cost_name' => $total['title'],
		// 			'cost_value' => $total['value'],
		// 			'include_invoice' => 1,
		// 			'cost_tax_invoice' => 'Non Pajak'
		// 		);
		// 	}
			
		// 	$sales_id = $this->add_sales($sales_data, $sales_details, $sales_inventories, $sales_costs);
			
		// 	foreach ($sales_inventories as $sales_inventory) {
		// 		$notification = array(
		// 			'notif_type' => 'INFO',
		// 			'notif_group' => 'Penjualan',
		// 			'refer_id' => $sales_id,
		// 			'notif_title' => 'Penjualan Baru '.$reference_no.' di DT ECommerce',
		// 			'description' => 'Ada Penjualan Baru '.$reference_no.' di DT ECommerce pada tanggal '.date('Y-m-d').', customer: '.$order_no_tax['user_name'].', tanggal pengiriman: '.date('Y-m-d').', sales: '.$this->admin->username(),
		// 			'show_date' => date('Y-m-d'),
		// 			'read_status' => 'Belum Dibaca',
		// 			'created_id' => $this->admin->admin_id(),
		// 			'created_by' => $this->admin->username(),
		// 			'created_date' => date('Y-m-d H:i:s'),
		// 			'place' => $sales_inventory['location_type'],
		// 			'place_id' => $sales_inventory['location_id']
		// 		);
				
		// 		$this->api_notifications_model->add_notification($notification);
				
		// 		$notification = array(
		// 			'notif_type' => 'INFO',
		// 			'notif_group' => 'Pengiriman Barang Penjualan',
		// 			'refer_id' => $sales_id,
		// 			'notif_title' => 'Permintaan Keluar Barang Untuk Penjualan '.$reference_no.' dari '.$this->api_places_model->get_place_name($sales_inventory['location_type'], $sales_inventory['location_id']).' ke '.$this->api_places_model->get_place_name('store', (int)$this->config->item('store_id')).' (DT ECommerce)',
		// 			'description' => 'Ada Permintaan Keluar Barang untuk Penjualan '.$reference_no.' dari '.$this->api_places_model->get_place_name($sales_inventory['location_type'], $sales_inventory['location_id']).' ke '.$this->api_places_model->get_place_name('store', (int)$this->config->item('store_id')).' (DT ECommerce) pada tanggal '.date('d-m-Y').', Detail barang: '.$this->api_products_model->get_iproduct_name($sales_inventory['product_id']).', jumlah: '.number_format($sales_inventory['qty']),
		// 			'show_date' => date('Y-m-d'),
		// 			'read_status' => 'Belum Dibaca',
		// 			'created_id' => $this->admin->admin_id(),
		// 			'created_by' => $this->admin->username(),
		// 			'created_date' => date('Y-m-d H:i:s'),
		// 			'place' => $sales_inventory['location_type'],
		// 			'place_id' => $sales_inventory['location_id']
		// 		);
				
		// 		$this->api_notifications_model->add_notification($notification);
		// 	}
		// }
	}
	
	/**
	 * Get reference number
	 * 
	 * @access public
	 * @return string
	 */
	public function get_reference_no()
	{
		// $random = '';
		
		// for ($i = 0; $i < 4; $i++) {
		// 	$random .= substr("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789ABCDEF", mt_rand(0, 37), 1);
		// }
		
		// return 'SL'.date('Ymd').'-'.$random;
		return '';
	}
	
	/**
	 * Get order tax
	 * 
	 * @access public
	 * @param int $order_id
	 * @return mixed
	 */
	public function get_order_tax($order_id)
	{
		// if ($order = $this->order_model->get_order($order_id)) {
		// 	$order['products'] = $this->edb
		// 	->select('op.*, p.inventory_product_id, p.base_price, p.tax_type, p.tax_value')
		// 	->join('product p', 'p.product_id = op.product_id', 'left')
		// 	->where('op.order_id', $order_id)
		// 	->where('op.tax >', 0)
		// 	->where('p.inventory_product_id >', 0)
		// 	->get('order_product op')
		// 	->result_array();
			
		// 	if ( ! $order['products']) {
		// 		return array();
		// 	}
			
		// 	$subtotal = 0;
		// 	$tax = 0;
			
		// 	foreach ($order['products'] as $product) {
		// 		$subtotal += (float)$product['total_base'];
		// 		$tax += (float)$product['tax'];
		// 	}
			
		// 	$order['totals'] = $this->edb
		// 	->where('order_id', $order_id)
		// 	->where_in('code', array('tax', 'midtrans', 'shipping', 'unique'))
		// 	->order_by('sort_order', 'asc')
		// 	->get('order_total')
		// 	->result_array();
			
		// 	foreach ($order['totals'] as $order_total) {
		// 		$subtotal += (float)$order_total['value'];
		// 	}
			
		// 	$order['total_sales'] = $subtotal + $tax;
			
		// 	return $order;
		// }
		return 0;
	}
	
	/**
	 * Get order no tax
	 * 
	 * @access public
	 * @param int $order_id
	 * @return mixed
	 */
	public function get_order_no_tax($order_id)
	{
		// if ($order = $this->order_model->get_order($order_id)) {
		// 	$order['products'] = $this->edb
		// 	->select('op.*, p.inventory_product_id, p.base_price, p.tax_type, p.tax_value')
		// 	->join('product p', 'p.product_id = op.product_id', 'left')
		// 	->where('op.order_id', $order_id)
		// 	->where('op.tax', 0)
		// 	->where('p.inventory_product_id >', 0)
		// 	->get('order_product op')
		// 	->result_array();
			
		// 	if ( ! $order['products']) {
		// 		return array();
		// 	}
			
		// 	$order['totals'] = $this->edb
		// 	->where('order_id', $order_id)
		// 	->where_in('code', array('tax', 'midtrans', 'shipping', 'unique'))
		// 	->order_by('sort_order', 'asc')
		// 	->get('order_total')
		// 	->result_array();
			
		// 	$total = 0;
			
		// 	foreach ($order['products'] as $product) {
		// 		$total += (float)$product['total'];
		// 	}
			
		// 	foreach ($order['totals'] as $order_total) {
		// 		$total += (float)$order_total['value'];
		// 	}
			
		// 	$order['total_sales'] = $total;
			
		// 	return $order;
		// }
		return 0;
	}
	
	/**
	 * Add sales
	 * 
	 * @access public
	 * @param array $data
	 * @param array $products
	 * @param array $inventories
	 * @param array $costs
	 * @return int | bool
	 */
	public function add_sales($data, $products, $inventories, $costs)
	{
		// if ($this->idb->insert('sales', $data)) {
		// 	$sale_id = $this->idb->insert_id();
			
		// 	foreach ($products as $product) {
		// 		$product['sale_id'] = $sale_id;
		// 		$this->idb->insert('sales_detail', $product);
		// 	}
			
		// 	foreach ($inventories as $inventory) {
		// 		$inventory['sale_id'] = $sale_id;
		// 		$this->idb->insert('sales_inventory', $inventory);
		// 	}
			
		// 	foreach ($costs as $cost) {
		// 		$cost['sale_id'] = $sale_id;
		// 		$this->idb->insert('sales_cost', $cost);
		// 	}
			
		// 	$this->add_payment($sale_id);
			
		// 	return $sale_id;
		// }
		
		// return false;
	}
	
	/**
	 * Add payment
	 * 
	 * @access public
	 * @param int $sales_id
	 * @return void
	 */
	public function add_payment($sales_id)
	{
		// $sales = $this->idb
		// ->where('id', (int)$sales_id)
		// ->get('sales')
		// ->row_array();
		
		// if ($sales) {
		// 	$order = $this->edb
		// 	->where('order_id', (int)$sales['order_id'])
		// 	->get('order')
		// 	->row_array();
			
		// 	if ($order) {
		// 		if ($this->get_paid_date($order['order_id'])) {
		// 			$admin = $this->admin->username();
		// 			$datetime = date('Y-m-d H:i:s');
		// 			$date = date('Y-m-d');
		// 			$data = array(
		// 				'invoice_no' => $this->create_invoice_no(date('y'), (int)$sales['sales_tax_type']),
		// 				'sale_status' => 'Pembayaran Sudah Diproses',
		// 				'invoice_date' => date('Y-m-d H:i:s'),
		// 				'payment_status' => 'Lunas',
		// 				'cashier_by' => $admin,
		// 				'cashier_date' => $datetime,
		// 				'updated_by' => $admin,
		// 				'updated_date' => $datetime,
		// 				'lunas_date' => $date,
		// 				'lunas_by' => $admin
		// 			);
					
		// 			$this->idb
		// 			->where('id', $sales_id)
		// 			->set($data)
		// 			->update('sales');
					
		// 			$payment = array(
		// 				'sale_id' => $sales_id,
		// 				'payment_date' => $date,
		// 				'payment_bank' => 'Cash',
		// 				'payment_detail' => 'Midtrans ('.$order['payment_method'].')',
		// 				'payment_value' => (float)$order['total'],
		// 				'payment_status' => 'Lunas',
		// 				'payment_type' => 'Cash',
		// 				'payment_by' => $admin,
		// 				'payment_time' => $datetime
		// 			);
					
		// 			$this->idb->insert('sales_payment', $payment);
		// 		}
		// 	}
		// }
	}
	
	/**
	 * Get paid date
	 * 
	 * @access private
	 * @param int $order_id
	 * @return mixed
	 */
	private function get_paid_date($order_id)
	{	
		// $query = $this->edb
		// ->select('date_added')
		// ->where('order_id', (int)$order_id)
		// ->where('order_status_id', (int)$this->config->item('order_paid_status_id'))
		// ->order_by('order_status_id', 'desc')
		// ->get('order_history')
		// ->row_array();
		
		// return $query ? $query['date_added'] : false;
		return '';
	}
	
	/**
	 * Create invoice no
	 * 
	 * @access private
	 * @param mixed $year
	 * @param mixed $type
	 * @return void
	 */
	private function create_invoice_no($year, $type)
	{
		// $cashieruser = strtoupper($this->admin->username());
		// $salesuser = strtoupper($this->admin->username());
		// $query = $this->idb->select('invoice_no');
		
		// if ($type == 2) {
		// 	$query = $this->idb->where("invoice_no REGEXP '^[0-9]{8}\/DUTA\-[A-Za-z]{3}\/".$year."'", null, false);
		// 	$invoice_type = 'duta';
		// } else {
		// 	$query = $this->idb->where("invoice_no REGEXP '^[0-9]{8}\/DAYA\-[A-Za-z]{3}\/".$year."'", null, false);
		// 	$invoice_type = 'daya';
		// }
		
		// $query = $this->idb
		// ->order_by('invoice_no', 'desc')
		// ->limit(1)
		// ->get('sales')
		// ->row_array();
		
		// if ($query) {
		// 	$number = round(substr($query['invoice_no'], 0, 8)) + 1;
			
		// 	if (strlen($number) == 1) {
		// 		$new_no = '0000000'.$number;
		// 	} elseif (strlen($number) == 2) {
		// 		$new_no = '000000'.$number;
		// 	} elseif (strlen($number) == 3) {
		// 		$new_no = '00000'.$number;
		// 	} elseif (strlen($number) == 4) {
		// 		$new_no = '0000'.$number;
		// 	} elseif (strlen($number) == 5) {
		// 		$new_no = '000'.$number;
		// 	} elseif (strlen($number) == 6) {
		// 		$new_no = '00'.$number;
		// 	} elseif (strlen($number) == 7) {
		// 		$new_no = '0'.$number;
		// 	} else {
		// 		$new_no = $number;
		// 	}
			
		// 	$invoice_no = $new_no.'/'.strtoupper($invoice_type).'-'.strtoupper(date('M')).'/'.date('y').'/'.$cashieruser.'/'.$salesuser;
		// } else {
		// 	$invoice_no = '00000001/'.strtoupper($invoice_type).'-'.strtoupper(date('M')).'/'.date('y').'/'.$cashieruser.'/'.$salesuser;
		// }
		
		// return $invoice_no;
		return '';
	}
	
	public function finish($order_id = null)
	{
		// if ((int)$order_id > 0) {
		// 	$sales = $this->idb
		// 	->select('id, reference_no')
		// 	->where('order_id', $order_id)
		// 	->get('sales')
		// 	->result_array();
			
		// 	if ($sales) {
		// 		$this->load->model('api/action_logs_model', 'api_action_logs_model');
				
		// 		foreach ($sales as $sale) {
		// 			$this->idb
		// 			->where('id', $sale['id'])
		// 			->update('sales', array(
		// 				'sale_status' => 'Selesai',
		// 				'updated_by' => $this->admin->username(),
		// 				'updated_date' => date('Y-m-d H:i:s')
		// 			));
					
		// 			$this->api_action_logs_model->insert_log('PENJUALAN', 'UBAH', 'Ubah Status Customer di Penjualan, nomor referensi jual = '.$sale['reference_no'].', Barang Sudah Diterima Customer, id = '.$sale['id'], $this->admin->username());	
		// 		}	
		// 	}
		// }
		return '';
	}
}