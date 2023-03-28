<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory extends Admin_Controller
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
		
		$this->lang->load('admin_inventory');
		$this->load->model('warehouse_model');
	}
	
	/**
	 * History
	 * 
	 * @access public
	 * @return void
	 */
	public function history()
	{
		if ($this->input->post(null, true)) {
			$this->load->library('datatables');
			$this->load->helper('format');
	
			$this->datatables
			->select('i.inventory_id, @cti := i.inventory_id, (CASE WHEN i.trans_table = "order" THEN "Penjualan" WHEN i.trans_table = "inventory_trans" THEN "Mutasi Stock" ELSE "Tidak Diketahui" END) AS description, i.date_added, (CASE WHEN i.quantity > 0 THEN i.quantity ELSE NULL END) AS item_in, (CASE WHEN i.quantity < 0 THEN i.quantity * (-1) ELSE NULL END) AS item_out, (SELECT SUM(quantity) FROM inventory ct WHERE ct.inventory_id < @cti AND ct.product_id = '.(int)$this->input->post('product_id').' AND ct.warehouse_id = '.(int)$this->input->post('warehouse_id').') + quantity as balance, o.order_id, o.invoice_no, it.inventory_trans_id', false)
			->from('inventory i')
			->join('order o', 'o.order_id = i.trans_table_id AND i.trans_table = "order"', 'left')
			->join('inventory_trans it', 'it.inventory_trans_id = i.trans_table_id AND i.trans_table = "inventory_trans"', 'left')
			->where('i.product_id', (int)$this->input->post('product_id'))
			->where('i.warehouse_id', (int)$this->input->post('warehouse_id'))
			->edit_column('date_added', '$1', 'format_date(date_added)');
	
			$this->output->set_output($this->datatables->generate('json'));
		} else {
			$product_id = $this->input->get('product_id');
			$warehouse_id = $this->input->get('warehouse_id');
			
			$this->load->model('product_model');
			
			if ($product = $this->product_model->get_product($product_id)) {
				$data['product_id'] = $product['product_id'];
				$data['product'] = $product['name'];
				$data['sku'] = '';
			} else {
				$data['product_id'] = null;
				$data['product'] = '';
				$data['sku'] = '';
			}
			
			$data['warehouses'] = $this->warehouse_model->get_all_warehouse();
			
			$this->output->set_output(json_encode(array(
				'content' => $this->load->layout(null)->view('admin/inventory_history', $data, true)
			)));
		}
		
		// if ($this->input->post(null, true)) {
		// 	$this->load->library('datatables');
		// 	$this->load->helper('format');
			
		// 	$this->datatables->set_database('inventory');
			
		// 	$this->datatables
		// 	->select('i.id, @cti := i.id, i.description, i.date, i.debit, i.credit, (SELECT SUM(debit-credit) FROM knr_stockflows ct WHERE ct.id < @cti AND ct.product_id = "'.(int)$this->input->post('product_id').'" AND ct.location = "'.$this->input->post('location').'" AND ct.location_id = "'.(int)$this->input->post('location_id').'") + (debit-credit) as balance, i.process_no', false)
		// 	->from('stockflows i')
		// 	->where('i.product_id', (int)$this->input->post('product_id'))
		// 	->where('i.location', $this->input->post('location'))
		// 	->where('i.location_id', (int)$this->input->post('location_id'))
		// 	->edit_column('date', '$1', 'format_date(date)');
	
		// 	$this->output->set_output($this->datatables->generate('json'));
		// } else {
		// 	$product_id = $this->input->get('product_id');
		// 	$warehouse_id = $this->input->get('warehouse_id');
			
		// 	$this->load->model('product_model');
			
		// 	if ($product = $this->product_model->get_product($product_id)) {
		// 		$data['product_id'] = (int)$product['inventory_product_id'];
		// 		$data['product'] = $product['name'];
		// 		$data['sku'] = $product['sku'];
		// 	} else {
		// 		$data['product_id'] = null;
		// 		$data['product'] = '';
		// 		$data['sku'] = '';
		// 	}
			
		// 	 $this->load->model('api/places_model', 'api_places_model');
			
		// 	 $data['places'] = $this->api_places_model->get_places();

		// 	$data['warehouses'] = $this->warehouse_model->get_all();
			
		// 	$this->output->set_output(json_encode(array(
		// 		'content' => $this->load->layout(null)->view('admin/inventory_history', $data, true)
		// 	)));
		// }
	}
	
	/**
	 * Jika user mempunyai akses login ke sistem inventory
	 * dengan catatan mempunyai username dan password yang
	 * sama dengan sistem inventory
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		if ( ! $this->admin->has_permission()) {
			show_401($this->uri->uri_string());
		} else {
			if ($this->admin->inventory_is_logged()) {
				redirect(INVENTORY_URL);
			} else {
				redirect(PATH_ADMIN.'/dashboard');
			}
		}
	}
}