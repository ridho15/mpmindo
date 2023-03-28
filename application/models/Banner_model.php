<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banner_Model extends MY_Model 
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
	}
	
	/**
	 * Get images
	 * 
	 * @access public
	 * @param int $banner_id
	 * @return array
	 */
	public function get_images($banner_id)
	{
		return $this->db
		->where('banner_id', $banner_id)
		->order_by('sort_order', 'asc')
		->get('banner_image')
		->result_array();
	}
	
	/**
	 * Set images
	 * 
	 * @access public
	 * @param int $banner_id
	 * @param array $images
	 * @return void
	 */
	public function set_images($banner_id, $images)
	{
		$this->db
		->where('banner_id', $banner_id)
		->delete('banner_image');
		
		$banner_image = array();
		
		foreach ($images as $image)
		{
			$banner_image[] = array(
				'banner_id' => $banner_id,
				'title' => $image['title'],
				'subtitle' => $image['subtitle'],
				'link' => $image['link'],
				'link_title' => $image['link_title'],
				'image' => $image['image'],
				'active' => isset($image['active']) ? (bool)$image['active'] : 0,
				'sort_order' => $image['sort_order'],
			);
		}
		
		if ($banner_image) $this->db->insert_batch('banner_image', $banner_image);
	}
	
	/**
	 * Delete
	 * 
	 * @access public
	 * @param mixed $banner_id
	 * @return void
	 */
	public function delete($banner_id)
	{
		if (is_array($banner_id)) {
			foreach ($banner_id as $id) {
				$this->delete($id);
			}
		} else {
			$this->db
			->where('banner_id', $banner_id)
			->delete(array('banner', 'banner_image'));	
		}
	}
}