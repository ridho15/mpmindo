<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model
{
	/**
	 * Active group
	 * 
	 * @var string
	 * @access protected
	 */
	protected $active_group = 'default';
	
	/**
	 * Table name
	 * 
	 * @var string
	 * @access protected
	 */
	protected $table;
	
	/**
	 * Primary key
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $primary_key;
	
	/**
	 * Constructor
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->db = $this->load->database($this->active_group, true);
		
		$this->db->simple_query("SET SQL_MODE = 'NO_ZERO_DATE'");
		
		$this->_set_table();
	}
	
	/**
	 * Set table
	 * 
	 * @access private
	 * @return void
	 */
	private function _set_table()
	{
		if ($this->table == null) {
			$class = preg_replace('/(_model|_Model)?$/', '', get_class($this));
			$this->table = strtolower($class);
		}
		
		if ($this->primary_key == null) {
			$this->primary_key = $this->table.'_id';
		}
	}
	
	/**
	 * Set where
	 * 
	 * @access private
	 * @param array $params
	 * @return void
	 */
	private function _set_where($params = array())
	{
		if (count($params) == 1) {
			$this->db->where($params[0]);
		} else {
			if (is_numeric($params[1])) {
				$params[1] = (int)$params[1];
			}
			
			$this->db->where($params[0], $params[1]);
		}
	}
	
	/**
	 * Trigger
	 * 
	 * @access private
	 * @param string $event
	 * @param mixed $id
	 * @param mixed $data
	 * @return void
	 */
	private function _trigger($event, $id = null, $data = false)
	{
		if (!isset($this->$event) ||!is_array($this->$event)) {
			return $data;
		}
		
		foreach ($this->$event as $method) {
			call_user_func_array(array($this, $method), array($id, $data));
		}
	}
	
	/**
	 * Get date time for now
	 * 
	 * @access protected
	 * @return string
	 */
	protected function now()
	{
		return date('Y-m-d H:i:s', time());
	}
	
	/**
	 * Set data
	 * 
	 * @access protected
	 * @param array $data
	 * @param string $table
	 * @return array
	 */
	protected function set_data($data = array(), $table = null)
	{
		$table_name = is_null($table) ? $this->table : $table;
		
		$set_data = array();
		
		if ($this->db->field_exists('date_modified', $table_name)) {
			$set_data['date_modified'] = $this->now();
		}
		
		foreach ($this->db->field_data($table_name) as $field) {
			if (isset($data[$field->name])) {
				switch($field->type) {
					case 'int':
						$set_data[$field->name] = (int)$data[$field->name];
					break;
					
					case 'tinyint':
						$set_data[$field->name] = (int)$data[$field->name];
					break;
					
					case 'decimal':
						$set_data[$field->name] = (float)$data[$field->name];
					break;
					
					case 'date':
						$set_data[$field->name] = date('Y-m-d', strtotime($data[$field->name]));
					break;
					
					case 'datetime':
						$set_data[$field->name] = date('Y-m-d H:i:s', strtotime($data[$field->name]));
					break;
					
					default:
						$set_data[$field->name] = $data[$field->name];
					break;
				}
			}
		}
		
		return $set_data;
	}
	
	/**
	 * Get a single record
	 * 
	 * @access public
	 * @param mixed $primary_value
	 * @return array
	 */
	public function get($primary_value)
	{
		$this->_set_where(array($this->primary_key, $primary_value));
		
		return $this->db->get($this->table)->row_array();
	}
	
	/**
	 * Get a single record by parameter
	 * 
	 * @access public
	 * @return array
	 */
	public function get_by()
	{
		$where = func_get_args();
		
		if (!$where) {
			return array();
		}
		
		$this->_set_where($where);
		
		return $this->db
		->get($this->table)
		->row_array();
	}
	
	/**
	 * Get multiple records by parameters
	 * 
	 * @access public
	 * @return array
	 */
	public function get_all_by()
	{
		$where = func_get_args();
		
		if (!$where) {
			return array();
		}
		
		$this->_set_where($where);
		
		return $this->get_all();
	}
	
	/**
	 * Get multiple records
	 * 
	 * @access public
	 * @return array
	 */
	public function get_all()
	{
		return $this->db
		->get($this->table)
		->result_array();
	}
	
	/**
	 * Insert new record
	 * 
	 * @access public
	 * @param array $data
	 * @return void
	 */
	public function insert($data)
	{	
		if ($this->db->field_exists('date_added', $this->table)) {
			if (!isset($data['date_added'])) {
				$data['date_added'] = $this->now();
			}
		}
		
		$this->db->set($this->set_data($data));
		$this->db->insert($this->table);
		
		return $this->db->insert_id();
	}
	
	/**
	 * Insert batch
	 * 
	 * @access public
	 * @param array $data
	 * @return bool
	 */
	public function insert_batch($data)
	{
		$batch_data = array();
		
		foreach ($data as $value) {
			$batch_data[] = $this->set_data($value);
		}
		
		if ($batch_data) {
			$this->db->insert_batch($this->table, $batch_data);
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Update existing record
	 * 
	 * @access public
	 * @param mixed $primary_value
	 * @param array $data
	 * @return void
	 */
	public function update($primary_value, $data)
	{
		if (is_array($primary_value)) {
			$this->db->where_in($this->primary_key, (array)$primary_value);
		} else {
			$this->db->where($this->primary_key, $primary_value);
		}
		
		$this->db->set($this->set_data($data));
		$this->db->update($this->table);
		
		return $this->db->affected_rows();
	}
	
	/**
	 * Delete permanently
	 * 
	 * @access public
	 * @param mixed $primary_value
	 * @return void
	 */
	public function delete($primary_value)
	{
		if (is_array($primary_value)) {
			$this->db->where_in($this->primary_key, (array)$primary_value);
		} else {
			$this->db->where($this->primary_key, $primary_value);
		}
		
		$this->db->delete($this->table);
		
		return $this->db->affected_rows();
	}
	
	/**
	 * Delete permanently by condition
	 * 
	 * @access public
	 * @return void
	 */
	public function delete_by()
	{
		$where = func_get_args();
		
		if (!$where) {
			return false;
		}
		
		$this->_set_where($where);
		
		$this->db->delete($this->table);
		
		return $this->db->affected_rows();
	}
	
	/**
	 * Temporary delete
	 * 
	 * @access public
	 * @param mixed $primary_value
	 * @return void
	 */
	public function soft_delete($primary_value)
	{	
		if ($this->db->field_exists('date_deleted', $this->table)) {
			if (is_array($primary_value)) {
				$this->db->where_in($this->primary_key, (array)$primary_value);
			} else {
				$this->db->where($this->primary_key, $primary_value);
			}
		
			$this->db->set('date_deleted', $this->now());
			$this->db->update($this->table);
			
			return $this->db->affected_rows();
		}
		
		return false;
	}
	
	/**
	 * Temporary delete by condition
	 * 
	 * @access public
	 * @return void
	 */
	public function soft_delete_by()
	{
		if ($this->db->field_exists('date_deleted', $this->table)) {
			$where = func_get_args();
		
			if (!$where) {
				return false;
			}
			
			$this->_set_where($where);
			
			$this->db->set('date_deleted', $this->now());
			$this->db->update($this->table);
			
			return $this->db->affected_rows();
		}
		
		return false;
	}
	
	/**
	 * Restore temporary deleted record
	 * 
	 * @access public
	 * @return void
	 */
	public function undelete($primary_value)
	{	
		if ($this->db->field_exists('date_deleted', $this->table)) {
			if (is_array($primary_value)) {
				$this->db->where_in($this->primary_key, (array)$primary_value);
			} else {
				$this->db->where($this->primary_key, $primary_value);
			}
		
			$this->db->set('date_deleted', null);
			$this->db->update($this->table);
			
			return $this->db->affected_rows();
		}
		
		return false;
	}
	
	/**
	 * Restore temporary deleted record by condition
	 * 
	 * @access public
	 * @return void
	 */
	public function undelete_by()
	{
		if ($this->db->field_exists('date_deleted', $this->table)) {
			$where = func_get_args();
		
			if (!$where) {
				return false;
			}
			
			$this->_set_where($where);
			
			$this->db->set('date_deleted', null);
			$this->db->update($this->table);
			
			return $this->db->affected_rows();
		}
		
		return false;
	}
	
	/**
	 * List fields
	 * 
	 * @access public
	 * @return array
	 */
	public function list_fields()
	{
		return $this->db->list_fields($this->table);
	}
	
	/**
	 * Set translation
	 * 
	 * @access public
	 * @param int $primary_value
	 * @param array $translations
	 * @return void
	 */
	public function set_translations($primary_value, $translations)
	{
		$table = $this->table.'_lang';
		
		if ($this->db->table_exists($table)) {
			$this->db->where($this->primary_key, $primary_value)->delete($table);
			$batch_data = array();
		
			foreach ($translations as $lang => $value) {
				$value[$this->primary_key] = $primary_value;
				$value['lang'] = $lang;
				
				if ($this->db->field_exists('slug', $table)) {
					$slug = false;
					
					if (isset($value['name'])) {
						$slug = $value['name'];
					} elseif (isset($value['title'])) {
						$slug = $value['title'];
					}
					
					if ($slug) {
						$value['slug'] = $this->_validate_slug(strtolower(url_title($slug)), $primary_value, null, $lang);	
					}
				}
				
				$batch_data[] = $this->set_data($value, $table);
			}
			
			if ($batch_data) {
				$this->db->insert_batch($table, $batch_data);
			}
		}
	}
	
	/**
	 * Get translations
	 * 
	 * @access public
	 * @param int $primary_value
	 * @return array
	 */
	public function get_translations($primary_value, $lang = null)
	{
		$table = $this->table.'_lang';
		$translations = [];
		
		if ($this->db->table_exists($table)) {
			if ($lang) {
				$this->db->where('lang', $lang);
			}
			
			$results = $this->db
			->where($this->primary_key, $primary_value)
			->get($table)
			->result_array();
			
			foreach ($results as $result) {
				$translations[$result['lang']] = $result;
			}
		}
		
		return $translations;
	}
	
	/**
	 * Validate slug
	 * 
	 * @access private
	 * @param string $slug
	 * @param int $primary_value
	 * @param int $counter
	 * @param string $lang
	 * @return string
	 */
	private function _validate_slug($slug = '', $primary_value = null, $counter = null, $lang = '')
	{
		if ($primary_value) {
			$this->db->where($this->primary_key.' !=', $primary_value);
		}
		
		$count = $this->db
		->select('slug')
		->from($this->table.'_lang')
		->where('slug', $slug.($counter ? '-'.$counter : ''))
		->where('lang', $lang)
		->count_all_results();
		
		if ($count > 0) {
			if ( ! $counter) {
				$counter = 1;
			} else {
				$counter++;
			}
		
			return $this->_validate_slug($slug, $primary_value, $counter, $lang);
		} else {
			return $slug.($counter ? '-'.$counter : '');
		}
	}
}