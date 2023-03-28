<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends User_Controller
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
		$this->load->helper('form');
		$this->load->library('form_validation');
	}
	
	public function index()
	{
		if ($this->user->store_id()) {
			if ($this->input->post(null, true)) {
				$this->load->library('datatables');
				$this->load->helper('format');
			
				$this->datatables
				->select('so.store_order_id, so.order_id, o.user_name, CONCAT_WS(" - ", o.user_city, o.user_province) as location, so.total, os.name as status, o.date_added', false)
				->from('store_order so')
				->join('order o', 'o.order_id = so.order_id', 'left')
				->join('order_status os', 'os.order_status_id = so.order_status_id', 'left')
				->where('so.store_id', $this->user->store_id())
				->where('o.order_status_id', $this->config->item('order_paid_status_id'))
				->edit_column('total', '$1', 'format_money(total)')
				->edit_column('date_added', '$1', 'format_date(date_added)');
				
				$this->output->set_output($this->datatables->generate('json'));
			} else {
				$this->load->view('user/order');
			}
		} else {
			show_401();
		}
	}
	
	public function detail($order_id = null)
	{
		$this->load->helper('format');
		$this->load->library('currency');
		
		if ($order = $this->order_model->get_store_order($order_id, $this->user->store_id())) {
			foreach ($order as $key => $value) {
				$data[$key] = $value;
			}
			
			$this->load->model('order_status_model');
			
			$data['order_statuses'] = $this->order_status_model->get_all_by(array('group' => 'seller'));
			$data['date_added'] = date('d/m/Y', strtotime($order['date_added']));
			$data['products'] = array();
			
			$key = 1;
			
			foreach ($this->order_model->get_store_order_products($order_id, $this->user->store_id()) as $product)
			{
				$product['price'] = format_money($product['price']);
				$product['total'] = format_money($product['total']);
				
				$data['products'][$key] = $product;
				
				$key++;
			}
			
			$data['total'] = format_money($data['totals']);
			$data['shipping_cost'] = format_money($data['shipping_cost']);
			$data['subtotal'] = format_money($data['subtotal']);
			
			$shipping_address = array(
				'name'		=> $data['user_name'],
				'address'	=> $data['user_address'],
				'telephone'	=> $data['user_telephone'],
				'postcode'	=> $data['user_postcode'],
				'subdistrict' => $data['user_subdistrict'],
				'city'		=> $data['user_city'],
				'province'	=> $data['user_province']
			);
			
			$data['shipping_address'] = format_address($shipping_address);
			$data['action'] = user_url('order/add_history');
			
			// jika order sdh close atau cancel, maka tidak dapat diupdate lagi
			if ($this->config->item('order_complete_status_id') == $data['order_status_id']) {
				$data['update'] = false;
			} elseif ($this->config->item('order_cancel_status_id') == $data['order_status_id']) {
				$data['update'] = false;
			} else {
				$data['update'] = true;
			}
			
			$this->load
			->title('Detil Pesanan #'.$data['store_order_id'])
			->view('user/order_detail', $data);
		} else {
			show_404();
		}
	}
	
	/**
	 * Fetch order hitory
	 * 
	 * @access public
	 * @return json
	 */
	public function history()
	{
		if ($this->input->post(null, true)) {
			$this->load->helper('format');
			$this->load->library('datatables');
		
			$this->datatables
			->select('oh.*, oh.date_added as date_added, os.name as status', false)
			->join('order_status os', 'os.order_status_id = oh.order_status_id', 'left')
			->where('(oh.store_order_id = '.$this->input->post('store_order_id').' AND oh.order_id = '.$this->input->post('order_id').')', null, false)
			->or_where('(oh.order_id = '.$this->input->post('order_id').' AND oh.store_order_id = 0)', null, false)
			->from('order_history oh')
			->edit_column('date_added', '$1', 'format_date(date_added, true)')
			->edit_column('total', '$1', 'format_money(total)');
			
			$this->output->set_output($this->datatables->generate('json'));
		} else {
			show_404();
		}
	}
	
	public function add_history()
	{
		$json = array();
		
		$keys = array(
			'order_id',
			'order_status_id',
			'store_order_id',
			'notify',
			'override',
			'comment'
		);

		foreach ($keys as $key) {
			if ($this->input->post($key)) {
				$$key = $this->input->post($key);
			} else {
				$$key = '';
			}
		}

		$this->load->model('order_model');
		$this->load->model('store_order_model');
		
		if ($order = $this->order_model->get($order_id)) {
			$this->load->model('order_history_model');
			
			$order_history = array(
				'order_id' => (int)$order_id,
				'order_status_id' => (int)$order_status_id,
				'store_order_id' => (int)$store_order_id,
				'notify' => (int)$notify,
				'comment' => htmlentities($comment, ENT_QUOTES, 'UTF-8')
			);
			
			$store_order['order_status_id'] = (int)$order_status_id;
			
			if ($this->input->post('waybill') != '' && strlen($this->input->post('waybill')) > 3) {
				$store_order['waybill'] = htmlentities($this->input->post('waybill'), ENT_QUOTES, 'UTF-8');
			}
			
			$this->store_order_model->update($store_order_id, $store_order);
			$this->order_history_model->insert($order_history);
			
			if ($notify) {
				$this->load->library('sms');
				$this->load->model('order_status_model');
				
				if ($status = $this->order_status_model->get($order_status_id)) {
					$this->sms->send($order['user_telephone'], 'Borongin - Pesanan #'.$store_order_id.' telah diperbarui dengan status '.strtoupper($status['name']));
				}
			}
			
			$json['success'] = 'Status pesanan telah berhasil diperbarui';
		} else {
			$json['error'] = 'Pesanan tidak ditemukan';
		}

		$this->output->set_header('Content-Type: application/json');
		$this->output->set_output(json_encode($json));
	}
	
	/**
	 * Print or export docs to pdf with spesific type
	 * 
	 * @access public
	 * @param string $type Invoice, delivery note
	 * @param int $order_id
	 * @param bool $stream
	 * @return void
	 */
	public function eprint($type = false, $order_id = null, $stream = true)
	{
		if ( ! in_array($type, array('invoice', 'delivery')))
		{
			show_404();
		}
		
		$this->load->helper('format');
		$this->load->library('currency');
		$this->load->library('pdf');
		
		if ($order = $this->order_model->get_store_order($order_id, $this->user->store_id())) {
			foreach ($order as $key => $value) {
				$data[$key] = $value;
			}
			
			$this->load->model('store_model');
			$store_address = $this->store_model->get_store_address($order['store_id']);
			
			if ($store_address) {
				$data['store_name'] = $store_address['name'];
				$data['store_address'] = $store_address['address'];
				$data['store_subdistrict'] = $store_address['subdistrict'];
				$data['store_city'] = $store_address['city'];
				$data['store_province'] = $store_address['province'];
			} else {
				$data['store_name'] = '';
				$data['store_address'] = '';
				$data['store_subdistrict'] = '';
				$data['store_city'] = '';
				$data['store_province'] = '';
			}
			
			$this->load->model('order_status_model');
			
			$data['order_statuses'] = $this->order_status_model->get_all_by(array('group' => 'seller'));
			$data['date_added'] = date('d/m/Y', strtotime($order['date_added']));
			$data['products'] = array();
			
			$key = 1;
			
			foreach ($this->order_model->get_store_order_products($order_id, $this->user->store_id()) as $product)
			{
				$product['price'] = format_money($product['price']);
				$product['total'] = format_money($product['total']);
				
				$data['products'][$key] = $product;
				
				$key++;
			}
			
			$data['total'] = $data['totals'];
			$data['shipping_cost'] = format_money($data['shipping_cost']);
			$data['subtotal'] = format_money($data['subtotal']);
			
			$shipping_address = array(
				'name' => $data['user_name'],
				'address' => $data['user_address'],
				'telephone'	=> $data['user_telephone'],
				'postcode' => $data['user_postcode'],
				'subdistrict' => $data['user_subdistrict'],
				'city' => $data['user_city'],
				'province' => $data['user_province']
			);
			
			$data['shipping_address'] = format_address($shipping_address);
		} else {
			show_404();
		}
		
		switch ($type) {
			case 'invoice':
				$filename = APPPATH.'cache/docs/invoice-'.$order_id.'.pdf';
				if ( ! $stream) {
					$this->pdf->pdf_create($this->load->layout(false)->view('user/order_invoice_pdf', $data, true), $filename, false);
					return file_exists($filename) ? $filename : false;
				} else {
					$this->pdf->pdf_create($this->load->layout(false)->view('user/order_invoice_pdf', $data, true), 'invoice-'.$order_id, true);
				}
			break;
			
			case 'delivery':
				$filename = APPPATH.'cache/docs/delivery-'.$order_id.'.pdf';
				if ( ! $stream) {
					$this->pdf->pdf_create($this->load->layout(false)->view('user/order_delivery_pdf', $data, true), $filename, false);
					return file_exists($filename) ? $filename : false;
				} else {
					$this->pdf->pdf_create($this->load->layout(false)->view('user/order_delivery_pdf', $data, true), 'delivery-'.$order_id, true);
				}
			break;
			
			default:
				show_404();
			break;
		}
	}
	
	public function waybill()
	{
		$html = '<div class="modal-dialog">';
		$html .= '<div class="modal-content">';
		$html .= '<div class="modal-header">';
		$html .= '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
		$html .= '<h4 class="modal-title" id="myModalLabel">Status Pengiriman No. '.$this->input->get('waybill').'</h4>';
		$html .= '</div>';
		$html .= '<div class="modal-body">';
		$html .= '<table class="table table-bordered">';
		$html .= '<tbody>';
		
		$manifests = array();

		if ($this->input->get('shipping_code')) {
			$sc = explode('.', $this->input->get('shipping_code'));
			if (isset($sc[1])) {
				$ssc = explode('_', $sc[1]);
				if (isset($ssc[0])) {
					$this->load->library('rajaongkir');
					$result = $this->rajaongkir->waybill($this->input->get('waybill'), $ssc[0]);
					
					if (isset($result['manifest']) && is_array($result['manifest'])) {
						foreach ($result['manifest'] as $manifest) {
							$html .= '<tr>';
							$html .= '<td>'.date('d/m/Y H:i', strtotime($manifest['manifest_date'].' '.$manifest['manifest_time'])).'</td>';
							$html .= '<td>'.$manifest['manifest_description'].'</td>';
							$html .= '</tr>';
						}
					} else {
						$html .= '<tr><td>Tidak ada data!</td></tr>';
					}
				}
			}
		} else {
			$html .= '<tr><td>Tidak ada metode pengiriman!</td></tr>';
		}
		
		$html .= '<tbody>';
		$html .= '</table>';
		$html .= '</div>';
		$html .= '<div class="modal-footer">';
		$html .= '<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '</div>';
		
		$this->output->set_output($html);
	}
}