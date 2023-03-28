<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_Model extends MY_Model
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
	 * Get settings
	 * 
	 * @access public
	 * @return void
	 */
	public function get_settings()
	{
		$settings = array();
			
		$results = $this->db
		->get('setting')
		->result_array();
		
		foreach ($results as $result) {
			$settings[$result['group']][$result['key']] = ( ! $result['serialized']) ? $result['value'] : unserialize($result['value']);
		}
		
		return $settings;
	}
	
	/**
	 * Get setting
	 * 
	 * @access public
	 * @param string $group
	 * @param string $key
	 * @return void
	 */
	public function get_setting($group = '', $key = '')
	{
		$setting = $this->db
		->select('value, serialized')
		->where('group', $group)
		->where('key', $key)
		->get('setting')
		->row_array();
			
		if ($setting) {
			return ( ! $setting['serialized']) ? $setting['value'] : unserialize($setting['value']);
		}

		return false;
	}
	
	/**
	 * Edit setting
	 * 
	 * @access public
	 * @param string $group
	 * @param array $data
	 * @return void
	 */
	public function edit_setting($group = '', $data = array())
	{
		$settings = array();
		
		foreach ($data as $key => $value) {
			$settings[] = array(
				'group' => $group,
				'key' => $key,
				'value' => is_array($value) ? serialize($value) : $value,
				'serialized' => is_array($value) ? 1 : 0
			);
		}
		
		if ($settings) {
			$this->db
			->where('group', $group)
			->delete('setting');
			
			$this->db->insert_batch('setting', $settings);
		}
	}
	
	/**
	 * Delete setting
	 * 
	 * @access public
	 * @param string $group
	 * @return void
	 */
	public function delete_setting($group = '')
	{
		$this->db
		->where('group', $group)
		->delete('setting');
	}
	
	/**
	 * Edit setting value
	 * 
	 * @access public
	 * @param string $group
	 * @param string $key
	 * @param string $value
	 * @return int | bool
	 */
	public function edit_setting_value($group = '', $key = '', $value = '')
	{
		$return = false;
		
		$setting = $this->db
		->select('value, serialized')
		->where('group', $group)
		->where('key', $key)
		->get('setting')
		->row_array();
		
		if ($setting) {
			$this->db
			->where('group', $group)
			->where('key', $key)
			->set('value', is_array($value) ? serialize($value) : $value)
			->set('serialized', is_array($value) ? 1 : 0)
			->update('setting');
			
			if ($this->db->affected_rows()) {
				$return = true;
			}
		} else {
			$this->db
			->set(array(
				'group' => $group,
				'key' => $key,
				'value' => is_array($value) ? serialize($value) : $value,
				'serialized' => is_array($value) ? 1 : 0,
			))->insert('setting');
			
			$return = $this->db->insert_id();
		}
		
		return $return;
	}
}