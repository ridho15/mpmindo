<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_modules extends Admin_Controller
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
		
		$this->load->model('payment_module_model');
		$this->lang->load('admin_payment_module');
	}
	
	/**
	 * Index payment modules
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		$this->payment_module_model->matches();
		
		$this->load->library('datatables');
		
		if ($this->input->post(null, true)) {
			$this->datatables
			->select('code, name, description, version, active')
			->from('payment_module');
					
			$this->output->set_output($this->datatables->generate('json'));
		} else {
			if ( ! $this->admin->has_permission()) {
				show_401($this->uri->uri_string());
			}
			
			$this->load->view('admin/payment_module');
		}
	}
	
	/**
	 * Install payment module
	 * 
	 * @access public
	 * @return json
	 */
	public function install()
	{
		check_ajax();
		
		$json = array();
		
		$code = $this->input->post('code');
		
		if ( ! $this->admin->has_permission('create')) {
			$json['error'] = lang('admin_error_delete');
		}
		
		if (empty($json['error'])) {
			$this->load->model('payment_module_model');
			
			$payment = $this->payment_module_model->get($code);
		
			if ($payment) {
				$file = APPPATH.'admin/'.$code.'.php';
				
				if (file_exists($file)) {
					require_once($file);
					$class = ucfirst($code);
					$class = new $class;
					
					if (method_exists($class, 'install')) {
						$class->install();
					}
				}
				
				$this->payment_module_model->update($code, array('active' => 1));
			
				$json['success'] = 'Berhasil menginstall sistem pembayaran '. $payment['name'];
			}
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	/**
	 * Uninstall payment module
	 * 
	 * @access public
	 * @return json
	 */
	public function uninstall()
	{
		check_ajax();
		
		$json = array();
		
		$code = $this->input->post('code');
		
		if ( ! $this->admin->has_permission('delete')) {
			$json['error'] = lang('admin_error_delete');
		}
		
		if (empty($json['error'])) {
			$this->load->model('payment_module_model');
			$this->load->model('setting_model');
			
			$payment = $this->payment_module_model->get($code);
			
			if ($payment) {
				$file = APPPATH.'admin/'.$code.'.php';
				
				if (file_exists($file)) {
					require_once($file);
					$class = ucfirst($code);
					$class = new $class;
					
					if (method_exists($class, 'uninstall')) {
						$class->uninstall();
					}
				}
				
				$this->payment_module_model->update($code, array('active' => 0));
				$this->setting_model->delete_setting('payment_'.$code);
			
				$json['success'] = 'Berhasil menghapus sistem pembayaran '. $payment['name'];
			}
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	/**
	 * Default index
	 * 
	 * @access public
	 * @param bool $slug (default: false)
	 * @param string $method (default: 'index')
	 * @return void
	 */
	public function setting($slug = false)
	{
		if ($this->payment_module_model->is_installed($slug)) {
			$module = $this->payment_module_model->get($slug);
			$payment_module = $this->load->payment($slug, 'admin_'.$slug);
			$output = call_user_func([$payment_module, 'setting']);
			
			if ($this->input->post(null, true)) {
				$this->output->set_header('Content-Type: application/json');
				$this->output->set_output(json_encode($output));
			} else {
				$data['heading_title'] = $module['name'];
				$data['content'] = $output;
				
				$this->load
				->layout('admin')
				->title($module['name'])
				->view('admin/payment_module_form', $data);
			}
		} else {
			redirect(admin_url('payment_modules'));
		}
	}
	
	/**
	 * Upload
	 * 
	 * @access public
	 * @return void
	 */
	public function upload()
	{
		$json = array();
		
		$upload_path = DIR_IMAGE.'payments';
		
		if ( ! file_exists($upload_path)) {
			@mkdir($upload_path, 0777);
		}
		
		$this->load->library('upload', array(
			'upload_path' => $upload_path,
			'allowed_types' => 'gif|jpg|png|jpeg|heif|tiff',
			'max_size' => '5120',
			'overwrite' => true
		));

		if ( ! $this->upload->do_upload()) {
			$json['error'] = $this->upload->display_errors('', '');
		} else {
			$upload_data = $this->upload->data();
			$image = substr($upload_data['full_path'], strlen(FCPATH.DIR_IMAGE));

			// check EXIF and autorotate if needed
			//$this->load->library('image_autorotate', array('filepath' => $image));
			
			$thumb = $this->image->resize($image, 200, 200, true);
			
			$json['thumb'] = $thumb;
			$json['image'] = $image;
			$json['success'] = lang('success_uploaded');
		}
		
		$this->output->set_output(json_encode($json));
	}
}