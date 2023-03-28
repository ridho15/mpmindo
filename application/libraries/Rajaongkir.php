<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * RajaOngkir API PHP client
 *
 * @author		Adi Setiawan
 * @email		mas.adisetiawan@gmail.com
 * @copyright	Copyright (c)2016
 * @filesource
 */
 
class Rajaongkir
{
	/**
	 * CI instance
	 * 
	 * @var string
	 * @access private
	 */
	private $ci;
	
	/**
	 * API Key
	 *
	 * Set $api_key property here with your RajaOngkir API Key
	 * 
	 * @var string
	 * @access private
	 */
	private $api_key = '';
	
	/**
	 * Account type
	 *
	 * Set $account_type property here with your RajaOngkir service plan
	 * starter/basic/pro
	 * 
	 * @var string
	 * @access private
	 */
	private $account_type = 'pro';
	
	/**
	 * API URL
	 * 
	 * @var string
	 * @access private
	 */
	private $api_url = "http://rajaongkir.com/api/";
	
	/**
	 * Cache folder
	 * 
	 * @var string
	 * @access private
	 */
	private $cache_folder = APPPATH.'cache/rajaongkir';
	
	/**
	 * Constructor
	 * 
	 * @access public
	 * @param string $api_key
	 * @param string $account_type
	 * @return void
	 */
	public function __construct($config = array())
	{	
		if (!function_exists('curl_init')) {
			log_message('error', 'cURL Class - PHP was not built with cURL enabled. Rebuild PHP with --with-curl to use cURL.');
		}
		
		foreach ($config as $key => $value) {
			$this->{$key} = $value;
		}
		
		if ($this->account_type == 'pro') {
			$this->api_url = 'http://pro.rajaongkir.com/api/';
		} else {
			$this->api_url = 'http://api.rajaongkir.com/'.$this->account_type.'/';
		}
		
		if ( ! file_exists($this->cache_folder)) {
			@mkdir($this->cache_folder, 0777);
		}
		
		$this->ci =& get_instance();
		$this->ci->load->helper('file');
	}
	
	/**
	 * Get quote
	 * 
	 * @access public
	 * @param string $address
	 * @return array
	 */
	public function get_quote($address)
	{
		$status = true;

		$quote_data = array();
		$method_data = array();
	
		if ($status) {
			$city_id = false;
			
			if (!empty($address['city_id'])) {
				$city_id = $address['city_id'];
			}
			
			if (!empty($address['subdistrict_id'])) {
				$destination_type = 'subdistrict';
				$destination = $address['subdistrict_id'];
			} else {
				$destination_type = 'city';
				$destination = $city_id;
			}
			
			if ($this->account_type == 'pro') {
				$origin_type = 'subdistrict';
			} else {
				$origin_type = 'city';
			}
			
			$couriers = $this->ci->config->item('rajaongkir_courier') ? (array)$this->ci->config->item('rajaongkir_courier') : array();
			
			if ($origin_type == 'city') {
				$origin = (int)$this->ci->config->item('rajaongkir_city_id');
			} else {
				$origin = (int)$this->ci->config->item('rajaongkir_subdistrict_id');
			}
			
			$this->ci->load->library('weight');
			$this->ci->load->library('currency');
			
			$weight = 0;
			
			foreach ($this->ci->shopping_cart->get_products() as $product) {
				$weight += $this->ci->weight->convert($product['weight'], $product['weight_class_id'], $this->ci->config->item('rajaongkir_weight_class_id'));
			}
			
			$this->ci->load->model('shipping_cost_model');
			
			if ($shipping_cost = $this->ci->shipping_cost_model->get_cost($destination_type, $destination)) {
				$quote_data['cod'] = array(
					'code' => 'rajaongkir.cod',
					'title' => 'COD (Cash On Delivery)',
					'cost' => (float)$shipping_cost['cost'],
					'text' => $this->ci->currency->format((float)$shipping_cost['cost'])
				);
			}
			
			foreach ($couriers as $courier) {
				$query = $this->cost($origin, (int)$destination, $weight, $courier, $origin_type, $destination_type);
								
				foreach ($query as $result) {
					foreach ($result['costs'] as $cost) {
						$passed = true;
						$excludes = array('PELIKAN', 'SPS', 'POPBOX', 'JTR');
						
						foreach ($excludes as $exclude) {
							if (strpos($cost['service'], $exclude) !== false) {
								$passed = false;
								break;
							}
						}
						
						if ($passed) {
							$code = $result['code'].'_'.strtolower(str_replace(' ', '_', $cost['service']));
								
							$costs = 0;
									
							foreach ($cost['cost'] as $cost_val) {
								$costs = $cost_val['value'];
							}
							
							$quote_data[$code] = array(
								'code' => 'rajaongkir.'.$code,
								'title' => $result['name'].' - '.$cost['service'],
								'cost' => $costs,
								'text' => $this->ci->currency->format($costs)
							);
						}
					}
				}
			}

			$method_data = array(
				'code' => 'rajaongkir',
				'title' => 'Metode Pengiriman',
				'quote' => $quote_data,
				'error' => $quote_data ? false : 'Gagal saat mengambil informasi metode pengiriman.'
			);
		}
		
		return $method_data;
	}
	
	/**
	 * cURL post
	 * 
	 * @access private
	 * @param array $params
	 * @param string $endpoint
	 * @return array
	 */
	private function post($params, $endpoint = '')
	{
		$curl = curl_init();
		$header[] = "Content-Type: application/x-www-form-urlencoded";
		$header[] = "key: $this->api_key";
		$query = http_build_query($params);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_URL, $this->api_url.$endpoint);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$request = curl_exec($curl);
		$return = ($request === false) ? curl_error($curl) : $request;
		curl_close($curl);
		
		return json_decode($return, true);
	}
	
	/**
	 * cURL get
	 * 
	 * @access private
	 * @param array $params
	 * @param string $endpoint
	 * @return array
	 */
	private function get($params, $endpoint = '')
	{
		$curl = curl_init();
		$header[] = "key: $this->api_key";
		$query = http_build_query($params);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_URL, $this->api_url.$endpoint."?".$query);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$request = curl_exec($curl);
		$return = ($request === false) ? curl_error($curl) : $request;
		curl_close($curl);
		
		return json_decode($return, true);
	}
	
	/**
	 * Get province(s)
	 * 
	 * @access public
	 * @param int $province_id
	 * @return array
	 */
	public function province($province_id = null)
	{
		$params = (is_null($province_id)) ? array() : array('id' => $province_id);
		
		$result = $this->get($params, 'province');
		
		$provinces = array();
		
		if (isset($result['rajaongkir']['results'])) {
			foreach ($result['rajaongkir']['results'] as $province) {
				$provinces[] = $province;
			}
		}
		
		return $provinces;
	}
	
	/**
	 * Get city
	 * 
	 * @access public
	 * @param int $province_id
	 * @param int $city_id
	 * @return array
	 */
	public function city($province_id = null, $city_id = null)
	{
		$params = (is_null($province_id)) ? array() : array('province' => $province_id);
		
		if (!is_null($city_id)) {
			$params['id'] = $city_id;
		}
		
		$result = $this->get($params, 'city');
		
		$cities = array();
		
		if (isset($result['rajaongkir']['results'])) {
			foreach ($result['rajaongkir']['results'] as $city) {
				$cities[] = $city;
			}
		}
		
		return $cities;
	}
	
	/**
	 * Get subdstrict(s)
	 * 
	 * @access public
	 * @param int $city_id
	 * @param int $subdistrict_id
	 * @return mixed
	 */
	public function subdistrict($city_id = null, $subdistrict_id = null)
	{
		$params = (is_null($city_id)) ? array() : array('city' => $city_id);
		
		if (!is_null($subdistrict_id)) {
			$params['id'] = $subdistrict_id;
		}
		
		$result = $this->get($params, 'subdistrict');
		
		$subdistricts = array();
		
		if (isset($result['rajaongkir']['results'])) {
			foreach ($result['rajaongkir']['results'] as $subdistrict) {
				$subdistricts[] = $subdistrict;
			}
		}
		
		return $subdistricts;
	}
	
	/**
	 * Get cost
	 * 
	 * @access public
	 * @param int $origin
	 * @param int $destination
	 * @param float $weight
	 * @param string $courier
	 * @param string $originType
	 * @param string $destinationType
	 * @return array
	 */
	public function cost($origin, $destination, $weight, $courier = null, $originType = 'city', $destinationType = 'city')
	{
		$file = $origin.'_'.$destination.'_'.$weight.'_'.$courier.'_'.$originType.'_'.$destinationType.'.json';
		
		if (file_exists($this->cache_folder.'/'.$file)) {
			$file = file_get_contents($this->cache_folder.'/'.$file);
			return json_decode($file, 'true');
		} else {
			$params = array(
				'origin' => $origin,
				'originType' => $originType,
				'destination' => $destination,
				'destinationType' => $destinationType,
				'weight' => $weight,
				'courier' => $courier
			);
			
			$result = $this->post($params, 'cost');
			
			$couriers = array();
			
			if (isset($result['rajaongkir']['results'])) {
				foreach ($result['rajaongkir']['results'] as $courier) {
					$couriers[] = $courier;
				}
			}
			
			if ( ! write_file($this->cache_folder.'/'.$file, json_encode($couriers))) {
				log_message('error', 'Unable to write the file');
			}
			
			return $couriers;	
		}
	}
	
	/**
	 * Get currency
	 * 
	 * @access public
	 * @return array
	 */
	public function currency()
	{
		return $this->get(array(), 'currency');
	}
	
	/**
	 * Get tracking status
	 * 
	 * @access public
	 * @param string $waybill_number
	 * @param string $courier
	 * @return array
	 */
	public function waybill($waybill_number, $courier = 'jne')
	{
		$params = array(
			'waybill' => $waybill_number,
			'courier' => $courier
		);
		
		$result = $this->post($params, 'waybill');
		
		if (isset($result['rajaongkir']['status']['code']) && $result['rajaongkir']['status']['code'] == 200) {
			return $result['rajaongkir']['result'];
		}
		
		return false;
	}
	
	/**
	 * Get couriers
	 * 
	 * @access public
	 * @return array
	 */
	public function get_couriers($type = 'domestic')
	{
		if ($type == 'domestic') {
			return array(
				'jne' => 'Jalur Nugraha Ekakurir (JNE)', 
				'pos' => 'POS Indonesia (POS)', 
				'tiki' => 'Citra Van Titipan Kilat (TIKI)', 
				'rpx' => 'RPX Holding (RPX)', 
				'esl' => 'Eka Sari Lorena (ESL)', 
				'pcp' => 'Priority Cargo and Package (PCP)', 
				'pandu' => 'Pandu Logistics (PANDU)', 
				'wahana' => 'Wahana Prestasi Logistik (WAHANA)', 
				'sicepat' => 'SiCepat Express (SICEPAT)', 
				'jnt' => 'J&T Express (J&T)', 
				'pahala' => 'Pahala Kencana Express (PAHALA)', 
				'cahaya' => 'Cahaya Logistik (CAHAYA)', 
				'sap' => 'SAP Express (SAP)', 
				'jet' => 'JET Express (JET)', 
				'indah' => 'Indah Logistic (INDAH)', 
				'dse' => '21 Express (DSE)'
			);
		} else {
			return array(
				'jne' => 'Jalur Nugraha Ekakurir (JNE)', 
				'pos' => 'POS Indonesia (POS)', 
				'tiki' => 'Citra Van Titipan Kilat (TIKI)', 
				'slis' => 'Solusi Ekspres (SLIS)', 
				'expedito' => 'Expedito'
			);
		}
	}
}