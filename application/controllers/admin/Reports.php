<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends Admin_Controller
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
		
		$this->load->helper('form');
		$this->load->model('warehouse_model');
	}
	
	/**
	 * Index
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		$data['warehouses'] = $this->warehouse_model->get_all_warehouse();
		
		$this->load
		->css('https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css')
		->js('https://cdn.jsdelivr.net/momentjs/latest/moment.min.js')
		->js('https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js')
		->view('admin/report', $data);
	}
	
	/**
	 * Sales
	 *
	 * @access public
	 * @return void
	 */
	public function sales()
	{
		if ($this->input->post(null, true)) {
			if ( ! $this->admin->has_permission('index')) {
				show_401($this->uri->uri_string());
			}

			$this->load->helper('format');

			$date = explode('-', $this->input->post('dates'));
			$date_start = date('Y-m-d 00:00:00', strtotime(str_replace('/', '-', $date[0])));
			$date_end = date('Y-m-d 23:59:59', strtotime(str_replace('/', '-', $date[1])));
			
			$this->load->library('PHPExcel');

			require_once(APPPATH.'libraries/PHPExcel.php');
			require_once(APPPATH.'libraries/PHPExcel/IOFactory.php');

			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
			$objPHPExcel->setActiveSheetIndex(0);
			
			if ($this->input->post('view') == 'global') {
				$results = $this->db
				->select('o.invoice_no, o.date_added, o.user_name, o.total, os.name as status', false)
				->from('order o')
				->join('order_status os', 'os.order_status_id = o.order_status_id', 'left')
				->where('o.date_added BETWEEN "'.$date_start.'" AND "'.$date_end.'"', NULL, FALSE)
				->order_by('o.date_added', 'asc')
				->get()
				->result_array();
	
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'NO. INVOICE');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'TANGGAL');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'CUSTOMER');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'TOTAL');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'STATUS');
	
				$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
				
				$row = 2;
				
				foreach($results as $result) {
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['invoice_no']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, date('d-m-Y', strtotime($result['date_added'])));
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['user_name']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, (float)$result['total']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $result['status']);
					
					$row++;
				}
				
				$filename = 'laporan-penjualan-global-'.date('dmYHi').'.xlsx';
			} else {
				$results = $this->db
				->select('o.invoice_no, o.date_added, o.user_name, os.name as status, op.name, op.quantity, op.price, op.total', false)
				->from('order_product op')
				->join('order o', 'o.order_id = op.order_id', 'left')
				->join('order_status os', 'os.order_status_id = o.order_status_id', 'left')
				->where('o.date_added BETWEEN "'.$date_start.'" AND "'.$date_end.'"', NULL, FALSE)
				->order_by('o.date_added', 'asc')
				->get()
				->result_array();
	
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'NO. INVOICE');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'TANGGAL');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'CUSTOMER');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'ITEM');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, 1, 'HARGA');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, 1, 'QUANTITY');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, 1, 'TOTAL');
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, 1, 'STATUS');
	
				$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
				
				$row = 2;
				
				foreach($results as $result) {
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $result['invoice_no']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, date('d-m-Y', strtotime($result['date_added'])));
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $result['user_name']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $result['name']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, (float)$result['price']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, (int)$result['quantity']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, (float)$result['total']);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $result['status']);
					
					$row++;
				}
				
				$filename = 'laporan-penjualan-detail-'.date('dmYHi').'.xlsx';
			}

			$objPHPExcel->setActiveSheetIndex(0);

			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$filename.'"');
			header('Cache-Control: max-age=0');

			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

			$objWriter->save('php://output');
		} else {
			show_404();
		}
	}
	
	public function chart_sales()
	{
		$json = array();
		
		$date = explode('-', $this->input->get('dates'));
		$date_start = date('Y-m-d', strtotime(str_replace('/', '-', $date[0])));
		$date_end = date('Y-m-d', strtotime(str_replace('/', '-', $date[1]) . '+1 days'));
		
		$period = new DatePeriod(
			new DateTime($date_start),
			new DateInterval('P1D'),
			new DateTime($date_end)
		);
		
		$params['dates'] = array();
		
		foreach ($period as $key => $value) {
			$params['dates'][] = $value->format('Y-m-d');
		}
		
		$results = $this->db
		->select('SUM(total) as total, DATE_FORMAT(date_added, "%Y-%m-%d") as date_added', false)
		->where_in('DATE_FORMAT(date_added, "%Y-%m-%d")', $params['dates'])
		->where('order_status_id', (int)$this->config->item('order_paid_status_id'))
		->order_by('date_added', 'asc')
		->group_by('DATE_FORMAT(date_added, "%Y-%m-%d")')
		->get('order')
		->result_array();
		
		$sales = array();
		
		foreach ($params['dates'] as $date) {
			foreach ($results as $result) {
				if ($result['date_added'] == $date) {
					$sales[$date] = $result['total'];
				} else {
					$sales[$date] = 0;
				}
			}
		}
		
		$json['labels'] = array_keys($sales);
		$json['datasets'] = array(
			array(
				'label' => 'Sales(Rp)',
				'fillColor' => 'rgba(255, 190, 10, 1)',
				'strokeColor' => 'rgba(189, 138, 0, 1)',
				'pointColor' => 'rgba(189, 138, 0, 1)',
				'pointStrokeColor' => '#fff',
				'pointHighlightFill' => '#fff',
				'pointHighlightStroke' => 'rgba(189, 138, 0, 1)',
				'data' => array_values($sales)
			)
		);
		
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
}