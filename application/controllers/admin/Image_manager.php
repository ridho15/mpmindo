<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Image_Manager extends Admin_Controller
{
	private $upload_image_path;
	private $error = array();
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->library('image');
		
		$this->upload_image_path = DIR_IMAGE.'data/';
	}
	
	public function index()
	{
		if ($this->input->get('filter_name')) {
			$filter_name = rtrim(str_replace(array('../', '..\\', '..', '*'), '', $this->input->get('filter_name')), '/');
		} else {
			$filter_name = null;
		}

		if ($this->input->get('directory')) {
			$directory = rtrim($this->upload_image_path.str_replace(array('../', '..\\', '..'), '', $this->input->get('directory')), '/');
		} else {
			$directory = rtrim($this->upload_image_path, '/');
		}

		if ($this->input->get('page')) {
			$page = $this->input->get('page');
		} else {
			$page = 1;
		}
		
		if ($this->input->get('width')) {
			$width = $this->input->get('width');
		} else {
			$width = 150;
		}
		
		if ($this->input->get('height')) {
			$height = $this->input->get('height');
		} else {
			$height = 150;
		}

		$data['images'] = array();

		$this->load->library('image');

		$directories = glob($directory.'/'.$filter_name.'*', GLOB_ONLYDIR);

		if ( ! $directories) {
			$directories = array();
		}

		$files = glob($directory.'/'.$filter_name.'*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}', GLOB_BRACE);

		if ( ! $files) {
			$files = array();
		}

		// Merge directories and files
		$images = array_merge($directories, $files);

		// Get total number of files and directories
		$image_total = count($images);

		// Split the array based on current page number and max number of items per page of 10
		$images = array_splice($images, ($page - 1) * 16, 16);

		foreach ($images as $image) {
			$name = str_split(basename($image), 14);

			if (is_dir($image)) {
				$url = '';

				if ($this->input->get('target')) {
					$url .= '&target='.$this->input->get('target');
				}

				if ($this->input->get('thumb')) {
					$url .= '&thumb='.$this->input->get('thumb');
				}

				$data['images'][] = array(
					'thumb' => '',
					'name'  => implode(' ', $name),
					'type'  => 'directory',
					'path'  => substr($image, strlen(DIR_IMAGE)),
					'href'  => admin_url('image_manager').'?directory='.urlencode(substr($image, strlen($this->upload_image_path))).$url
				);
			} elseif (is_file($image)) {
				$server = '';
				$data['images'][] = array(
					'thumb' => $this->image->resize(substr($image, strlen(DIR_IMAGE)), $width, $height),
					'name'  => implode(' ', $name),
					'type'  => 'image',
					'path'  => substr($image, strlen(DIR_IMAGE)),
					'href'  => HTTP_IMAGE.substr($image, strlen(DIR_IMAGE))
				);
			}
		}

		$data['heading_title'] = '';

		$data['text_no_results'] = '';
		$data['text_confirm'] = '';

		$data['entry_search'] = '';
		$data['entry_folder'] = '';

		$data['button_parent'] = '';
		$data['button_refresh'] = '';
		$data['button_upload'] = '';
		$data['button_folder'] = '';
		$data['button_delete'] = '';
		$data['button_search'] = '';

		$data['token'] = '';

		if ($this->input->get('directory')) {
			$data['directory'] = urlencode($this->input->get('directory'));
		} else {
			$data['directory'] = '';
		}

		if ($this->input->get('filter_name')) {
			$data['filter_name'] = $this->input->get('filter_name');
		} else {
			$data['filter_name'] = '';
		}

		// Return the target ID for the file manager to set the value
		if ($this->input->get('target')) {
			$data['target'] = $this->input->get('target');
		} else {
			$data['target'] = '';
		}

		// Return the thumbnail for the file manager to show a thumbnail
		if ($this->input->get('thumb')) {
			$data['thumb'] = $this->input->get('thumb');
		} else {
			$data['thumb'] = '';
		}

		// Parent
		$url = '';

		if ($this->input->get('directory')) {
			$pos = strrpos($this->input->get('directory'), '/');

			if ($pos) {
				$url .= '&directory='.urlencode(substr($this->input->get('directory'), 0, $pos));
			}
		}

		if ($this->input->get('target')) {
			$url .= '&target='.$this->input->get('target');
		}

		if ($this->input->get('thumb')) {
			$url .= '&thumb='.$this->input->get('thumb');
		}

		$data['parent'] = admin_url('image_manager?').$url;

		// Refresh
		$url = '';

		if ($this->input->get('directory')) {
			$url .= '&directory='.urlencode($this->input->get('directory'));
		}

		if ($this->input->get('target')) {
			$url .= '&target='.$this->input->get('target');
		}

		if ($this->input->get('thumb')) {
			$url .= '&thumb='.$this->input->get('thumb');
		}

		$data['refresh'] = admin_url('image_manager?').$url;

		$url = '?';

		if ($this->input->get('directory')) {
			$url .= 'directory='.urlencode(html_entity_decode($this->input->get('directory'), ENT_QUOTES, 'UTF-8'));
		}

		if ($this->input->get('filter_name')) {
			$url .= '&filter_name='.urlencode(html_entity_decode($this->input->get('filter_name'), ENT_QUOTES, 'UTF-8'));
		}

		if ($this->input->get('target')) {
			$url .= '&target='.$this->input->get('target');
		}

		if ($this->input->get('thumb')) {
			$url .= '&thumb='.$this->input->get('thumb');
		}
		
		$this->load->library('pagination');
		
		$config = array(
			'base_url'				=> admin_url('image_manager').$url,
			'total_rows'			=> $image_total,
			'per_page'				=> 16,
			'query_string_segment'	=> 'page',
			'page_query_string'		=> true,
			'full_tag_open'			=> '<ul class="pagination">',
			'full_tag_close'		=> '</ul>',
			'num_tag_open'			=> '<li>',
			'num_tag_close'			=> '</li>',
			'cur_tag_open'			=> '<li class="active"><a>',
			'cur_tag_close'			=> '</a></li>',
			'first_tag_open'		=> '<li>',
			'first_tag_close'		=> '</li>',
			'last_tag_open'			=> '<li>',
			'last_tag_close'		=> '</li>',
			'prev_tag_open'			=> '<li>',
			'prev_tag_close'		=> '</li>',
			'next_tag_open'			=> '<li>',
			'next_tag_close'		=> '</li>',
			'use_page_numbers'		=> true
		);
			
		$this->pagination->initialize($config);
		
		$data['pagination'] = $this->pagination->create_links();

		$this->load->layout(false)->view('admin/image_manager', $data);
	}

	public function upload()
	{
		$json = array();

		if ( ! $this->admin->has_permission('create')) {
			$json['error'] = lang('admin_error_create');
		}

		if ($this->input->get('directory')) {
			$directory = rtrim($this->upload_image_path.str_replace(array('../', '..\\', '..'), '', $this->input->get('directory')), '/');
		} else {
			$directory = rtrim($this->upload_image_path, '/');
		}

		if ( ! is_dir($directory)) {
			$json['error'] = 'Direktori tujuan tidak ditemukan!';
		}

		if ( ! $json) {
			$this->load->library('upload', array(
				'upload_path' => $directory,
				'allowed_types' => 'gif|jpg|png|jpeg|heif|tiff',
				'max_size' => '5120',
			));
	
			if ( ! $this->upload->do_upload()) {
				$json['error'] = $this->upload->display_errors('', '');
			} else {
				$json['success'] = 'File berhasil diunggah.';
			}
		}

		$this->output->set_output(json_encode($json));
	}

	public function folder() {
		$json = array();

		if ( ! $this->admin->has_permission('create')) {
			$json['error'] = lang('admin_error_create');
		}

		if ($this->input->get('directory')) {
			$directory = rtrim($this->upload_image_path.str_replace(array('../', '..\\', '..'), '', $this->input->get('directory')), '/');
		} else {
			$directory = rtrim($this->upload_image_path, '/');
		}

		if ( ! is_dir($directory)) {
			$json['error'] = 'Direktori tujuan tidak ditemukan!';
		}

		if ( ! $json) {
			$folder = str_replace(array('../', '..\\', '..'), '', basename(html_entity_decode($this->input->post('folder'), ENT_QUOTES, 'UTF-8')));

			if ((strlen($folder) < 3) || (strlen($folder) > 128)) {
				$json['error'] = 'Nama folder setidaknya harus 3 sampai 128 karakter';
			}

			if (is_dir($directory.'/'.$folder)) {
				$json['error'] = 'Folder sudah ada sebelumnya!';
			}
		}

		if ( ! $json) {
			mkdir($directory.'/'.$folder, 0777);
			$json['success'] = 'Folder baru berhasil dibuat!';
		}

		$this->output->set_output(json_encode($json));
	}

	public function delete()
	{
		$json = array();

		if ( ! $this->admin->has_permission('delete')) {
			$json['error'] = lang('admin_error_delete');
		}

		if ($this->input->post('path')) {
			$paths = $this->input->post('path');
		} else {
			$paths = array();
		}

		foreach ($paths as $path) {
			$path = rtrim(DIR_IMAGE.str_replace(array('../', '..\\', '..'), '', $path), '/');
			if ($path == rtrim($this->upload_image_path, '/')) {
				$json['error'] = 'Tidak dapat menghapus!';
				break;
			}
		}
		
		if (count($paths) == 0) {
			$json['error'] = 'Tidak ada file atau folder yang dipilih!';
		}

		if ( ! $json) {
			foreach ($paths as $path) {
				$path = rtrim(DIR_IMAGE.str_replace(array('../', '..\\', '..'), '', $path), '/');
				if (is_file($path)) {
					unlink($path);
				} elseif (is_dir($path)) {
					$files = array();
					$path = array($path.'*');
					while (count($path) != 0) {
						$next = array_shift($path);
						foreach (glob($next) as $file) {
							if (is_dir($file)) {
								$path[] = $file.'/*';
							}
							$files[] = $file;
						}
					}

					rsort($files);
					
					foreach ($files as $file) {
						if (is_file($file)) {
							unlink($file);
						} elseif (is_dir($file)) {
							rmdir($file);
						}
					}
				}
			}

			$json['success'] = 'Berhasil menghapus file atau folder!';
		}

		$this->output->set_output(json_encode($json));
	}
}