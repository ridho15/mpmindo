<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Banner extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('form_validation');
		$this->load->helper('form');
		$this->load->model('banner_model');
	}
	
	/**
	 * Index of banners
	 * 
	 * @access public
	 * @return void
	 */
	public function index()
	{
		if ($this->input->post(null, true))
		{
			$this->load->helper('format');
			$this->load->library('datatables');
			
			$this->datatables
			->select('b.banner_id, b.name, b.description, (SELECT COUNT(banner_image_id) FROM banner_image bi WHERE bi.banner_id = b.banner_id) as images')
			->from('banner b');
			
			$this->output->set_output($this->datatables->generate('json'));
		}
		else
		{
			if ( ! $this->admin->has_permission())
			{
				show_401($this->uri->uri_string());
			}
			
			$data['success'] = $this->session->userdata('success');
			$data['error'] = $this->session->userdata('error');
			
			$this->load
			->title('Banner')
			->view('admin/banner', $data);	
		}
	}
	
	/**
	 * Create new banner
	 * 
	 * @access public
	 * @return void
	 */
	public function create()
	{
		if ( ! $this->admin->has_permission())
		{
			show_401($this->uri->uri_string());
		}
		
		$this->load->title('Tambah banner');
		
		$this->form();
	}
	
	/**
	 * Edit existing user
	 * 
	 * @access public
	 * @param int $user_id
	 * @return void
	 */
	public function edit($banner_id = null)
	{
		if ( ! $this->admin->has_permission())
		{
			show_401($this->uri->uri_string());
		}
		
		$this->load->title('Edit banner');
		
		$this->form($banner_id);
	}
	
	/**
	 * Load banner form
	 * 
	 * @access private
	 * @param int $banner_id
	 * @return void
	 */
	private function form($banner_id = null)
	{
		
		$this->load->model('banner_model');
		
		$data['action']		= admin_url('banner/validate');
		$data['banner_id']	= null;
		$data['name']		= '';
		$data['description']= '';
		$data['active']		= 0;
		$data['images']		= array();
		
		$this->load->library('image');
		$data['no_image'] = $this->image->resize('no_image.jpg', 150, 150);
		
		if ($banner = $this->banner_model->get($banner_id))
		{
			$data['banner_id']	= (int)$banner['banner_id'];
			$data['name']		= $banner['name'];
			$data['description']= $banner['description'];
			$data['active']		= (bool)$banner['active'];
			$data['images']		= array();
			
			$images = $this->banner_model->get_images($banner_id);
			
			foreach ($images as $image)
			{
				if ( ! $image['image'])
				{
					$data['image'] = $data['no_image'];
				}
				
				$data['images'][] = array(
					'title'	=> $image['title'],
					'subtitle' => $image['subtitle'],
					'link' => $image['link'],
					'link_title' => $image['link_title'],
					'thumb' => $this->image->resize($image['image'], 150, 150),
					'image' => $image['image'],
					'active' => (bool)$image['active'],
					'sort_order' => $image['sort_order'],
				);
			}
		}
		
		$this->load->view('admin/banner_form', $data);
	}
	
	/**
	 * Validate banner form
	 * 
	 * @access public
	 * @return json
	 */
	public function validate()
	{
		check_ajax();
		
		$json = array();
		
		$banner_id = $this->input->post('banner_id');
		
		$this->form_validation
		->set_rules('name', 'Nama', 'trim|required')
		->set_error_delimiters('', '');
		
		if ($this->form_validation->run() == false)
		{
			foreach (array('name') as $field)
			{
				if (form_error($field) !== '')
				{
					$json['error'][$field] = form_error($field);
				}
			}
		}
		else
		{	
			if ($banner_id)
			{
				$this->banner_model->update($banner_id, $this->input->post(null, true));
				
				$json['success'] = 'Banner telah berhasil diperbarui.';
			}
			else
			{
				$banner_id = $this->banner_model->insert($this->input->post(null, true));
				
				$json['success'] = 'Banner baru telah berhasil ditambahkan.';
			}
			
			$images = ($this->input->post('banner_image') && is_array($this->input->post('banner_image'))) ? $this->input->post('banner_image') : array();
			
			$this->banner_model->set_images($banner_id, $images);
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	public function delete()
	{
		check_ajax();
		
		$json = array();
		
		$banner_id = $this->input->post('banner_id');
		
		if ( ! $this->admin->has_permission())
		{
			$json['error'] = lang('admin_error_delete');
		}
		
		if (empty($json['error']))
		{
			$this->banner_model->delete($banner_id);
			
			$json['success'] = 'Berhasil menghapus Banner!';
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	/**
	 * Set banner to active
	 * 
	 * @access public
	 * @return void
	 */
	public function set_active()
	{
		check_ajax();
		
		$json = array();
		
		$banner_id = $this->input->post('banner_id');
		
		if ( ! $this->admin->has_permission('edit'))
		{
			$json['error'] = lang('admin_error_edit');
		}
		
		if (empty($json['error']))
		{
			$this->banner_model->update($banner_id, array('active' => 1));
			
			$json['success'] = 'Berhasil mengaktifkan Banner!';
		}
		
		$this->output->set_output(json_encode($json));
	}
	
	/**
	 * Set banner to inactive
	 * 
	 * @access public
	 * @return void
	 */
	public function set_inactive()
	{
		check_ajax();
		
		$json = array();
		
		$banner_id = $this->input->post('banner_id');
		
		if ( ! $this->admin->has_permission('edit'))
		{
			$json['error'] = lang('admin_error_edit');
		}
		
		if (empty($json['error']))
		{
			$this->banner_model->update($banner_id, array('active' => 0));
			
			$json['success'] = 'Berhasil menonaktifkan Banner!';
		}
		
		$this->output->set_output(json_encode($json));
	}
}