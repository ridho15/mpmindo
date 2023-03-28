<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('category_model');
	}
	
	public function auto_complete()
	{
		$json = array();
		
		if ($this->input->get('filter_name'))
		{	
			$params = array(
				'filter_name' => $this->input->get('filter_name'),
				'start' => 0,
				'limit' => 20
			);
			
			foreach ($this->category_model->get_categories($params) as $category)
			{
				$json[] = array(
					'category_id' => $category['category_id'], 
					'name' => strip_tags(html_entity_decode($category['name'], ENT_QUOTES, 'UTF-8'))
				);	
				
			}
			
			$json[] = array(
				'category_id' => 0, 
				'name' => 'None'
			);	
		}
		
		$sort_order = array();
	
		foreach ($json as $key => $value)
		{
			$sort_order[$key] = $value['name'];
		}
		
		array_multisort($sort_order, SORT_ASC, $json);
	
		$this->output->set_output(json_encode($json));
	}
}