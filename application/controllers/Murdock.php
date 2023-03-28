<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Murdock extends Murdock_Controller
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
        $this->load->model('product_model');
        $this->load->model('category_model');
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->load->helper('language');
        $this->load->library('user_agent');
	}
	
	/**
	 * Index
	 * 
	 * @access public
	 * @param bool $slug (default: false)
	 * @return void
	 */
	public function index()
	{
        $data['products'] = $this->_get_products();
        $data['islogged'] = $this->_check_login();
        $data['category'] = $this->category_model->get_first_category();
        $data['mobile'] = $this->agent->is_mobile();
        if($this->agent->is_mobile()) {
            $this->load->layout(null)->view('murdock_mobile',$data);
        }
		else {
            $this->load->view('murdock',$data);
        }
    }
    
    /**
	 * Get carousels
	 * 
	 * @access public
	 * @return array
	 */
	public function _get_products()
	{
        // $params = array(
        //     'start' => 0,
        //     'limit' => 8,
        //     'sort'	=> 'p.viewed',
		// 	'order' => 'desc',
        // );
        
        // $products = array();

        // foreach ($this->product_model->get_products($params) as $product) {
        //     $path = '';
            
        //     if ($category = $this->category_model->get_category($product['category_id'])) {
        //         if ($category['path_id']) {
        //             $category_ids = explode('-', $category['path_id']);
        //             foreach ($category_ids as $category_id) {
        //                 if ($category_path = $this->category_model->get_category($category_id)) {
        //                     $path .= $category_path['slug'].'/';
        //                 }
        //             }
        //         }
        //         $path .= $category['slug'].'/';
        //     }
                        
        //     $name = strip_tags(html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8'));
        //     $path .= $product['slug'];
        //     $products[] = array(
        //         'product_id' => $product['product_id'],
        //         'thumb' => $product['image'],
        //         'name' => $name,
        //         'href' => site_url('product/'.$path),
        //         'category' => $category['name']
        //     );
        // }
		$products = array();
        $arrayId = array('577','578','582','576','580','583','581', '574');
        
        foreach($arrayId as $id) {
            foreach ($this->product_model->get_product_by_id($id) as $product) {
                $path = '';
                
                if ($category = $this->category_model->get_category($product['category_id'])) {
                    if ($category['path_id']) {
                        $category_ids = explode('-', $category['path_id']);
                        foreach ($category_ids as $category_id) {
                            if ($category_path = $this->category_model->get_category($category_id)) {
                                $path .= $category_path['slug'].'/';
                            }
                        }
                    }
                    $path .= $category['slug'].'/';
                }
                            
                $name = strip_tags(html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8'));
                $path .= $product['slug'];
                $products[] = array(
                    'product_id' => $product['product_id'],
                    'thumb' => $product['image'],
                    'name' => $name,
                    'href' => site_url('product/'.$path),
                    'category' => $category['name']
                );
            }
        }
        
		return $products;
    }
    
    /**
	 * send email
	 * 
	 * @access public
	 * @return void
	 */
	public function send()
	{
        $json = array();

		if ($this->input->post() && $this->input->is_ajax_request()) {
			$this->form_validation
			->set_rules('inputName', 'Nama', 'trim|required|min_length[3]')
			->set_rules('inputEmail', 'Email', 'trim|required|valid_email|min_length[5]')
			->set_rules('inputMessage', 'Pesan', 'trim|required')
			->set_error_delimiters('', '');
	
			if ($this->form_validation->run() == false) {
				foreach ($this->form_validation->get_errors() as $field => $error) {
					$json['error'][$field] = $error;
				}
			} else {
                $post = $this->input->post(null, true);

                $this->load->library('email');
                $this->load->config('email');
                $this->email->from($this->config->item('sender'), $this->config->item('site_name'));
                $this->email->to($this->config->item('office'));
                $this->email->subject('Ada Pesan Dari '.$post['inputName'].', '.$post['inputEmail'].' dikirim dari website MPM WAHL', ENT_QUOTES, 'UTF-8');
                $this->email->message($post['inputMessage']);
                $this->email->send();

				$json['redirect'] = site_url();
            }
            $this->output->set_output(json_encode($json));
        } 
        else {
            redirect(site_url());
        }
	}
}