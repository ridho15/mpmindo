<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Xendit\Xendit;

class Checkout extends MY_Controller
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
		
		$this->load->library('shopping_cart');
		$this->load->library('form_validation');
		$this->load->library('currency');
		
		$this->load->helper('form');
		$this->load->helper('language');
		$this->load->helper('format');
		
		$this->load->model('address_model');
		$this->load->model('location_model');
		
		$this->lang->load('checkout');
	}
	
	/**
	 * Index
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		if ( ! $this->user->is_logged() &&  ! $this->session->userdata('guest')) {
			$this->session->set_userdata('redirect', 'checkout');
			redirect('checkout/option');
		}
		
		if ( ! $this->shopping_cart->has_products() || ! $this->shopping_cart->has_stock()) {
			$this->session->set_flashdata('error', 'Maaf, produk yang ingin Anda beli saat ini sedang kosong!');
			redirect('cart');
		}
		
		$products = $this->shopping_cart->get_products();

		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$this->session->set_flashdata('error', 'Jumlah pembelian item harus minimum '.$product['minimum'].'!');
				redirect('cart');
			}
		}
		
		if ($this->user->is_logged()) {
			$this->session->unset_userdata('guest');
		}
		
		$data['heading_title'] = lang('heading_title');
		$data['success'] = $this->session->flashdata('success');
		$data['error'] = $this->session->flashdata('error');
		
		$this->load
		->breadcrumb('Checkout', site_url('checkout'))
		->view('checkout', $data);
	}
	
	/**
	 * Option
	 * 
	 * @access public
	 * @return void
	 */
	public function option()
	{
		if ($this->input->post('type') == 'guest') {
			$post['name'] = '';
			$post['address'] = '';
			$post['postcode'] = '';
			$post['province_id'] = (int)$this->config->item('rajaongkir_province_id');
			$post['city_id'] = 0;
			$post['subdistrict_id']	= 0;		
			$post['email'] = '';
			$post['telephone'] = '';
			$post['postcode'] = '';
			$post['province'] = '';
			$post['city'] = '';
			$post['subdistrict'] = '';
							
			$this->session->set_userdata('guest', $post);
			
			redirect('checkout');
		}
		
		$data = array();
		
		$data['text_forgotten'] = sprintf(lang('text_forgotten'), user_url('reset'));
			
		$this->load
		->breadcrumb('Checkout', site_url('checkout'))
		->breadcrumb('Option', site_url('option'))
		->view('checkout/option', $data);
	}
	
	/**
	 * Overview
	 * 
	 * @access public
	 * @return void
	 */
	public function overview()
	{
		check_ajax();
		
		$this->load->library('image');
		$this->load->library('currency');
		$this->load->library('total');
		$this->load->helper('array');
		$this->load->model('category_model');
				
		list($total_data, $total) = $this->total->get_totals();
				
		$total_data = reorder($total_data, 'sort_order', 'asc');

		$data['products'] = array();

		foreach ($this->shopping_cart->get_products() as $product) {
			if ($product['image']) {
				$image = $this->image->resize($product['image'], 80, 80);
			} else {
				$image = '';
			}
			
			$path = '';
			
			if ($category = $this->category_model->get_category($product['category_id'])) {
				if ($category['path_id']) {
					$category_ids = explode('-', $category['path_id']);
					foreach ($category_ids as $category_id) {
						if ($category_path = $this->category_model->get_category($category_id)) {
							$path .= $category_path['slug'].'/';
						}
					}
				}
				
				$path .= $category['slug'].'/';
			}
			
			$data['products'][] = array(
				'key' => $product['key'],
				'thumb' => $image,
				'name' => $product['name'],
				'quantity'=> $product['quantity'],
				'price' => $this->currency->format($product['price']),
				'total' => $this->currency->format($product['total']),
				'ori_total' => $product['total'],
				'href'=> site_url('product/'.$path.$product['slug'])
			);
		}
		
		$data['totals'] = array();

		foreach ($total_data as $result) {
			$data['totals'][] = array(
				'title' => $result['title'],
				'text'=> $this->currency->format($result['value']),
			);
		}
		

		$this->session->set_userdata('totals', $data['totals']);
		$this->session->set_userdata('product', $data['products']);
		$this->load->model('address_model');

		if ($this->user->is_logged() && $this->session->userdata('shipping_address_id')) {			
			$shipping_address = $this->address_model->get_address($this->session->userdata('shipping_address_id'));		
		} elseif ($this->session->userdata('guest')) {
			$shipping_address = $this->session->userdata('guest');
		}
		
		if (!empty($shipping_address['address']) && !empty($shipping_address['subdistrict']) && !empty($shipping_address['city']) && !empty($shipping_address['province']) && !empty($shipping_address['postcode'])) {	
			$data['shipping_address'] = $shipping_address['address'].', '.$shipping_address['subdistrict'].', '.$shipping_address['city'].' - '.$shipping_address['province'].' '. $shipping_address['postcode'];
		} else {
			$data['shipping_address'] = '';
		}
		
		echo $this->load->layout(false)->view('checkout/overview', $data);
	}
	
	/**
	 * Shipping address
	 * 
	 * @access public
	 * @return void
	 */
	public function shipping_address()
	{
		check_ajax();
		
		if ($this->input->post()) {
			$json = array();

			if ( ! $this->shopping_cart->has_products() || ! $this->shopping_cart->has_stock()) {
				$json['redirect'] = site_url('cart');
			}
			
			$products = $this->shopping_cart->get_products();

			foreach ($products as $product) {
				$product_total = 0;

				foreach ($products as $product_2) {
					if ($product_2['product_id'] == $product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}

				if ($product['minimum'] > $product_total) {
					$this->session->set_flashdata('error', 'Jumlah pembelian item harus minimum '.$product['minimum'].'!');
					$json['redirect'] = site_url('cart');
					break;
				}
			}
			
			if ( ! $json) {
				if ($this->input->post('shipping_address') && $this->input->post('shipping_address') == 'existing') {
					if ( ! $this->input->post('address_id')) {
						$json['error']['warning'] = lang('error_address');
					} elseif ( ! $this->address_model->check_address($this->input->post('address_id'), $this->user->user_id())) {
						$json['error']['warning'] = lang('error_address');
					} else {
						$address_info = $this->address_model->get_address($this->input->post('address_id'));					
					}

					if ( ! $json) {
						$this->session->set_userdata('shipping_address_id', $this->input->post('address_id'));

						if ($address_info) {
							$this->session->set_userdata('shipping_province_id', $address_info['province_id']);
							$this->session->set_userdata('shipping_city_id', $address_info['city_id']);
							$this->session->set_userdata('shipping_subdistrict_id', $address_info['subdistrict_id']);
						} else {
							$this->session->unset_userdata('shipping_province_id');
							$this->session->unset_userdata('shipping_city_id');
							$this->session->unset_userdata('shipping_subdistrict_id');
						}
						
						$this->session->set_userdata('tax_id', $this->input->post('tax_id'));
						$this->session->set_userdata('tax_name', $this->input->post('tax_name'));
						$this->session->set_userdata('tax_address', $this->input->post('tax_address'));
						$this->session->set_userdata('no_tax_id', $this->input->post('no_tax_id'));
						
						$this->session->unset_userdata('shipping_method');
						$this->session->unset_userdata('shipping_methods');
					}
				} else {
					$this->form_validation
					->set_rules('name', 'lang:entry_name', 'trim|required|min_length[3]')
					->set_rules('address', 'lang:entry_address', 'trim|required|min_length[3]');
					
					if ( ! $this->user->is_logged()) {
						$this->form_validation
						->set_rules('email', 'lang:entry_email', 'trim|required|valid_email')
						->set_rules('telephone', 'lang:entry_telephone', 'trim|required|numeric');
					}
					
					if ($this->shopping_cart->has_tax() && $this->input->post('no_tax_id') == null) {
						$this->form_validation->set_rules('tax_id', 'NPWP', 'trim|required|min_length[15]');
						$this->form_validation->set_rules('tax_name', 'Nama WP', 'trim|required');
						$this->form_validation->set_rules('tax_address', 'Alamat WP', 'trim|required');
					}
					
					$this->form_validation->set_error_delimiters('', '');
					
					if ($this->form_validation->run() == false) {
						$errors = validation_errors();
					
						foreach (array('name', 'address') as $field) {
							foreach ($this->form_validation->get_errors() as $field => $error) {
								$json['error'][$field] = form_error($field);
							}
						}
					} else {
						$post['name'] = $this->input->post('name');
						$post['address'] = $this->input->post('address');
						$post['postcode'] = $this->input->post('postcode');
						$post['province_id'] = (int)$this->input->post('province_id');
						$post['city_id'] = (int)$this->input->post('city_id');
						$post['subdistrict_id']	= (int)$this->input->post('subdistrict_id');
						
						if ($this->user->is_logged()) {
							$post['user_id'] = (int)$this->user->user_id();
							
							$this->session->set_userdata('shipping_address_id', $this->address_model->insert($post));
							$this->session->set_userdata('shipping_province_id', $this->input->post('province_id'));
							$this->session->set_userdata('shipping_city_id', $this->input->post('city_id'));
							$this->session->set_userdata('shipping_subdistrict_id', $this->input->post('subdistrict_id'));
							$this->session->set_userdata('tax_id', $this->input->post('tax_id'));
							$this->session->set_userdata('tax_name', $this->input->post('tax_name'));
							$this->session->set_userdata('tax_address', $this->input->post('tax_address'));
							$this->session->set_userdata('no_tax_id', $this->input->post('no_tax_id'));
						} else {
							$post['email'] = $this->input->post('email');
							$post['telephone'] = $this->input->post('telephone');
							$post['postcode'] = $this->input->post('postcode');
							
							$this->load->model('location_model');
							
							$province = $this->location_model->get_province($post['province_id']);
							$city = $this->location_model->get_city($post['city_id']);
							$subdistrict = $this->location_model->get_subdistrict($post['subdistrict_id']);
							
							$post['province'] = $province ? $province['name'] : '';
							$post['city'] = $city ? $city['name'] : '';
							$post['subdistrict'] = $subdistrict ? $subdistrict['name'] : '';
							
							$this->session->set_userdata('guest', $post);
							$this->session->set_userdata('tax_id', $this->input->post('tax_id'));
							$this->session->set_userdata('tax_name', $this->input->post('tax_name'));
							$this->session->set_userdata('tax_address', $this->input->post('tax_address'));
							$this->session->set_userdata('no_tax_id', $this->input->post('no_tax_id'));
						}

						$this->session->unset_userdata('shipping_method');
						$this->session->unset_userdata('shipping_methods');
					}
				}
			}

			$this->output->set_output(json_encode($json));
		} else {
			$data['action']	= site_url('checkout/shipping_address');
			$data['provinces'] = $this->location_model->get_provinces();
			
			if ($this->user->is_logged()) {
				$data['address_id']	= $this->session->userdata('shipping_address_id') ? $this->session->userdata('shipping_address_id') : 0;
				$data['addresses'] = $this->address_model->get_addresses($this->user->user_id());
				$data['province_id'] = $this->session->userdata('shipping_province_id') ? $this->session->userdata('shipping_province_id') : (int)$this->config->item('rajaongkir_province_id');
				$data['city_id'] = $this->session->userdata('shipping_city_id') ? $this->session->userdata('shipping_city_id') : '';
				$data['subdistrict_id']	= $this->session->userdata('shipping_subdistrict_id') ? $this->session->userdata('shipping_subdistrict_id') : '';
				
				if ($this->session->userdata('tax_id')) {
					$data['tax_id'] = $this->session->userdata('tax_id');
					$data['tax_name'] = $this->session->userdata('tax_name');
					$data['tax_address'] = $this->session->userdata('tax_address');
					$data['no_tax_id'] = $this->session->userdata('no_tax_id');
				} elseif ($user = $this->user_model->get($this->user->user_id()) && $this->session->userdata('no_tax_id') == null) {
					$data['tax_id'] = isset($user['tax_id']) ? $user['tax_id'] : '' ;
					$data['tax_name'] = '';
					$data['tax_address'] = '';
					$data['no_tax_id'] = '';
				} else {
					$data['tax_id'] = '';
					$data['tax_name'] = '';
					$data['tax_address'] = '';
					$data['no_tax_id'] = '';
				}
			} elseif ($this->session->userdata('guest')) {
				$guest = $this->session->userdata('guest');
				
				foreach ($guest as $key => $value) {
					$data[$key] = $value;
				}
				
				$data['tax_id'] = $this->session->userdata('tax_id');
				$data['tax_name'] = $this->session->userdata('tax_name');
				$data['tax_address'] = $this->session->userdata('tax_address');
				$data['no_tax_id'] = $this->session->userdata('no_tax_id');
			} else {
				$data['name'] = '';
				$data['telephone'] = '';
				$data['email'] = '';
				$data['address'] = '';
				$data['postcode'] = '';
				$data['province_id'] = (int)$this->config->item('rajaongkir_province_id');
				$data['city_id'] = '';
				$data['subdistrict_id'] = '';
				$data['tax_id'] = '';
				$data['tax_name'] = '';
				$data['tax_address'] = '';
				$data['no_tax_id'] = '';
			}
			
			$data['has_tax'] = $this->shopping_cart->has_tax();
			
			if ($this->user->is_logged()) {
				return $this->load->layout(false)->view('checkout/shipping_address', $data);
			} else {
				return $this->load->layout(false)->view('checkout/shipping_address_guest', $data);	
			}
		}
	}
	
	/**
	 * Shipping method
	 * 
	 * @access public
	 * @return void
	 */
	public function shipping_method()
	{
		check_ajax();
		
		$this->load->model('address_model');
		$this->load->model('user_model');


		$user = $this->user_model->get($this->user->user_id());
		$address = $this->address_model->get_address($this->session->userdata('shipping_address_id'));

		if( ! $this->session->userdata('user_id_xendit') ){

			$url = "https://api.xendit.co/customers";

			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_USERPWD, "xnd_production_3zWAmW8wU8gjvnw9t980YJb8BAlEzy6u1JlTTFhACHuB9Z351Rh1RvI52bo");  

			$headers = array(
			"api-version: 2020-05-19",
			"Content-Type: application/json",
			"Cookie: incap_ses_1121_2182539=QT3KXZEecX2d/ChCG5iOD2KJUmIAAAAAYtEIgBsL+qHGfQCeJTWebg==; nlbi_2182539=YpYpIplgNDqj5eABjjCKbQAAAACczhwJhdQ00tQ3kV7Ijz2G",
			);
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
			if ($this->user->is_logged() && $this->session->userdata('shipping_address_id')) {			
				$param_create_user = '{
					"given_names": "'.$user['name'].'",
					"surname":"'.$user['name'].'",
					"email": "'.$user['email'].'",
					"mobile_number": "'.$user['telephone'].'",
					"reference_id": "mpmindo'. time().'",
					"addresses": [{
						"street_line1": "'.$address['address'].'",
						"city": "'.$address['city'].'",
						"province": "'.$address['province'].'",
						"postal_code": "'.$address['postcode'].'",
						"country": "ID"
					}]
				}';	
			} elseif ($this->session->userdata('guest')) {
				$data_guest = $this->session->userdata('guest');
				$param_create_user = '{
					"given_names": "'.$data_guest['name'].'",
					"surname":"'.$data_guest['name'].'",
					"email": "'.$data_guest['email'].'",
					"mobile_number": "'.$data_guest['telephone'].'",
					"reference_id": "mpmindo'. time().'",
					"addresses": [{
						"street_line1": "'.$data_guest['address'].'",
						"city": "'.$data_guest['city'].'",
						"province": "'.$data_guest['province'].'",
						"postal_code": "'.$data_guest['postcode'].'",
						"country": "ID"
					}]
				}';
			}

			curl_setopt($curl, CURLOPT_POSTFIELDS, $param_create_user);

			//for debug only!
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

			$resp = curl_exec($curl);
			curl_close($curl);
			$resp = json_decode($resp);

			$this->session->set_userdata('user_id_xendit', $resp->id);
		}


		if ($this->input->post()) {
			$json = array();

			if ($this->user->is_logged() && $this->session->userdata('shipping_address_id')) {			
				$shipping_address = $this->address_model->get_address($this->session->userdata('shipping_address_id'));		
			} elseif ($this->session->userdata('guest')) {
				$shipping_address = $this->session->userdata('guest');
			}

			if (empty($shipping_address)) {
				$json['redirect'] = site_url('checkout');
			}

			if ( ! $json) {
				if ( ! $this->input->post('shipping_method')) {
					$json['error']['warning'] = $this->lang->line('error_shipping');
				} else {
					$shipping = explode('.', $this->input->post('shipping_method'));
					$shipping_methods = $this->session->userdata('shipping_methods');
						
					if (!isset($shipping[0]) or !isset($shipping[1]) or !isset($shipping_methods[$shipping[0]]['quote'][$shipping[1]])) {			
						$json['error']['warning'] = $this->lang->line('error_shipping');
					}
				}

				if ( ! $json) {
					$shipping = explode('.', $this->input->post('shipping_method'));
					$shipping_methods = $this->session->userdata('shipping_methods');
					
					$shipping_method_session = $shipping_methods[$shipping[0]]['quote'][$shipping[1]];
					
					$comment = strip_tags($this->input->post('comment'));
					
					$this->session->set_userdata('shipping_method', $shipping_method_session);
					$this->session->set_userdata('comment', $comment);
				}
			}

			$this->output->set_output(json_encode($json));
		} else {
			if ($this->user->is_logged() && $this->session->userdata('shipping_address_id')) {					
				$shipping_address = $this->address_model->get_address($this->session->userdata('shipping_address_id'));		
			} elseif ($this->session->userdata('guest')) {
				$shipping_address = $this->session->userdata('guest');
			}
			
			if ( ! empty($shipping_address)) {
				$this->load->library('currency');
				$this->load->library('image');
				$this->load->library('rajaongkir');
				$this->load->model('category_model');
				
				$shipping_method = $this->session->userdata('shipping_method');
				$quote_data = array();
			
				$quote = $this->rajaongkir->get_quote($shipping_address);
					
				if ($quote) {
					$quote_data[$quote['code']] = array( 
						'title' => $quote['title'],
						'quote' => $quote['quote'],
						'error' => $quote['error']
					);
				}
				
				$shipping_methods = $quote_data;
				
				$this->session->set_userdata('shipping_methods', $shipping_methods);
			}

			$data['text_shipping_method'] = $this->lang->line('text_shipping_method');
			$data['text_comments'] = $this->lang->line('text_comments');

			if ( ! $this->session->userdata('shipping_methods')) {
				$data['error_warning'] = sprintf($this->lang->line('error_no_shipping'), site_url('contact'));
			} else {
				$data['error_warning'] = '';
			}

			if ($this->session->userdata('shipping_methods')) {
				$data['shipping_methods'] = $this->session->userdata('shipping_methods'); 
			} else {
				$data['shipping_methods'] = array();
			}
			
			if ($this->session->userdata('shipping_method')) {
				$shipping_method = $this->session->userdata('shipping_method');
				$data['shipping_code'] = $shipping_method['code'];
			} else {
				$data['shipping_code'] = '';
			}

			if ($this->session->userdata('comment')) {
				$data['comment'] = $this->session->userdata('comment');
			} else {
				$data['comment'] = '';
			}
			
			$data['action']	= site_url('checkout/shipping_method');
			
			return $this->load->layout(false)->view('checkout/shipping_method', $data);
		}
	}
	
	/**
	 * Payment method
	 * 
	 * @access public
	 * @return void
	 */
	public function payment_method()
	{
		
		$this->load->model('user_model');
		check_ajax();
		
		if ($this->input->post()) {
			$this->lang->load('checkout');

			$json = array();		

			if (!$this->shopping_cart->has_products() || !$this->shopping_cart->has_stock()) {
				$json['redirect'] = site_url('cart');				
			}

			$products = $this->shopping_cart->get_products();

			foreach ($products as $product) {
				$product_total = 0;

				foreach ($products as $product_2) {
					if ($product_2['product_id'] == $product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}		

				if ($product['minimum'] > $product_total) {
					$this->session->set_flashdata('error', 'Jumlah pembelian item harus minimum '.$product['minimum'].'!');
					$json['redirect'] = site_url('cart');
					break;
				}
			}
			
			$payment_methods = $this->session->userdata('payment_methods');

			if (!$json) {
				if (!$this->input->post('payment_method')) {
					$json['error']['warning'] = $this->lang->line('error_payment');
				} elseif (!isset($payment_methods[$this->input->post('payment_method')])) {
					$json['error']['warning'] = $this->lang->line('error_payment');
				}
				
				if (!$this->input->post('agree')) {
					$json['error']['warning'] = 'Anda harus menyetujui syarat dan ketentuan yang berlaku!';
				}

				if ( ! $json) {
					$this->session->set_userdata('payment_method', $payment_methods[$this->input->post('payment_method')]);
				}						
			}

			$this->output->set_output(json_encode($json));
		} else {
			$this->lang->load('checkout');
			$this->load->model('address_model');

			if ($this->user->is_logged() and $this->session->userdata('shipping_address_id')) {					
				$payment_address = $this->address_model->get_address($this->session->userdata('shipping_address_id'));		
			} elseif ($this->session->userdata('guest')) {
				$payment_address = $this->session->userdata('guest');
			}

			if ( ! empty($payment_address)) {
				$this->load->library('total');
				$this->load->helper('array');
					
				list($total_data, $total) = $this->total->get_totals();
					
				$total_data = reorder($total_data, 'sort_order', 'asc');

				$method_data = array();
				
				// pembayaran menggunakan Midtrans
				$this->load->model('midtrans_model');
				$results = $this->midtrans_model->get_methods();

				$item_details = array();
				foreach ($this->session->product as $key => $order_product) {
					$item_details[] = array(					
						"type" 				=> "PHYSICAL_PRODUCT",
						"reference_id" 		=> "mpmidno" . time() . $key,
						"name" 				=> $order_product['name'],
						"net_unit_amount" 	=> round((float)$order_product['ori_total'], 0),
						"quantity" 			=> (int)$order_product['quantity'],
						"url" 				=> "https://mpmindo.id/",
						"category" 			=> "shop"
					);
				}
				
				$plan_paylater = array();
				foreach ($results as $result) {
					$method_data[$result['code']] = $result;
					$code = explode(".", $result['code']);
					$payment_gateway = $code[0];
					$payment_method = $code[1];
					if($payment_gateway == "xendit"){
						
						$parameter_plan = [
							"customer_id"	=> $this->session->userdata('user_id_xendit'),
							"channel_code"	=> $payment_method,
							"currency"		=> "IDR",
							"amount"		=> $this->total->get_totals()[1],
							"order_items"	=> $item_details,
						];

						$plan = \Xendit\PayLater::initiatePayLaterPlans($parameter_plan);
						$plan_paylater[$payment_method] = $plan['id'];
						$method_data[$result['code']]["instalment"] = $plan['options'];
					}
				}
				if ($this->user->is_logged() and $this->session->userdata('shipping_address_id')) {		
					$this->user_model->update_user($this->user->user_id(), ["xendit_plan" => json_encode($plan_paylater)]);	
				} 
				
				$sort_order = array(); 

				foreach ($method_data as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort($sort_order, SORT_ASC, $method_data);			
				$this->session->set_userdata('plan_paylater', $plan_paylater);
				$this->session->set_userdata('payment_methods', $method_data);
			}

			$data['text_payment_method'] = $this->lang->line('text_payment_method');
			$data['text_comments'] = $this->lang->line('text_comments');

			if (!$this->session->userdata('payment_methods')) {
				$data['error_warning'] = sprintf($this->lang->line('error_no_payment'), site_url('contact'));
			} else {
				$data['error_warning'] = '';
			}	

			if ($this->session->userdata('payment_methods')) {
				$data['payment_methods'] = $this->session->userdata('payment_methods'); 
			} else {
				$data['payment_methods'] = array();
			}
			
			$payment_method = $this->session->userdata('payment_method');

			if (isset($payment_method['code'])) {
				$data['code'] = $payment_method['code'];
			} else {
				$data['code'] = '';
			}
			
			$data['action']	= site_url('checkout/payment_method');
			
			return $this->load->layout(false)->view('checkout/payment_method', $data);
		}
	}
	
	/**
	 * Confirm
	 * 
	 * @access public
	 * @return void
	 */
	public function confirm()
	{
		check_ajax();
		
		$this->load->model('user_model');
		$this->load->model('address_model');
		
		$redirect = '';
		
		$data['order'] = array();
		$data['order_products'] = array();
		$data['order_totals'] = array();
		
		if ($this->shopping_cart->has_shipping()) {
			if ($this->user->is_logged()) {
				$shipping_address = $this->address_model->get_address($this->session->userdata('shipping_address_id'));
			} elseif ($this->session->userdata('guest')) {
				$shipping_address = $this->session->userdata('guest');
			} else {
				$shipping_address = false;
			}
			
			if ( ! $shipping_address) {
				$redirect = site_url('checkout');
			}
	
			if ( ! $this->session->userdata('shipping_method')) {
				$redirect = site_url('checkout');
			}
		} else {
			$this->session->unset_userdata('shipping_method');
			$this->session->unset_userdata('shipping_methods');
		}
		
		if ( ! $this->session->userdata('payment_method')) {
			$redirect = site_url('checkout');
		}
		
		if ( ! $this->shopping_cart->has_products()) {
			$redirect = site_url('cart');			
		}
		
		$products = $this->shopping_cart->get_products();
		$product_names = array();

		foreach ($products as $product) {
			$product_names[] = $product['name'];
			
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}
			
			if ($this->config->item('stock_checkout') && $product['minimum'] < $product_total) {
				$this->session->set_flashdata('error', 'Jumlah pembelian item harus minimum '.$product['minimum'].'!');
				$redirect = site_url('cart');
				break;
			}			
		}

		if ( ! $redirect) {
			$this->load->library('total');
			$this->load->helper('array');
				
			list($total_data, $total) = $this->total->get_totals();
			$total_data = reorder($total_data, 'sort_order', 'asc');
			$user = $this->user_model->get($this->user->user_id());
			$order = array();
			$shipping_method = $this->session->userdata('shipping_method');
			
			if ($this->user->is_logged()) {
				$order['user_id'] = $this->user->user_id();
				$order['user_name']	= $this->user->name();
				$order['user_email'] = $this->user->email();
				$order['user_telephone'] = $user['telephone'];
			} else {
				$order['user_id'] = null;
				$order['user_name']	= $shipping_address['name'];
				$order['user_email'] = $shipping_address['email'];
				$order['user_telephone'] = $shipping_address['telephone'];
			}
			
			$order['user_address'] = $shipping_address['address'];
			$order['user_postcode'] = $shipping_address['postcode'];
			$order['user_province'] = $shipping_address['province'];
			$order['user_province_id'] = $shipping_address['province_id'];
			$order['user_city']	= $shipping_address['city'];
			$order['user_city_id'] = $shipping_address['city_id'];
			$order['user_subdistrict'] = $shipping_address['subdistrict'];
			$order['user_subdistrict_id'] = $shipping_address['subdistrict_id'];
			$order['user_agent'] = $this->input->user_agent();
			$order['total']	= $total;
			$order['currency_id'] = $this->currency->get_id();
			$order['currency_value'] = $this->currency->get_value($this->currency->get_code());
			$order['ip'] = $this->input->ip_address();
			
			$payment_method = $this->session->userdata('payment_method');

			$order['payment_method'] = isset($payment_method['title']) ? $payment_method['title'] : '';
			$order['payment_code'] = isset($payment_method['code']) ? $payment_method['code'] : '';
			
			$subtotal = 0;
			$product_data = array();
			
			foreach ($this->shopping_cart->get_products() as $product) {
				$product_data[] = array(
					'product_id' => $product['product_id'],
					'name' => $product['name'],
					'sku' => $product['sku'],
					'quantity' => $product['quantity'],
					'price' => $product['price'],
					'price_base' => $product['price_base'],
					'total' => $product['total'],
					'total_base' => $product['total_base'],
					'tax' => (float)$product['tax_total'],
					'tax_value' => $product['tax_value'],
					'tax_type' => $product['tax_type']
				);
				
				$subtotal += $product['total'];
			}
			
			$order['subtotal'] = (float)$subtotal;
			$order['shipping_method'] = $shipping_method['title'];
			$order['shipping_code'] = $shipping_method['code'];
			$order['comment'] = $this->session->userdata('comment');
			$order['tax_id'] = $this->session->userdata('tax_id');
			$order['tax_name'] = $this->session->userdata('tax_name');
			$order['tax_address'] = $this->session->userdata('tax_address');
			$order['order_status_id'] = (int)$this->config->item('order_status_id');

			$this->load->model('order_model');
			
			$order_id = $this->session->userdata('order_id');
			
			if ($order_id) {
				$this->order_model->update_order($order_id, $order, $product_data, $total_data);
			} else {
				$order_id = $this->order_model->create_order($order, $product_data, $total_data);
				$this->session->set_userdata('order_id', $order_id);
				
				$orderdata = $this->order_model->get_order($order_id);

				// notification
				$this->load->model('notification_model');
				$this->notification_model->add_notification(array(
					'title' => 'Pemesanan #'.$orderdata['invoice_no'],
					'message' => 'Ada pesanan baru nomor #'.$orderdata['invoice_no'],
					'table' => 'order',
					'table_id' => $order_id,
					'date_added' => date('Y-m-d H:i:s')
				));
			}
			
			$order['order_id'] = $order_id;
			
			$data['order'] = $order;
			$data['order_products'] = $product_data;
			$data['order_totals'] = $total_data;
			$data['orderdata'] = $this->order_model->get_order($order_id);
			
			$payment_gateway = explode('.', $payment_method['code']);
			$payment_gateway = $payment_gateway[0];

			if($payment_gateway == "xendit"){
				$payload['action'] = site_url('payment/confirm');
				
				$data['payment'] = $this->load->layout(false)->view('checkout/xendit', $payload, true);
			}elseif($payment_gateway == "midtrans"){

				// gunakan Midtrans SNAP
				$this->load->config('midtrans');
				
				$payload['action'] = site_url('payment/confirm');
				$payload['client_key'] = $this->config->item('client_key');
				$payload['snap_js'] = (bool)$this->config->item('is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js';
				
				$data['payment'] = $this->load->layout(false)->view('checkout/midtrans', $payload, true);
			}
			
		} else {
			$data['redirect'] = $redirect;
		}
		
		return $this->load->layout(false)->view('checkout/confirm', $data);
	}
	
	/**
	 * Success
	 * 
	 * @access public
	 * @return void
	 */
	public function success()
	{
		if ($this->session->userdata('order_id')) {
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
			$this->session->unset_userdata('tax_id');
			$this->session->unset_userdata('tax_name');
			$this->session->unset_userdata('tax_address');
			$this->session->unset_userdata('no_tax_id');
	
			$this->session->set_flashdata('action_success', [
				'title' => 'Pesanan Berhasil',
				'content' => '<p>Terima kasih telah berbelanja di '.$this->config->item('site_name').'</p><h4>Rincian transaksi telah kami kirimkan ke email Anda.</h4>',
				'redirect' => site_url()
			]);
				
			redirect('page/success');
		} else {
			show_404();
		}
	}
}