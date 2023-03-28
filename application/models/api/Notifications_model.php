<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'models/api/Base_model.php';

class Notifications_model extends Base_model
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
	
	/**
	 * Insert
	 * 
	 * @access public
	 * @param array $data
	 * @return void
	 */
	public function add_notification($data = array())
	{
		// if (isset($data['place']) && isset($data['place_id'])) {
		// 	$place = $data['place'];
		// 	$place_id = $data['place_id'];
		// 	unset($data['place']);
		// 	unset($data['place_id']);
		// } else {
		// 	$place = null;
		// 	$place_id = null;
		// }
		
		// $notifications = array();
		
		// $this->load->model('api/users_model', 'api_users_model');
		
		// // -------------------------------------------------------------------------------
		// // semua notif hanya user tertentu yang dapat
		// $users = $this->api_users_model->get_users(array(1,2,6));
		
		// foreach ($users as $user) {
		// 	$data['user_id'] = $user['id'];
		// 	$notifications[] = $data;
		// }
		
		// // -------------------------------------------------------------------------------
		
		// // pengiriman barang penjualan
		// if ($data['notif_group'] == 'Pengiriman Barang Penjualan') {
		// 	if ($place == 'store') {
		// 		$users = $this->api_users_model->get_users(array(7,9), $place, (int)$place_id);	
		// 	} elseif ($place == 'warehouse') {
		// 		$users = $this->api_users_model->get_users(array(8,9), $place, (int)$place_id);	
		// 	}
			
		// 	foreach ($users as $user) {
		// 		$data['user_id'] = $user['id'];
		// 		$notifications[] = $data;
		// 	}
		// }
		
		// // -------------------------------------------------------------------------------
		
		// // penjualan
		// if ($data['notif_group'] == 'Penjualan') {
		// 	if ($place && $place_id) {
		// 		$users = $this->api_users_model->get_users(array(3,5), $place, (int)$place_id);	
		// 	} else {
		// 		$users = $this->api_users_model->get_users(array(3,5), 'store', (int)$this->config->item('store_id'));
		// 	}
			
		// 	foreach ($users as $user) {
		// 		$data['user_id'] = $user['id'];
		// 		$notifications[] = $data;
		// 	}
		// }
		
		// $this->idb->insert_batch('notifications', $notifications);
	}
	
	public function get_notifications()
	{
		// return $this->idb->limit(10)->order_by('id', 'desc')->get('notifications')->result_array();
		return array();
	}
}