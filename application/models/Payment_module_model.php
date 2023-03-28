<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_module_model extends MY_Model
{
	/**
	 * Primary key
	 * 
	 * @var string
	 * @access protected
	 */
	protected $primary_key = 'code';
	
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
	 * Match payment package files with database
	 * 
	 * @access public
	 * @return void
	 */
	public function matches()
	{
		$payments = array();
		$old = array();
		$new = array();
		$codes = array();
		$files = glob(APPPATH.'payment_modules/*');
		
		foreach ($this->get_all() as $payment) {
			$payments[$payment['code']] = $payment;
		}
		
		if ($files) {
			foreach ($files as $file) {
				if (is_dir($file)) {
					if (file_exists($file.'/manifest.php')) {
						$package = basename($file);
						require_once($file.'/manifest.php');
						
						if (isset($payments[$package])) {
							$old[] = array(
								'code' => $manifest['code'],
								'name' => $manifest['name'],
								'description' => $manifest['description'],
								'version' => $manifest['version']
							);
						} else {
							$new[] = array(
								'code' => $manifest['code'],
								'name' => $manifest['name'],
								'description' => $manifest['description'],
								'version' => $manifest['version'],
								'config' => serialize(array())
							);
						}
						
						$codes[] = $package;
					}
				}
			}
			
			$this->db->where_not_in('code', $codes)->delete('payment_module');
			
			if ($old) $this->db->update_batch('payment_module', $old, 'code');
			if ($new) $this->db->insert_batch('payment_module', $new);
		} else {
			$this->db->empty_table('payment_module');
		}
	}
	
	/**
	 * Is installed
	 * 
	 * @access public
	 * @param string $code
	 * @return bool
	 */
	public function is_installed($code)
	{
		return (bool)$this->db
		->where('code', $code)
		->where('active', 1)
		->count_all_results('payment_module');
	}
	
	/**
	 * Uninstall
	 * 
	 * @access public
	 * @param string $code
	 * @return void
	 */
	public function uninstall($code)
	{
		$this->db
		->set('active', 1)
		->where('code', $code)
		->update('payment_module');
	}
}