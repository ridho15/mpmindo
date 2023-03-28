<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_Model extends MY_Model
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
	 * Create order
	 * 
	 * @access public
	 * @param array $order
	 * @param array $products
	 * @param array $totals
	 * @return int
	 */
	public function create_order($order, $products, $totals)
	{
		$order_id = $this->insert($order);
		
		if ($order_id) {
			$this->set_order_products($order_id, $products);
			$this->set_order_totals($order_id, $totals);
			$this->set_invoice($order_id);	
			$this->set_order_product_details($order_id, $products);
			$this->set_order_product_ratings($order_id, $products);
		}
		
		return $order_id;
	}
	
	/**
	 * Update order
	 * 
	 * @access public
	 * @param int $order_id
	 * @param array $order
	 * @param array $products
	 * @param float $totals
	 * @return int
	 */
	public function update_order($order_id, $order, $products, $totals)
	{
		$this->update($order_id, $order);
		
		$this->db
		->where('order_id', $order_id)
		->delete(array('order_product', 'order_total', 'order_product_detail', 'order_product_rating'));
		
		$this->set_order_products($order_id, $products);
		$this->set_order_totals($order_id, $totals);
		$this->set_order_product_details($order_id, $products);
		$this->set_order_product_ratings($order_id, $products);
		
		return $order_id;
	}
	
	/**
	 * Set order products
	 * 
	 * @access public
	 * @param array $products
	 * @return void
	 */
	public function set_order_products($order_id, $products)
	{
		$order_product = array();
		
		foreach ($products as $product) {
			$product['order_id'] = (int)$order_id;
			$order_product[] = $this->set_data($product, 'order_product');
		}
		
		if ($order_product) {
			$this->db->insert_batch('order_product', $order_product);
		}
	}

	/**
	 * Set order products detail
	 * 
	 * @access public
	 * @param array $products
	 * @return void
	 */
	public function set_order_product_details($order_id, $products)
	{
		$order_product_detail = array();
		
		foreach ($products as $product) {
			for($i = 1; $i <= $product['quantity']; $i++) {
				$product['order_id'] = (int)$order_id;
				$product['product_id'] = $product['product_id'];
				$order_product_detail[] = $this->set_data($product, 'order_product_detail');
			}
		}
		
		if ($order_product_detail) {
			$this->db->insert_batch('order_product_detail', $order_product_detail);
		}
	}
	
	/**
	 * Set order products rating
	 * 
	 * @access public
	 * @param array $products
	 * @return void
	 */
	public function set_order_product_ratings($order_id, $products)
	{
		$order_product_rating = array();
		
		foreach ($products as $product) {
			$product['order_id'] = (int)$order_id;
			$product['product_id'] = $product['product_id'];
			$product['value'] = 0;
			$order_product_rating[] = $this->set_data($product, 'order_product_rating');
		}
		
		if ($order_product_rating) {
			$this->db->insert_batch('order_product_rating', $order_product_rating);
		}
	}

	/**
	 * Set order totals
	 * 
	 * @access public
	 * @param array $totals
	 * @return void
	 */
	public function set_order_totals($order_id, $totals)
	{
		$order_total = array();
		
		foreach ($totals as $total) {
			$total['order_id'] = (int)$order_id;
			$order_total[] = $this->set_data($total, 'order_total');
		}
		
		if ($order_total) {
			$this->db->insert_batch('order_total', $order_total);
		}
	}
	
	/**
	 * Set invoice number
	 * 
	 * @access public
	 * @param int $order_id
	 * @return void
	 */
	public function set_invoice($order_id)
	{
		$this->db
		->where('order_id', (int)$order_id)
		->set('invoice_no', date('Ymd', time()).$order_id)
		->update('order');
	}
	
	/**
	 * Get order
	 * 
	 * @access public
	 * @param int $order_id
	 * @return array
	 */
	public function get_order($order_id)
	{
		return $this->db
		->select('o.*, os.name as status', false)
		->join('order_status os', 'os.order_status_id = o.order_status_id', 'left')
		->where('o.order_id', (int)$order_id)
		->get('order o')
		->row_array();
	}

	public function get_order_by_payment_plan($paymen_plan)
	{
		return $this->db
		->select('o.*, os.name as status', false)
		->join('order_status os', 'os.order_status_id = o.order_status_id', 'left')
		->where('o.payment_plan', (int)$paymen_plan)
		->get('order o')
		->row_array();
	}
	
	/**
	 * Get order products
	 * 
	 * @access public
	 * @param int $order_id
	 * @return array
	 */
	public function get_order_products($order_id)
	{
		return $this->db
		->select('op.*')
		->from('order_product op')
		->join('product p', 'p.product_id = op.product_id', 'left')
		->where('op.order_id', (int)$order_id)
		->get()
		->result_array();
	}
	
	/**
	 * Get order totals
	 * 
	 * @access public
	 * @param int $order_id
	 * @return array
	 */
	public function get_order_totals($order_id)
	{
		return $this->db
		->where('order_id', (int)$order_id)
		->order_by('sort_order', 'asc')
		->get('order_total')
		->result_array();
	}
	
	/**
	 * Order confirmation
	 * 
	 * @access public
	 * @param int $order_id
	 * @param int $order_status_id
	 * @param string $comment
	 * @param bool $notifys
	 * @return void
	 */
	public function confirm($order_id, $order_status_id, $comment = '', $notify = false)
	{
		$order_info = $this->get_order($order_id);
		
		if ($order_info) {
			$this->db
			->where('order_id', (int)$order_id)
			->set(array(
				'order_status_id' => (int)$order_status_id,
				'date_modified' => date('Y-m-d H:i:s', time())
			))->update('order');
			
			$this->load->model('order_history_model');
			
			$order_history = array(
				'order_id' => (int)$order_id,
				'order_status_id' => (int)$order_status_id,
				'notify' => (int)$notify,
				'comment' => $comment
			);
			
			$this->order_history_model->insert($order_history);
			
			$this->load->library('currency');
			
			$data['products'] = array();
			
			foreach ($this->get_order_products($order_id) as $order_product) {
				$data['products'][] = array(
					'name' => $order_product['name'],
					'quantity' => $order_product['quantity'],
					'price' => $this->currency->format($order_product['price']),
					'total' => $this->currency->format($order_product['total'])
				);
			}
			
			$this->load->library('total');
			$this->total->confirm($order_id);
			
			if ($notify) {
				$this->load->library('image');
				
				$data['logo'] = $this->image->resize($this->config->item('logo'), 200);
				$data['order_id'] = $order_id;
				$data['invoice'] = $order_info['invoice_no'];
				$data['name'] = $order_info['user_name'];
				$data['telephone'] = $order_info['user_telephone'];
				$data['email'] = $order_info['user_email'];
				$data['date_added'] = date('d/m/Y', strtotime($order_info['date_added']));
				$data['status'] = $order_info['status'];
				$data['comment'] = nl2br($comment);
				$data['ip_address'] = $order_info['ip'];
				
				if ($this->config->item('instruction', 'payment_'.$order_info['payment_code'])) {
					$data['payment_instruction'] = $this->config->item('instruction', 'payment_'.$order_info['payment_code']);
				} else {
					$data['payment_instruction'] = false;
				}
				
				$data['bank_accounts'] = [];
				
				if ($this->config->item('bank_accounts', 'payment_'.$order_info['payment_code'])) {
					$this->load->model('bank_account_model');
					
					foreach ($this->config->item('bank_accounts', 'payment_'.$order_info['payment_code']) as $bank_account_id) {
						$data['bank_accounts'][] = $this->bank_account_model->get_bank_account($bank_account_id);	
					}
				}
				
				if ($this->config->item('qrcode', 'payment_'.$order_info['payment_code'])) {
					$data['qrcode'] = $this->image->resize($this->config->item('qrcode', 'payment_'.$order_info['payment_code']), 500);
				} else {
					$data['qrcode'] = false;
				}
				
				$this->load->helper('format');
				
				$address['name'] = $order_info['user_name'];
				$address['address'] = $order_info['user_address'];
				$address['telephone'] = $order_info['user_telephone'];
				$address['postcode'] = $order_info['user_postcode'];
				$address['subdistrict'] = $order_info['user_subdistrict'];
				$address['city'] = $order_info['user_city'];
				$address['province'] = $order_info['user_province'];
				
				$data['address'] = format_address($address);
				$data['payment_method'] = $order_info['payment_method'];
				$data['totals'] = $this->get_order_totals($order_id);
						
				$this->load->library('email');
				$this->load->config('email');
				
				$this->email->from($this->config->item('sender'), $this->config->item('site_name'));
				$this->email->to($order_info['user_email']);
				$this->email->cc($this->config->item('email'));
				$this->email->subject('Konfirmasi Pesanan');
				$this->email->message($this->load->layout(false)->view('emails/order', $data, true));
				
				if ($order_tax = $this->get_order_tax($order_id, true)) {
					$this->email->attach($order_tax, 'attachment', $order_info['invoice_no'].'-pajak.pdf');
				}
				
				if ($order_tax = $this->get_order_no_tax($order_id, true)) {
					$this->email->attach($order_tax, 'attachment', $order_info['invoice_no'].'.pdf');
				}
				
				$this->email->send();
			}
		}
	}
	
	/**
	 * Delete order
	 * 
	 * @access public
	 * @param int $order_id
	 * @return void
	 */
	public function delete($order_id)
	{
		if (is_array($order_id)) {
			foreach ($order_id as $id) {
				$this->delete($id);
			}
		} else {
			$this->db
			->where('order_id', $order_id)
			->delete(array(
				'order',
				'order_history',
				'order_product',
				'order_total'
			));
			
			$this->db->reset_query();
			
			$this->db
			->where('trans_table', 'order')
			->where('trans_table_id', $order_id)
			->delete('inventory');
		}
	}
	
	/**
	 * Get chart of sales
	 * 
	 * @access public
	 * @param array $params
	 * @return array
	 */
	public function get_chart_sales($params = array())
	{
		$results = $this->db
		->select('SUM(total) as total, DATE_FORMAT(date_added, "%d") as day, DATE_FORMAT(date_added, "%m") as month', false)
		->where('date_added >= ', $params['start'])
		->where('date_added <= ', $params['end']);
		
		if ( ! empty($params['order_status_id'])) {
			$results = $this->db->where('order_status_id', (int)$params['order_status_id']);
		}
		
		$results = $this->db
		->order_by($params['group'], 'asc')
		->group_by($params['group'])
		->get('order')
		->result_array();
		
		$sales = array();
		
		foreach ($results as $result) {
			$sales[(int)$result[$params['group']]] = $result['total'];
		}
		
		return $sales;
	}
	
	/**
	 * Count sales
	 * 
	 * @access public
	 * @param array $params
	 * @return float
	 */
	public function count_sales($params)
	{
		$query = $this->db
		->select_sum('total')
		->where('date_added >= ', $params['start'])
		->where('date_added <= ', $params['end']);
		
		if ( ! empty($params['order_status_id'])) {
			$query = $this->db->where('order_status_id', (int)$params['order_status_id']);
		}
		
		$query = $this->db->get('order')->row_array();
		
		return $query ? $query['total'] : 0;
	}
	
	/**
	 * Count product sales
	 * 
	 * @access public
	 * @param array $params
	 * @return float
	 */
	public function count_product_sales($params)
	{
		$query = $this->db
		->select_sum('op.total')
		->join('order o', 'o.order_id = op.order_id', 'left')
		->where('o.date_added >= ', $params['start'])
		->where('o.date_added <= ', $params['end'])
		->where('o.order_status_id', (int)$params['order_status_id']);
		
		if ($params['tax']) {
			$query = $this->db->where('op.tax >', 0);
		} else {
			$query = $this->db->where('op.tax', 0);
		}
		
		$query = $this->db->get('order_product op')->row_array();
		
		return $query ? $query['total'] : 0;
	}
	
	/**
	 * Count buyers
	 * 
	 * @access public
	 * @param array $params
	 * @return int
	 */
	public function count_buyers($params)
	{
		$query = $this->db
		->select('order_id')
		->where('date_added >= ', $params['start'])
		->where('date_added <= ', $params['end']);
		
		if ( ! empty($params['order_status_id'])) {
			$query = $this->db->where('order_status_id', (int)$params['order_status_id']);
		}
		
		if ( ! empty($params['registered'])) {
			$query = $this->db->where('user_id >', 0);
		}
		
		if ( ! empty($params['guest'])) {
			$query = $this->db->where('user_id', 0);
		}
		
		$query = $this->db->group_by('user_email')->count_all_results('order');
		
		return (int)$query;
	}
	
	/**
	 * Get chart of orders
	 * 
	 * @access public
	 * @param array $data
	 * @param array $params
	 * @return array
	 */
	public function get_chart_orders($data = array(), $params = array())
	{
		$results = $this->db
		->select('COUNT(order_id) as total, DATE_FORMAT(date_added, "%d") as day, DATE_FORMAT(date_added, "%m") as month', false)
		->from('order')
		->where('date_added >= ', $params['start'])
		->where('date_added <= ', $params['end']);
		
		if (isset($data['order_status_id'])) {
			$results = $this->db->where('order_status_id', $data['order_status_id']);
		}
		
		$results = $this->db
		->order_by($params['group'], 'asc')
		->group_by($params['group'])
		->get()
		->result_array();
		
		$orders = array();
		
		foreach ($results as $result) {
			$orders[(int)$result[$params['group']]] = $result['total'];
		}
		
		return $orders;
	}
	
	public function get_conversion_rate()
	{
		return $this->db
		->select('op.product_id, (SELECT SUM(viewed) FROM product_view pv WHERE pv.product_id = op.product_id) AS viewed, SUM(op.quantity) AS sold', false)
		->from('order_product op')
		->join('order o', 'o.order_id = op.order_id', 'left')
		->where('o.order_status_id', (int)$this->config->item('order_complete_status_id'))
		->group_by('op.product_id')
		->get()
		->result_array();
	}
	
	/**
	 * Get histories
	 * 
	 * @access public
	 * @param int $order_id
	 * @return array
	 */
	public function get_histories($order_id = null)
	{
		return $this->db
		->select('oh.*, oh.date_added as date_added, os.name as status', false)
		->join('order_status os', 'os.order_status_id = oh.order_status_id', 'left')
		->where('oh.order_id ', $order_id)
		// ->where('oh.notify', 1)
		->from('order_history oh')
		->order_by('oh.order_history_id','desc')
		->get()
		->result_array();
	}
	
	/**
	 * Get waybill
	 * 
	 * @access public
	 * @param int $order_id
	 * @return string
	 */
	public function get_waybill($order_id = null)
	{
		$order_history = $this->db
		->select('o.shipping_code, oh.waybill')
		->from('order_history oh')
		->join('order o', 'o.order_id = oh.order_id', 'left')
		->where('oh.order_id', $order_id)
		->where('oh.waybill !=', '')
		->order_by('oh.order_history_id', 'desc')
		->get()
		->row_array();
			
		if ($order_history) {
			return $order_history['waybill'];
		} else {
			return '';
		}
	}
	
	/**
	 * Update stock
	 * 
	 * @access public
	 * @param int $order_id (default: null)
	 * @return void
	 */
	public function update_stock($order_id = null)
	{
		$products = $this->get_order_products($order_id);
		$inventories = array();
		
		foreach ($products as $product) {
			$inventory = $this->db
			->where('trans_table', 'order')
			->where('trans_table_id', (int)$product['order_id'])
			->where('product_id', (int)$product['product_id'])
			->get('inventory')
			->row_array();
			
			if ($inventory) {
				$this->db
				->where('trans_table', 'order')
				->where('trans_table_id', (int)$product['order_id'])
				->where('product_id', (int)$product['product_id'])
				->set('quantity', ((int)$product['quantity'] * -1))
				->update('inventory');
			} else {
				$inventories[] = array(
					'trans_table' => 'order',
					'trans_table_id' => (int)$order_id,
					'product_id' => (int)$product['product_id'],
					'warehouse_id' => (int)$this->config->item('warehouse_id'),
					'quantity' => ((int)$product['quantity'] * -1),
					'date_added' => date('Y-m-d H:i:s')
				);
			}
		}
		
		if ($inventories) {
			$this->db->insert_batch('inventory', $inventories);
		}
	}
	
	/**
	 * Get order tax
	 * 
	 * @access public
	 * @param int $order_id
	 * @param bool $return_pdf
	 * @return mixed
	 */
	public function get_order_tax($order_id, $return_pdf = false)
	{
		if ($order = $this->get_order($order_id)) {
			$this->load->library('currency');
			
			$order['products'] = array();
			$order['totals'] = array();
			
			$order_products = $this->db
			->where('order_id', $order_id)
			->where('tax >', 0)
			->get('order_product')
			->result_array();
			
			if ( ! $order_products) {
				return array();
			}
			
			$subtotal = 0;
			$tax = 0;
			$total = 0;
			
			foreach ($order_products as $product) {
				$order['products'][] = array(
					'product_id' => (int)$product['product_id'],
					'name' => $product['name'],
					'sku' => $product['sku'],
					'quantity' => (int)$product['quantity'],
					'price' => (float)$product['price_base'],
					'total' => (float)$product['total_base'],
					'tax' => (float)$product['tax'],
				);
				
				$subtotal += (float)$product['total_base'];
				$tax += (float)$product['tax'];
			}
			
			$order['totals'][] = array(
				'code' => 'subtotal',
				'title' => 'Subtotal',
				'text' => $this->currency->format($subtotal),
				'value' => $subtotal,
				'sort_order' => 1
			);
			
			$order['totals'][] = array(
				'code' => 'tax',
				'title' => 'PPN 10%',
				'text' => $this->currency->format($tax),
				'value' => $tax,
				'sort_order' => 2
			);
			
			$order['totals'][] = array(
				'code' => 'total',
				'title' => 'Total',
				'text' => $this->currency->format($subtotal + $tax),
				'value' => $subtotal + $tax,
				'sort_order' => 3
			);
			
			if ($return_pdf) {
				$this->load->library('image');
				$this->load->library('pdf');
				$this->load->helper('format');
				
				$order['logo'] = str_replace(base_url(), FCPATH, $this->image->resize($this->config->item('logo'), 120));
				
				$filename = APPPATH.'cache/docs/invoice-dengan-pajak-'.$order['order_id'].'-'.date('YmdHi').'.pdf';
				
				$this->pdf->pdf_create($this->load->layout(false)->view('invoices/order_invoice_tax_pdf', $order, true), $filename, false);
				
				return file_exists($filename) ? $filename : false;
			} else {
				return $order;	
			}
		}
	}
	
	/**
	 * Get order no tax
	 * 
	 * @access public
	 * @param int $order_id
	 * @param bool $return_pdf
	 * @return mixed
	 */
	public function get_order_no_tax($order_id, $return_pdf = false)
	{
		if ($order = $this->get_order($order_id)) {
			$this->load->library('currency');
			
			$order['products'] = array();
			$order['totals'] = array();
			
			$order_products = $this->db
			->where('order_id', $order_id)
			->where('tax', 0)
			->get('order_product')
			->result_array();
			
			if ( ! $order_products) {
				return array();
			}
			
			$order_totals = $this->db
			->where('order_id', $order_id)
			->where_not_in('code', array('subtotal', 'tax', 'total'))
			->get('order_total')
			->result_array();
			
			$subtotal = 0;
			
			foreach ($order_products as $product) {
				$order['products'][] = array(
					'product_id' => (int)$product['product_id'],
					'name' => $product['name'],
					'sku' => $product['sku'],
					'quantity' => (int)$product['quantity'],
					'price' => (float)$product['price'],
					'total' => (float)$product['total']
				);
				
				$subtotal += (float)$product['total'];
			}
			
			$order['totals'][] = array(
				'code' => 'subtotal',
				'title' => 'Subtotal',
				'text' => $this->currency->format($subtotal),
				'value' => $subtotal,
				'sort_order' => 1
			);
			
			$total = $subtotal;
			$sort_order = 2;
			
			foreach ($order_totals as $order_total) {
				$order['totals'][] = array(
					'code' => $order_total['code'],
					'title' => $order_total['title'],
					'text' => $order_total['text'],
					'value' => (float)$order_total['value'],
					'sort_order' => (int)$sort_order
				);
				
				$total += $order_total['value'];
				$sort_order++;
			}
			
			$order['totals'][] = array(
				'code' => 'total',
				'title' => 'Total',
				'text' => $this->currency->format($total),
				'value' => $total,
				'sort_order' => $sort_order + 1
			);
			
			if ($return_pdf) {
				$this->load->library('image');
				$this->load->library('pdf');
				$this->load->helper('format');
				
				$order['logo'] = str_replace(base_url(), FCPATH, $this->image->resize($this->config->item('logo'), 120));
				
				$filename = APPPATH.'cache/docs/invoice-tanpa-pajak-'.$order['order_id'].'-'.date('YmdHi').'.pdf';
				
				$this->pdf->pdf_create($this->load->layout(false)->view('invoices/order_invoice_no_tax_pdf', $order, true), $filename, false);
				
				return file_exists($filename) ? $filename : false;
			} else {
				return $order;	
			}
		}
	}
	
	/**
	 * Get order combined
	 * 
	 * @access public
	 * @param int $order_id
	 * @param bool $return_pdf
	 * @return mixed
	 */
	public function get_order_combined($order_id, $return_pdf = false)
	{
		if ($order = $this->get_order($order_id)) {
			$this->load->library('currency');
			
			$order['products'] = $this->get_order_products($order_id);
			$order['totals'] = $this->get_order_totals($order_id);
			
			if ( ! $order['products']) {
				return array();
			}
			
			if ($return_pdf) {
				$this->load->library('image');
				$this->load->library('pdf');
				$this->load->helper('format');
				
				$order['logo'] = str_replace(base_url(), FCPATH, $this->image->resize($this->config->item('logo'), 120));
				
				$filename = APPPATH.'cache/docs/invoice-total-'.$order['order_id'].'-'.date('YmdHi').'.pdf';
				
				$this->pdf->pdf_create($this->load->layout(false)->view('invoices/order_invoice_no_tax_pdf', $order, true), $filename, false);
				
				return file_exists($filename) ? $filename : false;
			} else {
				return $order;	
			}
		}
	}

	/**
	 * Get order product detail
	 * 
	 * @access public
	 * @param int $order_id
	 * @return array
	 */
	public function get_order_products_detail($order_id = null)
	{
		return $this->db
			->select('oa.*, p.name', false)
			->join('product p', 'p.product_id = oa.product_id', 'left')
			->where('oa.order_id', $order_id)
			->from('order_product_detail oa')
			->order_by('oa.id')
			->get()
			->result_array();
	}

	/**
	 * update order_product_detail
	 * 
	 * @access public
	 * @param int $order_id
	 * @param string $product_code
	 * @return array
	 */
	public function update_order_product_detail($order_id, $product_code)
	{
		$this->db->where('id', (int)$order_id);
		$this->db->set('product_code', $product_code);
		$this->db->update('order_product_detail');
	}

	/**
	 * check product code
	 * 
	 * @access public
	 * @param int $id
	 * @param string $product_code
	 * @return int
	 */
	public function check_product_order_product_detail($product_code, $order_id, $isNotCheckUserId)
	{
		$this->db
		->select('count(a.id) as qty')
		->from('order_product_detail a')
		->join('order b', 'a.order_id = b.order_id')
		->where('product_code', $product_code);
		
		if($isNotCheckUserId) {
			$this->db->where('b.user_id != 0');
		}

		if($order_id != '') {
			$this->db->where('a.order_id != ', $order_id);
		}
		
		$result = $this->db->get()->row_array();
		
		return ($result) ? $result['qty'] : 0;
	}

	/**
	 * Get order product detail by id
	 * 
	 * @access public
	 * @param int $id
	 * @return array
	 */
	public function get_order_products_detail_by_id($id = null)
	{
		return $this->db
			->select('oa.*, p.name', false)
			->join('product p', 'p.product_id = oa.product_id', 'left')
			->where('oa.id', $id)
			->from('order_product_detail oa')
			->get()
			->row_array();
	}

	/**
	 * Get order product rating by id
	 * 
	 * @access public
	 * @param int $id
	 * @return array
	 */
	public function get_order_products_rating_by_id($id = null)
	{
		return $this->db
			->select('oa.*, p.name', false)
			->join('product p', 'p.product_id = oa.product_id', 'left')
			->where('oa.id', $id)
			->from('order_product_rating oa')
			->get()
			->row_array();
	}

	/**
	 * update rating
	 * 
	 * @access public
	 * @param int $order_id
	 * @param string $product_code
	 * @return array
	 */
	public function update_rating($id, $rating, $notes, $photo)
	{
		$this->db->where('id', (int)$id);
		$this->db->set('value', $rating);
		$this->db->set('notes', $notes);
		$this->db->set('photo', $photo);
		$this->db->set('rating_date', date('Y-m-d H:i:s'));
		$this->db->update('order_product_rating');
	}

	/**
	 * Get sum sale of product
	 * 
	 * @access public
	 * @param int $product_id
	 * @return array
	 */
	public function get_sum_sale($product_id = null)
	{
		$qty = $this->db
		->select('sum(quantity) as qty')
		->from('order_product it')
		->where('it.product_id', (int)$product_id)
		->get()
		->row_array();
		
		return $qty['qty'];
	}

	/**
	 * Get rating value of product
	 * 
	 * @access public
	 * @param int $product_id
	 * @return array
	 */
	public function get_rating_values($product_id = null)
	{
		$value = $this->db
		->select('count(0) as count, sum(value) as value')
		->from('order_product_rating it')
		->where('it.product_id', (int)$product_id)
		->where('it.rating_date != ', '')
		->get()
		->row_array();
		
		return $value;
	}

	/**
	 * Get comments of product
	 * 
	 * @access public
	 * @param int $product_id
	 * @return array
	 */
	public function get_comments($product_id = null)
	{
		$values = $this->db
		->select('it.*, p.user_name, u.image')
		->from('order_product_rating it')
		->join('order p', 'p.order_id = it.order_id', 'left')
		->join('user u', 'u.user_id = p.user_id', 'left')
		->where('it.product_id', (int)$product_id)
		->where('it.rating_date != ', '')
		->order_by('it.rating_date', 'desc')
		->get()
		->result_array();
		
		return $values;
	}

	/**
	 * Get quantity product all
	 * 
	 * @access public
	 * @param int $product_id
	 * @return array
	 */
	public function get_qty_product_order_all($product_id = null)
	{
		$qty = $this->db
		->select('count(it.product_id) as qty')
		->from('order_product it')
		->where('it.product_id', (int)$product_id)
		->get()
		->row_array();
		
		return $qty['qty'];
	}

	/**
	 * Get quantity product all array
	 * 
	 * @access public
	 * @param int $product_id
	 * @return array
	 */
	public function get_qty_product_order_all_array($product_id = null)
	{
		$qty = $this->db
		->select('count(it.product_id) as qty')
		->from('order_product it')
		->where_in('it.product_id', $product_id)
		->get()
		->row_array();
		
		return $qty['qty'];
	}

	/**
	 * update response
	 * 
	 * @access public
	 * @param int $id
	 * @param int $response
	 * @return array
	 */
	public function update_response($id, $response)
	{
		$this->db->where('id', (int)$id);
		$this->db->set('response', $response);
		$this->db->set('response_date', date('Y-m-d H:i:s'));
		$this->db->update('order_product_rating');
	}

	public function update_plan_id($order_id, $plan_id){
		$this->db
		->where('order_id', (int)$order_id)
		->set('payment_plan', $plan_id)
		->update('order');
	}
}