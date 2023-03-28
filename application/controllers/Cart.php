<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends MY_Controller
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
		$this->load->library('currency');
		$this->load->helper('form');
		$this->load->helper('language');
		
		$this->lang->load('cart');
		
		$this->load->library('image');
		$this->load->model('category_model');
	}
	
	/**
	 * Shopping cart page
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		if ($this->input->post('quantity') && is_array($this->input->post('quantity'))) {
			foreach ($this->input->post('quantity') as $key => $value) {
				$this->shopping_cart->update($key, $value);
			}
			
			$this->session->unset_userdata('shipping_method');
			$this->session->unset_userdata('shipping_methods');
			$this->session->unset_userdata('payment_method');
			$this->session->unset_userdata('payment_methods');
			
			$this->session->set_flashdata('success', lang('text_remove'));

			redirect('cart');
		}
		
		if ($this->input->post('coupon')) {
			$this->load->model('coupon_model');
			
			if ($this->coupon_model->get_coupon($this->input->post('coupon'))) {
				$this->session->set_userdata('coupon', $this->input->post('coupon'));
				$this->session->set_flashdata('success', lang('text_coupon'));	
			} else {
				$this->session->set_flashdata('error', 'Kesalahan: Kupon tidak valid atau sudah kadaluarsa!');
			}

			redirect('cart');
		}
			
		if ($this->session->flashdata('error')) {
			$data['error'] = $this->session->flashdata('error');
		} elseif ( ! $this->shopping_cart->has_stock()) {
			$data['error'] = lang('error_stock');
		} else {
			$data['error'] = false;
		}

		if ($this->config->item('member_price') && ! $this->user->is_logged()) {
			$data['attention'] = sprintf(lang('text_login'), site_url('account/login'), site_url('account/register'));
		} else {
			$data['attention'] = '';
		}
			
		$data['success'] = $this->session->flashdata('success');
		$data['action'] = site_url('cart'); 

		if ($this->config->item('cart_weight')) {
			$data['weight'] = $this->weight->format($this->shopping_cart->get_weight(), $this->config->item('weight_class_id'));
		} else {
			$data['weight'] = $this->weight->format($this->shopping_cart->get_weight(), $this->config->item('weight_class_id'));
		}

		$data['products'] = array();
			
		$products = $this->shopping_cart->get_products();
			
		foreach ($products as $product) {
			$product_total = 0;
			$subtotal = 0;
			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
					$subtotal += $product_2['total'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$data['error_warning'] = sprintf(lang('error_minimum'), $product['name'], $product['minimum']);
			}

			if ($product['image']) {
				$image = $this->image->resize($product['image'], 100, 100);
			} else {
				$image = $this->image->resize('no_image.jpg', 100, 100);
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
			
			$product['href'] = site_url('product/'.$path.$product['slug']);
			$product['thumb'] = $image;
			$product['price'] = $this->currency->format($product['price']);
			$product['total'] = $this->currency->format($product['total']);
			$product['remove'] = site_url('cart').'?remove='.$product['key'];
			
			$data['products'][] = $product;
		}

		$data['next'] = $this->input->post('next') ? $this->input->post('next') : '';
		$data['coupon_status'] = $this->config->item('coupon_status');

		if ($this->input->post('coupon')) {
			$data['coupon'] = $this->input->post('coupon');			
		} elseif ($this->session->userdata('coupon')) {
			$data['coupon'] = $this->session->userdata('coupon');
		} else {
			$data['coupon'] = '';
		}

		$data['shipping_status'] = $this->config->item('shipping_status') && $this->config->item('shipping_estimator') && $this->shopping_cart->has_shipping();
		
		$this->load->model('location_model');
		
		$data['provinces'] = $this->location_model->get_provinces();
			
		if ($this->input->post('province_id')) {
			$data['province_id'] = $this->input->post('province_id');
		} elseif ($this->session->userdata('shipping_province_id')) {
			$data['province_id'] = $this->session->userdata('shipping_province_id');
		} else {
			$data['province_id'] = '';
		}
			
		if ($this->input->post('regency_id')) {
			$data['regency_id'] = $this->input->post('regency_id');
		} elseif ($this->session->userdata('shipping_regency_id')) {
			$data['regency_id'] = $this->session->userdata('shipping_regency_id');
		} else {
			$data['regency_id'] = '';
		}
			
		if ($this->input->post('district_id')) {
			$data['district_id'] = $this->input->post('district_id');
		} elseif ($this->session->userdata('shipping_district_id')) {
			$data['district_id'] = $this->session->userdata('shipping_district_id');
		} else {
			$data['district_id'] = '';
		}
			
		if ($this->input->post('postcode')) {
			$data['postcode'] = $this->input->post('postcode');				
		} elseif ($this->session->userdata('postcode')) {
			$data['postcode'] = $this->session->userdata('postcode');
		} else {
			$data['postcode'] = '';
		}
		
		$this->load->library('total');
		$this->load->helper('array');
				
		list($total_data, $total) = $this->total->get_totals('subtotal', 'shipping', 'total');
				
		$total_data = reorder($total_data, 'sort_order', 'asc');
				
		$data['totals'] = $total_data;
		$data['continue'] = site_url();
		$data['checkout'] = site_url('checkout');
		$data['first_category'] = $this->category_model->get_first_category();
			
		$this->load->view('cart', $data);
	}
	
	/**
	 * Add product to shopping cart
	 * 
	 * @access public
	 * @return void
	 */
	public function add()
	{
		$json = array();

		$product_id = $this->input->post('product_id') ? $this->input->post('product_id') : 0;

		$this->load->model('product_model');

		if ($product = $this->product_model->get_product($product_id)) {
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
			
			$quantity = $this->input->post('quantity') ? $this->input->post('quantity') : 1;

			if ( ! $json) {
				$this->shopping_cart->add($product_id, $quantity);
				
				$json['success'] = sprintf(lang('text_success'), site_url('product/'.$path.$product['slug']), $product['name'], site_url('cart'));

				$this->session->unset_userdata('shipping_method');
				$this->session->unset_userdata('shipping_methods');
				$this->session->unset_userdata('payment_method');
				$this->session->unset_userdata('payment_methods');
			} else {
				$json['redirect'] = 'product/'.$path.$product['slug'];
			}
		}

		$this->output->set_output(json_encode($json));
	}
	
	public function edit()
	{
		$json = array();

		$product_id = $this->input->post('key') ? $this->input->post('key') : 0;

		$this->load->model('product_model');

		if ($product = $this->product_model->get_product($product_id)) {
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
			
			$quantity = $this->input->post('quantity') ? $this->input->post('quantity') : 1;

			if ( ! $json) {
				$this->shopping_cart->update($product_id, $quantity);

				$json['success'] = sprintf(lang('text_success'), site_url('product/'.$path.$product['slug']), $product['name'], site_url('cart'));

				$this->session->unset_userdata('shipping_method');
				$this->session->unset_userdata('shipping_methods');
				$this->session->unset_userdata('payment_method');
				$this->session->unset_userdata('payment_methods');
			} else {
				$json['redirect'] = 'product/'.$path.$product['slug'];
			}
		}

		$this->output->set_output(json_encode($json));
	}

	protected function validate_shipping()
	{
		$error = false;
		
		if ($this->input->post('shipping_method'))
		{
			$shipping = explode('.', $this->input->post('shipping_method'));
			$shipping_methods = $this->session->userdata('shipping_methods');
			
			if ( ! isset($shipping[0]) || ! isset($shipping[1]) || ! isset($shipping_methods[$shipping[0]]['quote'][$shipping[1]]))
			{			
				$error = lang('error_shipping');
			}
		}
		else
		{
			$error = lang('error_shipping');
		}
		
		if ($error)
		{
			$this->session->set_flashdata('error', $error);
			
			return false;
		}

		return true;		
	}
	
	/**
	 * Coupon validation
	 * 
	 * @access protected
	 * @return bool
	 */
	protected function validate_coupon()
	{
		$this->load->model('coupon_model');

		if ( ! $this->coupon_model->get_coupon($this->input->post('coupon'))) {
			$this->session->set_flashdata('error', lang('error_coupon'));
			return false;
		}
		
		return true;	
	}
	
	/**
	 * Info
	 * 
	 * @access public
	 * @return void
	 */
	public function info($type = 'desktop')
	{
		check_ajax();
		
		$this->lang->load('cart');
		$this->load->library('image');
		$this->load->library('currency');
		$this->load->library('total');
		$this->load->helper('array');

		$data['products'] = array();
		$products = $this->shopping_cart->get_products();
		
		foreach ($products as $product) {
			if ($product['image']) {
				$image = $this->image->resize($product['image'], 100, 100);
			} else {
				$image = $this->image->resize('no_image.jpg', 100, 100);;
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
				'total' => $this->currency->format($product['price'] * $product['quantity']),
				'href'=> site_url('product/'.$path.$product['slug']),
				'tax_value' => $product['tax_value'],
				'tax_type' => $product['tax_type']
			);
		}
		
		list($total_data, $total) = $this->total->get_totals();
				
		$total_data = reorder($total_data, 'sort_order', 'asc');
		
		$data['totals'] = array();

		foreach ($total_data as $result) {
			$data['totals'][] = array(
				'title' => $result['title'],
				'text'=> $this->currency->format($result['value']),
			);
		}

		$data['cart'] = site_url('cart');
		$data['checkout'] = site_url('checkout');
		
		if ($type == 'desktop') {
			echo $this->load->layout(false)->view('cart_info', $data);
		} else {
			echo $this->load->layout(false)->view('cart_info_mobile', $data);
		}
	}
	
	/**
	 * Remove item via ajax
	 * 
	 * @access public
	 * @return void
	 */
	public function remove()
	{
		$json = array();
		
		if ($this->input->post('key')) {
			
			$this->shopping_cart->remove($this->input->post('key'));
			
			$this->session->set_flashdata('success', lang('text_remove'));

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
			
			$this->output->set_output(json_encode($json));
		}
	}
}