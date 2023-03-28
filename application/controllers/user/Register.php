<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends MY_Controller
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
		
		$this->load->layout('default');
		$this->load->library('form_validation');
		$this->load->helper('form');
		$this->load->helper('language');
		$this->lang->load('user_register');
		$this->load->model('location_model');
		$this->load->model('address_model');
	}
	
	/**
	 * User register
	 * 
	 * @access public
	 * @return void
	 */
	public function index($type = null)
	{
		if ( ! $this->config->item('registration')) {
			redirect();
		}
		
		$json = array();
		
		if ($this->input->post() && $this->input->is_ajax_request()) {
			$this->form_validation
			->set_rules('name', 'lang:entry_name', 'trim|required|min_length[5]')
			->set_rules('email', 'lang:entry_email', 'trim|required|valid_email|callback__check_email|min_length[5]')
			->set_rules('password', 'lang:entry_password', 'trim|required|min_length[8]')
			->set_rules('confirm', 'lang:entry_confirm_password', 'trim|required|min_length[8]|matches[password]')
			->set_rules('gender', 'lang:entry_gender', 'trim|required')
			->set_rules('telephone', 'lang:entry_telephone', 'trim|required|min_length[3]|callback__check_telephone')
			->set_rules('job', 'lang:entry_job', 'trim|required')
			->set_rules('job_status', 'lang:entry_job_status', 'trim|required')
			->set_rules('address', 'lang:entry_address', 'trim|required')
			->set_rules('postcode', 'lang:entry_postcode', 'trim|required')
			->set_rules('province_id', 'lang:entry_province', 'trim|required')
			->set_rules('city_id', 'lang:entry_city', 'trim|required')
			->set_rules('subdistrict_id', 'lang:entry_subdistrict', 'trim|required')
			->set_error_delimiters('', '');
	
			if ($this->form_validation->run() == false) {
				foreach ($this->form_validation->get_errors() as $field => $error) {
					$json['error'][$field] = $error;
				}
			} else {
				$post = $this->input->post(null, true);
				
				$post['name'] = ucwords($post['name']);
				$post['ip'] = $this->input->ip_address();
				$post['dob'] = $this->input->post('dob_year').'-'.$this->input->post('dob_month').'-'.$this->input->post('dob_date');
				$post['type'] = 'b';
				$post['image'] = 'user.png';
				$post['active'] = 1;
				
				$this->load->model('user_model');
				
				$user_id = $this->user_model->create_user($post);
				if ($user_id) {
					//insert address
					$address['user_id'] = $user_id;
					$address['name'] = 'Alamat Utama';
					$address['address'] = $this->input->post('address');
					$address['postcode'] = $this->input->post('postcode');
					$address['province_id'] = $this->input->post('province_id');
					$address['city_id'] = $this->input->post('city_id');
					$address['subdistrict_id'] = $this->input->post('subdistrict_id');
					$province = $this->location_model->get_province($this->input->post('province_id'));
					$city = $this->location_model->get_city($this->input->post('city_id'));
					$subdistrict = $this->location_model->get_subdistrict($this->input->post('subdistrict_id'));
					$address['province'] = $province['name'];
					$address['city'] = $city['name'];
					$address['subdistrict'] = $subdistrict['name'];

					$address_id = $this->address_model->insert($address);
					if($address_id) {
						$addressdefault['address_id'] = $address_id;
						$this->user_model->update_user($user_id, $addressdefault);
					}

					$content = lang('email_success_body');
					$find = array('{name}', '{email}', '{password}', '{login}');
					$replace = array(
						'name' => $post['name'],
						'email' => $post['email'],
						'password' => $post['password'],
						'login' => user_url('login')
					);
					
					$data['subject'] = lang('email_success_subject');
					$data['content'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $content))));
					
					$this->load->library('email');
					$this->load->config('email');
					
					$this->email->from($this->config->item('sender'), $this->config->item('site_name'));
					$this->email->to($post['email']);
					$this->email->subject(lang('email_success_subject'));
					$this->email->message($this->load->layout(false)->view('email', $data, true));
					$this->email->send();
					
					$this->session->set_flashdata('register_success', true);
					
					$this->user->login($post['email'], $post['password'], true, true);
					
					// remove guest account
					$this->session->unset_userdata('guest');
					
					$redirect = $this->session->userdata('redirect');
					
					if ($redirect) {
						$this->session->unset_userdata('redirect');
						
						$json['redirect'] = site_url($redirect);
					} else {
						$json['redirect'] = user_url();
					}
				} else {
					$json['warning'] = 'Sorry, we can\'t processing your application at this moment!';
				}
			}
			
			$this->output->set_output(json_encode($json));
		} else {
			$data['heading_title'] = 'Pendaftaran Akun';
			
			$this->lang->load('calendar');
			
			$data['months'] = array(
				'01' => lang('cal_january'),
				'02' => lang('cal_february'),
				'03' => lang('cal_march'),
				'04' => lang('cal_april'),
				'05' => lang('cal_may'),
				'06' => lang('cal_june'),
				'07' => lang('cal_july'),
				'08' => lang('cal_august'),
				'09' => lang('cal_september'),
				'10' => lang('cal_october'),
				'11' => lang('cal_november'),
				'12' => lang('cal_december'),
			);
			
			$data['type'] = $type;
			$data['action'] = user_url('register/'.$type);
			$data['text_agree'] = sprintf(lang('text_agree'), site_url(), site_url());
			$data['text_account_already'] = sprintf(lang('text_account_already'), user_url('login'));
			$data['provinces'] = $this->location_model->get_provinces();

			if ($this->input->get('redirect')) {
				$this->session->set_userdata('redirect', $this->input->get('redirect'));
			}
		
			$this->load
			->breadcrumb(lang('heading_title'), user_url('register'))
			->view('user/register', $data);
		}
	}
	
	/**
	 * Check email callback
	 * 
	 * @access public
	 * @param string $email
	 * @return bool True if email exists otherwise false
	 */
	public function _check_email($email)
	{
		$this->load->model('user_model');
		
		if ($this->user_model->check_email($email)) {
			$this->form_validation->set_message('_check_email', 'Alamat email sudah digunakan!');
			
			return false;
		}
		
		return true;
	}
	
	/**
	 * Check telephone callback
	 * 
	 * @access public
	 * @param string $telephone
	 * @return bool
	 */
	public function _check_telephone($telephone)
	{
		if ($this->user_model->check_telephone($telephone)) {
			$this->form_validation->set_message('_check_telephone', 'No. handphone ini sudah digunakan!');
			return false;
		}
		
		return true;
	}
	
	/**
	 * Register success
	 * 
	 * @access public
	 * @return void
	 */
	public function success()
	{
		if ($this->session->flashdata('register_success')) {
			$this->load->view('user/register_success');
		} else {
			show_404();
		}
	}
}