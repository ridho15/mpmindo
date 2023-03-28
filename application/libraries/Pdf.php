<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pdf
{
	public function __construct()
	{
		require_once(APPPATH.'third_party/dompdf/dompdf_config.inc.php');
		
		spl_autoload_register('DOMPDF_autoload');
	}
	
	public function pdf_create($html, $filename, $stream = TRUE)
	{
		$dompdf = new DOMPDF();
		//$dompdf->set_base_path(APPPATH);
		$dompdf->load_html($html);
		$dompdf->set_paper('a4', 'portrait');
		$dompdf->render();
	
		//$canvas = $dompdf->get_canvas();
		//$w = $canvas->get_width();
		//$h = $canvas->get_height();
		//$font = Font_Metrics::get_font('verdana', 'bold');
		//$canvas->page_text(200, $h-700, 'DELETED', $font, 50, array(0.85,0.85,0.85));
	
		$canvas = $dompdf->get_canvas();
		$font = Font_Metrics::get_font('helvetica');
		$canvas->page_text(34, 18, 'Halaman : {PAGE_NUM} dari {PAGE_COUNT}', $font, 6, array(0,0,0));
	
		if ($stream)
		{
			$dompdf->stream($filename.'.pdf', array('Attachment' => 0));
		}
		else
		{
			$ci =& get_instance();
			$ci->load->helper('file');
			
			write_file($filename, $dompdf->output());
		}
	}
	
	public function get_output($html)
	{
		$dompdf = new DOMPDF();
		$dompdf->load_html($html);
		$dompdf->set_paper('a4', 'portrait');
		$dompdf->render();
	
		$canvas = $dompdf->get_canvas();
		$font = Font_Metrics::get_font('helvetica');
		$canvas->page_text(40, 18, 'Halaman : {PAGE_NUM} dari {PAGE_COUNT}', $font, 6, array(0,0,0));
	
		return $dompdf->output();
	}
}