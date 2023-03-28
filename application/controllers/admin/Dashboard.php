<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller
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
		
		$this->load->helper('format');
	}
	
	/**
	 * Index
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		$data = array();
		
		$this->load->database();
		
		if($this->admin->is_distributor() != 0) {
			$data['count_stock'] = $this->db
			->select('COUNT(0) as total', false)
			->from('article')
			->where('warehouse_id', (int)$this->admin->warehouse_id())
			->get()->row()->total;

			$data['count_mutations'] = $this->db
			->select('COUNT(0) as total', false)
			->from('inventory_trans')
			->where('warehouse_from', (int)$this->admin->warehouse_id())
			->or_where('warehouse_to', (int)$this->admin->warehouse_id())
			->get()->row()->total;

			$data['count_regs'] = $this->db
			->select('COUNT(0) as total', false)
			->from('reg_sale_offline')
			->where('warehouse_id', (int)$this->admin->warehouse_id())
			->get()->row()->total;

			$data['count_claim'] = $this->db
			->select('COUNT(0) as total', false)
			->from('warranty_claim')
			->where('warehouse_destination', (int)$this->admin->warehouse_id())
			->get()->row()->total;

			$data['count_claim_in'] = $this->db
			->select('COUNT(0) as total', false)
			->from('warranty_claim')
			->where('warehouse_destination', (int)$this->admin->warehouse_id())
			->where_not_in('status', array('Klaim Ditolak','Klaim Tidak Disetujui Dan Barang Dikembalikan Ke Customer','Selesai'))
			->get()->row()->total;

			$data['count_claim_out'] = $this->db
			->select('COUNT(0) as total', false)
			->from('warranty_claim')
			->where('warehouse_destination', (int)$this->admin->warehouse_id())
			->where_in('status', array('Klaim Ditolak','Klaim Tidak Disetujui Dan Barang Dikembalikan Ke Customer','Selesai'))
			->get()->row()->total;
		}
		else {
			$data['count_users'] = $this->db->where('active', 1)->count_all_results('user');
		
			$data['count_orders'] = $this->db
			->select('COUNT(DISTINCT(order_id)) as total', false)
			->from('order')
			->where('SUBSTRING_INDEX(date_added,"-",1) = "'.date('Y', time()).'"', null, false)
			->get()->row()->total;
			
			$data['count_pending_orders'] = $this->db
			->select('COUNT(DISTINCT(order_id)) as total', false)
			->from('order')
			->where('SUBSTRING_INDEX(date_added,"-",1) = "'.date('Y', time()).'"', null, false)
			->where('order_status_id', (int)$this->config->item('order_status_id'))
			->get()->row()->total;
			
			$data['count_paid_orders'] = $this->db
			->select('COUNT(DISTINCT(order_id)) as total', false)
			->from('order')
			->where('SUBSTRING_INDEX(date_added,"-",1) = "'.date('Y', time()).'"', null, false)
			->where('order_status_id', (int)$this->config->item('order_paid_status_id'))
			->get()->row()->total;
			
			$this->load->model('chat_session_model');
			
			$data['chat_time'] = gmdate('H:i:s', (int)$this->chat_session_model->count_average_duration());
			
			$this->load->model('order_model');
			
			$conversion_rates = $this->order_model->get_conversion_rate();
			$total_cr = 0;
			$product_sold = 0;
			
			foreach ($conversion_rates as $conversion_rate) {
				$product_sold += $conversion_rate['sold'];
				$total_cr += ((int)$conversion_rate['sold'] / (int)$conversion_rate['viewed']) * 100;
			}
			
			if ($total_cr > 0) {
				$data['conversion_rate'] = format_money($total_cr/count($conversion_rates), 2);
			} else {
				$data['conversion_rate'] = 0;
			}
			
			$data['product_sold'] = format_money($product_sold, 0);
			
			$this->load->model('product_view_model');
			$data['product_viewed'] = format_money($this->product_view_model->count_viewed(), 0);
		}

		$this->load->view('admin/dashboard', $data);
	}
	
	/**
	 * Chart sales
	 * 
	 * @access public
	 * @return void
	 */
	public function chart_sales()
	{
		$this->load->model('order_model');
		
		$json = array();
		
		if ($this->input->get('range')) {
			$range = $this->input->get('range');
		} else {
			$range = 'week';
		}
		
		$params = $this->set_params($range);
		
		if ( ! $params) {
			return false;
		}
		
		$sale = $this->order_model->get_chart_sales($params);
		
		foreach ($params['labels'] as $key => $label) {
			if (isset($sale[$key])) {
				$sales[$key] = $sale[$key];
			} else {
				$sales[$key] = 0;
			}
		}
		
		$params['order_status_id'] = (int)$this->config->item('order_complete_status_id');
		$sale2 = $this->order_model->get_chart_sales($params);
		
		foreach ($params['labels'] as $key => $label) {
			if (isset($sale2[$key])) {
				$sales2[$key] = $sale2[$key];
			} else {
				$sales2[$key] = 0;
			}
		}
		
		$json['labels'] = array_values($params['labels']);
		$json['datasets'] = array(
			array(
				'label' => 'Potensi Pendapatan',
				'fillColor' => 'rgba(255, 190, 10, 0.2)',
				'strokeColor' => 'rgba(189, 138, 0, 1)',
				'pointColor' => 'rgba(189, 138, 0, 1)',
				'pointStrokeColor' => '#fff',
				'pointHighlightFill' => '#fff',
				'pointHighlightStroke' => 'rgba(189, 138, 0, 1)',
				'data' => array_values($sales)
			),
			array(
				'label' => 'Pendapatan Aktual',
				'fillColor' => 'rgba(26, 255, 0, 0.2)',
				'strokeColor' => 'rgba(14, 138, 0, 2)',
				'pointColor' => 'rgba(14, 138, 0, 2)',
				'pointStrokeColor' => '#fff',
				'pointHighlightFill' => '#fff',
				'pointHighlightStroke' => 'rgba(14, 138, 0, 2)',
				'data' => array_values($sales2)
			)
		);
		
		// potensi pendapatan
		$last_amount = $this->order_model->count_sales(array(
			'start' => $params['last_start'], 
			'end' => $params['last_end']
		));
		
		$this_amount = $this->order_model->count_sales(array(
			'start' => $params['start'], 
			'end' => $params['end']
		));
		
		$json['sales_pending'] = array(
			'amount' => format_money($this_amount, 0),
			'percentage' => ($last_amount > 0) ? ($this_amount - $last_amount) / $last_amount * 100 : ($this_amount > 0 ? 100 : 0)
		);
		
		// pendapatan kotor
		$last_amount = $this->order_model->count_sales(array(
			'start' => $params['last_start'], 
			'end' => $params['last_end'], 
			'order_status_id' => (int)$this->config->item('order_complete_status_id')
		));
		
		$this_amount = $this->order_model->count_sales(array(
			'start' => $params['start'], 
			'end' => $params['end'], 
			'order_status_id' => (int)$this->config->item('order_complete_status_id')
		));

		$json['sales_completed'] = array(
			'amount' => format_money($this_amount, 0),
			'percentage' => ($last_amount > 0) ? ($this_amount - $last_amount) / $last_amount * 100 : ($this_amount > 0 ? 100 : 0)
		);
		
		// pendapatan dengan pajak
		$last_amount = $this->order_model->count_product_sales(array(
			'start' => $params['last_start'], 
			'end' => $params['last_end'], 
			'tax' => true,
			'order_status_id' => (int)$this->config->item('order_complete_status_id')
		));
		
		$this_amount = $this->order_model->count_product_sales(array(
			'start' => $params['start'], 
			'end' => $params['end'], 
			'tax' => true,
			'order_status_id' => (int)$this->config->item('order_complete_status_id')
		));
		
		$json['sales_tax'] = array(
			'amount' => format_money($this_amount, 0),
			'percentage' => ($last_amount > 0) ? ($this_amount - $last_amount) / $last_amount * 100 : ($this_amount > 0 ? 100 : 0)
		);
		
		// pendapatan tanpa pajak
		$last_amount = $this->order_model->count_product_sales(array(
			'start' => $params['last_start'], 
			'end' => $params['last_end'], 
			'tax' => false,
			'order_status_id' => (int)$this->config->item('order_complete_status_id')
		));
		
		$this_amount = $this->order_model->count_product_sales(array(
			'start' => $params['start'], 
			'end' => $params['end'], 
			'tax' => false,
			'order_status_id' => (int)$this->config->item('order_complete_status_id')
		));
		
		$json['sales_no_tax'] = array(
			'amount' => format_money($this_amount, 0),
			'percentage' => ($last_amount > 0) ? ($this_amount - $last_amount) / $last_amount * 100 : ($this_amount > 0 ? 100 : 0)
		);
		
		$this->output->set_output(json_encode($json));
	}
	
	protected function set_params($range)
	{
		switch ($range) {
			case 'week':
				$params['group'] = 'day';
				$params['start'] = date('Y-m-d H:i:s', strtotime('monday this week'));
				$params['end'] = date('Y-m-d H:i:s', strtotime('sunday this week') + 86340);
				$params['last_start'] = date('Y-m-d H:i:s', strtotime('last week monday'));
				$params['last_end'] = date('Y-m-d H:i:s', strtotime('last sunday') + 86340);
				
				$iStart = (int)date('d', strtotime($params['start']));
				$iEnd = (int)date('d', strtotime($params['end']));
				$days = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
				
				$j = 0;
				
				for ($i = $iStart; $i <= $iEnd; $i++) {
					$params['labels'][$i] = (string)$days[$j];
					$j++;
				}
				
				return $params;
			break;
			
			case 'month':
				$params['group'] = 'day';
				$params['start'] = date('Y-m-d H:i:s', strtotime('first day of this month'));
				$params['end'] = date('Y-m-d H:i:s', strtotime('last day of this month'));
				$params['last_start'] = date('Y-m-d H:i:s', strtotime('first day of last month'));
				$params['last_end'] = date('Y-m-d H:i:s', strtotime('last day of last month'));
				
				$iStart = (int)date('d', strtotime($params['start']));
				$iEnd = (int)date('d', strtotime($params['end']));
				
				for ($i = $iStart; $i <= $iEnd; $i++) {
					$params['labels'][$i] = (string)$i;
				}
				
				return $params;
			break;
			
			case 'year':
				$params['group'] = 'month';
				$params['start'] = date('Y-m-d H:i:s', mktime(0, 0, 0, 1, 1, date('Y', time())));
				$params['end'] = date('Y-m-d H:i:s', mktime(23, 59, 0, 12, 31, date('Y', time())));
				$params['last_start'] = date('Y-m-d H:i:s', mktime(0, 0, 0, 1, 1, date('Y') - 1));
				$params['last_end'] = date('Y-m-d H:i:s', mktime(23, 59, 0, 12, 31, date('Y') - 1));
				
				$iStart = (int)date('m', strtotime($params['start']));
				$iEnd = (int)date('m', strtotime($params['end']));
				$months = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Des');
				
				$j = 0;
				
				for ($i = $iStart; $i <= $iEnd; $i++) {
					$params['labels'][$i] = (string)$months[$j];
					$j++;
				}
				
				return $params;
			break;
			
			default:
				return false;
			break;
		}
	}
	
	/**
	 * Get featured products
	 *
	 * Ambil 10 produk terlaris selama periode tertentu
	 * 
	 * @access public
	 * @return json
	 */
	public function chart_featured_products()
	{
		if ($this->input->get('range')) {
			$range = $this->input->get('range');
		} else {
			$range = 'week';
		}
		
		$params = $this->set_params($range);
		
		if ( ! $params) {
			return false;
		}
		
		$results = $this->db
		->select('SUM(op.quantity) as total, op.name, op.product_id', false)
		->from('order_product op')
		->join('order o', 'o.order_id = op.order_id', 'left')
		->where('(o.date_added BETWEEN "'.$params['start'].'" AND "'.$params['end'].'")', null, false)
		->limit(10)
		->order_by('total', 'asc')
		->group_by('op.product_id')
		->get()
		->result_array();
		
		$total = 0;
		
		foreach ($results as $result) {
			$total += (int)$result['total'];
		}
		
		$json = array();
		
		foreach ($results as $result) {
			$color = 'rgba('.implode(',', $this->getColor($result['product_id'])).')';
			
			$json['datasets'][] = array(
				'value' => (int)$result['total'] / $total * 100,
				'color' => $color,
				'highlight' => $color,
				'label' => ucwords(strtolower($result['name']))
			);
		}
					
		$this->output->set_output(json_encode($json));
	}
	
	/**
	 * Chart stocks
	 * 
	 * @access public
	 * @return json
	 */
	public function chart_stocks()
	{
		$json = array();
		
		$results = $this->db
		->select('SUM(i.quantity) as total, p.name, p.product_id, p.minimum_stock', false)
		->from('inventory i')
		->join('product p', 'p.product_id = i.product_id', 'left')
		->where('i.warehouse_id', (int)$this->config->item('warehouse_id'))
		->where('(SELECT SUM(i.quantity)) <= p.minimum_stock', null, false)
		->order_by('total', 'asc')
		->group_by('i.product_id')
		->get()
		->result_array();
		
		$json = array();
		
		$json['labels'] = array('Item');
		
		foreach ($results as $result) {
			$color = 'rgba('.implode(',', $this->getColor($result['product_id'])).')';
			
			$json['datasets'][] = array(
				'label' => $result['name'],
				'fillColor' => $color,
				'strokeColor' => $color,
				'pointColor' => $color,
				'pointStrokeColor' => '#fff',
				'pointHighlightFill' => '#fff',
				'pointHighlightStroke' => $color,
				'data' => array($result['total'])
			);
		}
					
		$this->output->set_output(json_encode($json));
	}
	
	/**
	 * Get color
	 * 
	 * @access private
	 * @param mixed $num
	 * @return void
	 */
	private function getColor($num)
	{
		$hash = md5('color' . $num);
		
		return [
			hexdec(substr($hash, 0, 2)),
			hexdec(substr($hash, 2, 2)),
			hexdec(substr($hash, 4, 2)),
			1
		];
	}
	
	/**
	 * Chart of product views
	 * 
	 * @access public
	 * @return void
	 */
	public function chart_product_views()
	{
		$this->load->model('product_view_model');
		
		$json = array();
		
		if ($this->input->get('range')) {
			$range = $this->input->get('range');
		} else {
			$range = 'date';
		}
		
		$views = $this->product_view_model->get_chart_views(array(
			'group' => $range
		));
		
		if ( ! $views) {
			return false;
		}
		
		$json['labels'] = array('Produk');
		
		$num = 1;
		
		foreach ($views as $product => $total) {
			$color = 'rgba('.implode(',', $this->getColor($num)).')';
			
			$json['datasets'][] = array(
				'label' => ucwords(strtolower($product)),
				'fillColor' => $color,
				'strokeColor' => $color,
				'pointColor' => $color,
				'pointStrokeColor' => '#fff',
				'pointHighlightFill' => '#fff',
				'pointHighlightStroke' => $color,
				'data' => array($total)
			);
			
			$num++;
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	public function chart_customers()
	{
		if ($this->input->get('range')) {
			$range = $this->input->get('range');
		} else {
			$range = 'week';
		}
		
		$params = $this->set_params($range);
		
		if ( ! $params) {
			return false;
		}
		
		$this->load->model('order_model');
		$this->load->model('user_model');
		
		if ($this->input->get('type') == 'type') {
			$member = $this->user_model->count_users($params);
			$guest = $this->order_model->count_buyers($params);
			$total = $member + $guest;
			
			if ($total) {
				$json['datasets'] = array(
					array(
						'value' => (int)$member / (int)$total * 100,
						'color' => '#F7464A',
						'highlight' => '#FF5A5E',
						'label' => 'Pelanggan Terdaftar'
					),
					array(
						'value' => (int)$guest / (int)$total * 100,
						'color' => '#46BFBD',
						'highlight' => '#5AD3D1',
						'label' => 'Pelanggan Tamu'
					)
				);
			} else {
				$json['datasets'] = array();
			}
		} elseif ($this->input->get('type') == 'gender') {
			$params['gender'] = 'm';
			$male = $this->user_model->count_users($params);
			
			$params['gender'] = 'f';
			$female = $this->user_model->count_users($params);
			$total = $male + $female;
			
			if ($total) {
				$json['datasets'] = array(
					array(
						'value' => (int)$male / (int)$total * 100,
						'color' => '#F7464A',
						'highlight' => '#FF5A5E',
						'label' => 'Laki-laki'
					),
					array(
						'value' => (int)$female / (int)$total * 100,
						'color' => '#46BFBD',
						'highlight' => '#5AD3D1',
						'label' => 'Perempuan'
					)
				);
			} else {
				$json['datasets'] = array();
			}
		} else {
			$json['datasets'] = array();
		}
		
		$last_amount = $this->order_model->count_buyers(array(
			'start' => $params['last_start'], 
			'end' => $params['last_end'], 
			'order_status_id' => (int)$this->config->item('order_complete_status_id')
		));
		
		$this_amount = $this->order_model->count_buyers(array(
			'start' => $params['start'], 
			'end' => $params['end'], 
			'order_status_id' => (int)$this->config->item('order_complete_status_id')
		));

		$json['customers_buyer'] = array(
			'amount' => $this_amount,
			'percentage' => ($last_amount > 0) ? ($this_amount - $last_amount) / $last_amount * 100 : ($this_amount > 0 ? 100 : 0)
		);
		
		$this_customer = $this->user_model->count_users(array(
			'start' => $params['start'], 
			'end' => $params['end']
		));
		
		$last_customer = $this->user_model->count_users(array(
			'start' => $params['last_start'], 
			'end' => $params['last_end']
		));
		
		$json['customers_user'] = array(
			'amount' => $this_customer,
			'percentage' => ($last_customer > 0) ? ($this_customer - $last_customer) / $last_customer * 100 : ($this_customer > 0 ? 100 : 0)
		);
		
		$this_guest = $this->order_model->count_buyers(array(
			'start' => $params['start'], 
			'end' => $params['end'],
			'guest' => true
		));
		
		$last_guest = $this->order_model->count_buyers(array(
			'start' => $params['last_start'], 
			'end' => $params['last_end'],
			'guest' => true
		));
		
		$json['customers_guest'] = array(
			'amount' => $this_guest,
			'percentage' => ($last_guest > 0) ? ($this_guest - $last_guest) / $last_guest * 100 : ($this_guest > 0 ? 100 : 0)
		);
					
		$this->output->set_output(json_encode($json));
	}
}