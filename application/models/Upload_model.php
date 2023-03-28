<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Upload_Model extends MY_Model
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
	 * Delete upload data
	 * 
	 * @access public
	 * @access int $upload_id
	 * @return void
	 */
	public function delete_upload($upload_id)
	{	
		if ($upload = $this->get($upload_id)) {
			$this->delete($upload_id);
			
			if (file_exists($upload['full_path'])) {
				unlink($upload['full_path']);
			}
		}
	}
}