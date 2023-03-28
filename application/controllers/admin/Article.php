<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Article extends Admin_Controller
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
		
		$this->load->library('form_validation');
		
		$this->load->model('warehouse_model');
		$this->load->model('article_model');
		$this->load->model('article_history_model');
		$this->load->model('inventory_model');

		$this->load->helper('format');
		
		$this->lang->load('article');
	}
	
	/**
	 * Index
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		
		if ($this->input->post(null, true)) {
			$this->load->library('datatables');
			
			

			$this->datatables
			->select('product_code, wf.name as product_name, it.id as article_id, wr.name as warehouse_name, invoice_no')
			->join('product wf', 'wf.product_id = it.product_id', 'left')
			->join('warehouse wr', 'wr.warehouse_id = it.warehouse_id', 'left')
			->join('order or', 'or.order_id = it.order_id', 'left')
			->from('article it');
			
			if ($this->input->post('product_id')) {
				$this->datatables->where('wf.product_id', (int)$this->input->post('product_id'));
			}
			if ($this->input->post('warehouse_id')) {
				if ($this->input->post('warehouse_id') == "xxx") $this->datatables->where('it.warehouse_id', 0);
				else $this->datatables->where('it.warehouse_id', (int)$this->input->post('warehouse_id'));
			}
			if($this->admin->warehouse_id() != 0) {
				$this->datatables->where('it.warehouse_id', $this->admin->warehouse_id());
			}
			
			$this->output->set_output($this->datatables->generate('json'));
		} else {
			// if ( ! $this->admin->has_permission()) {
			// 	show_401($this->uri->uri_string());
			// }
			$data['warehouse_distributor'] = '';

			if($this->admin->warehouse_id() != 0) {
				$data['warehouse_distributor'] = $this->admin->warehouse_id();
			}

			$data['products'] = $this->article_model->get_products();

			$data['warehouses'] = $this->warehouse_model->get_all_warehouse();
			
			$this->load
			->title(lang('heading_title'))
			->view('admin/article', $data);
		}
	}
	
	/**
	 * Detail
	 * 
	 * @access public
	 * @return void
	 */
	public function detail()
	{
		if ( ! $this->admin->has_permission('index')) {
			return $this->output->set_output(json_encode(array('error' => lang('admin_error_index'))));
		}
		
		if ($article = $this->article_model->get_article_detail($this->input->get('article_id'))) {
			$data = array();
			
			foreach ($article as $key => $value) {
				$data[$key] = $value;
			}
			
			
			$this->load->title(lang('button_history').' #'.$data['product_code']);
			
			$this->output->set_output(json_encode(array(
				'content' => $this->load->layout(null)->view('admin/article_detail', $data, true, true)
			)));
		}
	}
	
	/**
	 * Select Article
	 * 
	 * @access public
	 * @return void
	 */
	public function select_article()
	{
		$json = array();

		$warehouseFrom = (int)$this->input->post('warehouse_from');

		if ($this->input->post('inventories')) {
			$inventories = $this->input->post('inventories');
		} else {
			$inventories = array();
		}
		
		$json['warning'] = '';

		if (count($inventories) < 1) {
			$json['warning'] = lang('error_item_required');
		}
		
		if ((int)$this->input->post('warehouse_from') == (int)$this->input->post('warehouse_to')) {
			$json['warning'] = lang('error_same_origin');
		}
		
		foreach ($inventories as $inventory) {
			$warehouse = $this->warehouse_model->get((int)$this->input->post('warehouse_from'));
			if ($warehouse && ($this->inventory_model->check_stock((int)$inventory['product_id'], (int)$this->input->post('warehouse_from')) < (int)$inventory['quantity'])) {
				$json['warning'] = sprintf(lang('error_not_enough_stock'), $warehouse['name']);
				break;
			}
		}

		$data = array();
		$selectArticles = array();
		
		if ($json['warning'] == '') {
			if($warehouseFrom != 0) {
				foreach ($inventories as $inventory) {
					$articles = $this->article_model->get_article_select($inventory['product_id'],$warehouseFrom);
					
					$selectArticles[] = array(
						'articles' => $articles,
						'product_name' => $inventory['product'],
						'product_id' => $inventory['product_id'],
						'qty' => (int)$inventory['quantity']
					);
				}
			}
		}

		$data['selectArticles'] = $selectArticles;
		
		$this->output->set_output(json_encode(array(
			'warning' => $json['warning'],
			'content' => $this->load->layout(null)->view('admin/article_select_article', $data, true, true)
		)));
	}

	/**
	 * Auto complete
	 * 
	 * @access public
	 * @return json
	 */
	public function auto_complete()
	{
		$json = array();
		
		if ($this->input->get('filter_name')) {	
			$params = array(
				'filter_name' => $this->input->get('filter_name'),
				'start' => 0,
				'limit' => 20
			);
			
			foreach ($this->article_model->get_articles_autocomplete($params) as $product) {
				if(($this->admin->warehouse_id() != 0 && $this->admin->warehouse_id() == $product['warehouse_id']) || $this->admin->warehouse_id() == 0) {
					$json[] = array(
						'article_id' => $product['id'], 
						'product_code' => $product['product_code'],
					);	
				}
			}
		}
		
		$sort_order = array();
	
		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['product_code'];
		}
		
		array_multisort($sort_order, SORT_ASC, $json);
	
		$this->output->set_output(json_encode($json));
	}

	/**
	 * Print
	 *
	 * @access public
	 * @return void
	 */
	public function excel()
	{
		if ($this->input->post(null, true)) {

			$this->load->helper('format');

			$article_id = $this->input->post('article_id');
			
			$this->load->library('PHPExcel');

			require_once(APPPATH.'libraries/PHPExcel.php');
			require_once(APPPATH.'libraries/PHPExcel/IOFactory.php');

			$objPHPExcel = new PHPExcel();
			$objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
			$objPHPExcel->setActiveSheetIndex(0);
			
			if (is_array($article_id)) {
				$articles = $this->article_model->get_article_print_all_array($article_id);
			} else {
				$articles = $this->article_model->get_article_print_all($article_id);
			}

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'KODE PRODUK');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, 1, 'NAMA PRODUK');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'GUDANG / DISTRIBUTOR');
			if($this->admin->warehouse_id() == 0) {
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, 1, 'INVOICE PESANAN');
			}

			if($this->admin->warehouse_id() != 0) {
				$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);
			}
			else {
				$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);
			}
			
			$row = 2;
			
			foreach($articles as $article) {
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $article['product_code']);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $article['product_name']);
				$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $article['warehouse_name']);
				if($this->admin->warehouse_id() == 0) {
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $article['invoice_no']);
				}
				
				$row++;
			}
			
			$filename = 'daftar-stok-'.date('dmYHi').'.xlsx';
			
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
}